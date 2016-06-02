<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property integer $pin
 * @property integer $updateInterval
 * @property string $title
 * @property string $icon
 */
class Item extends ActiveRecord
{
    const TYPE_RELAY = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'pin', 'title', 'icon', 'updateInterval'], 'required'],
            [['type', 'pin', 'updateInterval'], 'integer'],
            [['name', 'title', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'name' => 'Тех. название',
            'pin' => 'Пин',
            'updateInterval' => 'Интервал обновления',
            'title' => 'Название',
            'icon' => 'Иконка',
        ];
    }

    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_RELAY => 'Реле',
        ];
    }

    /**
     * @return string
     */
    public function getTypeLabel()
    {
        return self::getTypesArray()[$this->type];
    }
}
