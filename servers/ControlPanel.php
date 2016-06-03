<?php

namespace app\servers;

use app\helpers\StateHelper;
use app\models\Item;
use app\models\Log;
use app\models\User;
use linslin\yii2\curl\Curl;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Class ControlPanel
 * @package app\servers
 */
class ControlPanel implements MessageComponentInterface
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    protected $loop;

    /**
     * @var ConnectionInterface[] array
     */
    protected $clients;

    /**
     * @var array
     */
    protected $itemTimers;

    /**
     * @var array Server configuration
     */
    protected $config;

    /**
     * @var Curl CURL component
     */
    protected $curl;

    /**
     * @inheritdoc
     */
    public function __construct($loop, $config)
    {
        $this->loop = $loop;
        $this->config = $config;
        $this->clients = [];

        $this->curl = new Curl();
        $this->curl->setOption(CURLOPT_TIMEOUT, 2);

        // Database driver hack
        Yii::$app->db->createCommand('SET SESSION wait_timeout = 31536000;')->execute();

        if ($this->checkConnection()) {
            $this->o('Connection is active');

            /** @var Item[] $items */
            $items = Item::find()->all();

            foreach ($items as $item) {
                $state = $this->getItemState($item);

                $this->o("Item {$item->name} [{$item->id}] is {$this->boolToState($state)}");

                // Set timer for state updating
                if ($item->updateInterval > 0) {
                    $this->itemTimers[$item->id] = $this->loop->addPeriodicTimer($item->updateInterval,
                        function () use (&$item) {
                            if (count($this->clients) > 0 and $this->checkConnection()) {
                                $this->sendAll([
                                    'type' => 'itemState',
                                    'itemID' => $item->id,
                                    'state' => $this->getItemState($item),
                                ]);
                            }
                        });
                }
            }
        } else {
            $this->o('No connection');
        }
    }

    /**
     * @inheritdoc
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Auth
        $uid = $conn->WebSocket->request->getQuery()->get('uid');
        $auth_key = $conn->WebSocket->request->getQuery()->get('auth_key');

        $user = User::findOne([
            'id' => $uid,
            'auth_key' => $auth_key,
            'status' => User::STATUS_ACTIVE,
        ]);

        if (!$user) {
            $this->o("Authorization failed with User [$uid]");

            $conn->send(Json::encode([
                'type' => 'error',
                'message' => 'Не удалось авторизоваться',
            ]));

            return $conn->close();
        }

        // Close duplicating connection
        if (isset($this->clients[$user->id])) {
            $this->clients[$user->id]->close();
        }

        $user->generateAuthKey();
        $user->save();

        // Handle user
        $conn->User = $user;
        $this->clients[$user->id] = $conn;

        // Check Arduino Connection
        $connection = $this->checkConnection();

        // Get items
        $items = [];

        if ($connection) {
            /** @var Item[] $itemModels */
            $itemModels = Item::find()->all();

            foreach ($itemModels as $item) {
                // Set timer for state updating
                if ($item->updateInterval > 0 and isset($this->itemTimers[$item->id])) {
                    if (isset($this->itemTimers[$item->id])) {
                        $this->itemTimers[$item->id]->cancel();
                    }

                    $this->itemTimers[$item->id] = $this->loop->addPeriodicTimer($item->updateInterval,
                        function () use (&$item) {
                            if (count($this->clients) > 0 and $this->checkConnection()) {
                                $this->sendAll([
                                    'type' => 'itemState',
                                    'itemID' => $item->id,
                                    'state' => $this->getItemState($item),
                                ]);
                            }
                        });
                } elseif (isset($this->itemTimers[$item->id])) {
                    $this->itemTimers[$item->id]->cancel();
                }

                $items[] = ArrayHelper::merge($item->toArray(), [
                    'state' => $this->getItemState($item),
                ]);
            }
        }

        // Welcome
        $conn->send(Json::encode([
            'type' => 'welcome',
            'connection' => $connection,
            'items' => $items,
        ]));

        // Logging
        return $this->o("User [$uid] connected");
    }

    /**
     * @inheritdoc
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = Json::decode($msg);
        /** @var User $user */
        $user = $from->User;

        switch ($data['type']) {
            case 'toggle':
                $itemID = (int)$data['itemID'];
                $item = Item::findOne($itemID);

                $newState = $this->toggleItemState($item);

                $this->sendAll([
                    'type' => 'itemState',
                    'itemID' => $item->id,
                    'state' => $newState,
                ]);

                $this->log($user, $item, $newState);

                break;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function onClose(ConnectionInterface $conn)
    {
        if (isset($conn->User)) {
            unset($this->clients[$conn->User->id]);

            $this->o("User [{$conn->User->id}] disconnected");
        }
    }

    /**
     * @inheritdoc
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e->getMessage() . ': ' . $e->getFile() . ' : ' . $e->getLine() . PHP_EOL;

        $conn->close();
    }

    /**
     * Send data to user by id
     * @param integer $uid
     * @param array $data Will be encoded to json
     */
    private function send($uid, $data)
    {
        $jsonData = Json::encode($data);

        $this->clients[$uid]->send($jsonData);
    }

    /**
     * Send data to all clients
     * @param array $data Will be encoded to json
     */
    private function sendAll($data)
    {
        $jsonData = Json::encode($data);

        foreach ($this->clients as $client) {
            /** @var ConnectionInterface $client */
            $client->send($jsonData);
        }
    }

    /**
     * Make request to arduino
     * @param $url
     * @return bool|mixed
     */
    private function get($url)
    {
        try {
            $response = $this->curl->get($this->config['arestURL'] . '/' . $url);
        } catch (\Exception $e) {
            return false;
        }

        if ($response) {
            return Json::decode($response);
        }

        return false;
    }

    /**
     * Make a request to REST to check connection
     * @return boolean
     */
    private function checkConnection()
    {
        $data = $this->get('');

        if (!$data or !$data['connected']) {
            return false;
        }

        return true;
    }

    /**
     * Get item state
     * @param Item $itemModel
     * @return bool
     */
    private function getItemState($itemModel)
    {
        $response = $this->get('digital/' . $itemModel->pin);

        if ($response) {
            return $response['return_value'] === 0;
        }

        return false;
    }

    /**
     * Toggle item state
     *
     * @param Item $item
     * @return bool Current Item state
     */
    private function toggleItemState($item)
    {
        $itemState = $this->getItemState($item);

        if ($itemState) {
            echo 'Turning item [' . $item->id . '] Off' . PHP_EOL;
            $this->get('digital/' . $item->pin . '/1/');

            return false;
        }

        echo 'Turning item [' . $item->id . '] On' . PHP_EOL;
        $this->get('digital/' . $item->pin . '/0/');

        return true;
    }

    /**
     * @param $bool
     * @return string
     */
    private function boolToState($bool)
    {
        return $bool ? 'On' : 'Off';
    }

    /**
     * Logs user action
     *
     * @param User $user
     * @param Item $item
     * @param boolean $newState
     */
    private function log($user, $item, $newState)
    {
        $model = new Log();

        $model->type = Log::TYPE_STATE;
        $model->user_id = $user->id;
        $model->item_id = $item->id;
        $model->value = StateHelper::boolToInt($newState);

        if (!$model->save()) {
            $this->o('Cannot save log from User [' . $user->username . ']');
            VarDumper::dump($model->errors);
        }
    }

    /**
     * Output to console
     *
     * @param string $message
     * @param boolean $eol
     * @return bool
     */
    private function o($message, $eol = true)
    {
        echo $message;

        if ($eol) {
            echo PHP_EOL;
        }

        return true;
    }
}
