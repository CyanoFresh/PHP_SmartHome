<?php

namespace app\servers;

use app\models\Item;
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

        // Database driver hack
        Yii::$app->db->createCommand('SET SESSION wait_timeout = 31536000;')->execute();

        if ($this->checkConnection()) {
            echo 'Connection is active' . PHP_EOL;

            /** @var Item[] $items */
            $items = Item::find()->all();

            foreach ($items as $item) {
                $state = $this->getItemState($item);
                echo 'Item ' . $item->name . ' [' . $item->id . '] is ' . $this->boolToState($state) . PHP_EOL;
            }
        } else {
            echo 'No connection' . PHP_EOL;
        }
    }

    /**
     * @inheritdoc
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $connection = $this->checkConnection();

        $items = [];

        if ($connection) {
            /** @var Item[] $items */
            $itemModels = Item::find()->all();

            foreach ($itemModels as $item) {
                $items[] = ArrayHelper::merge($item->toArray(), [
                    'state' => $this->getItemState($item),
                ]);
            }
        }

        $this->clients[$conn->resourceId] = $conn;

        $conn->send(Json::encode([
            'type' => 'welcome',
            'connection' => $connection,
            'items' => $items,
        ]));

        echo 'Connected new user' . PHP_EOL;
    }

    /**
     * @inheritdoc
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = Json::decode($msg);

        switch ($data['type']) {
            case 'toggle':
                $itemID = (int)$data['itemID'];
                $item = Item::findOne($itemID);

                $this->toggleItemState($item);

                $this->sendAll([
                    'type' => 'itemState',
                    'itemID' => $item->id,
                    'state' => $this->getItemState($item),
                ]);

                break;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function onClose(ConnectionInterface $conn)
    {
        if (isset($this->clients[$conn->resourceId])) {
            unset($this->clients[$conn->resourceId]);
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
        $response = $this->curl->get($this->config['arestURL'] . '/' . $url);

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
     * @param Item $item
     * @return bool
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
}
