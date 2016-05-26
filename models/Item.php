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
            [['type', 'name', 'pin', 'title', 'icon'], 'required'],
            [['type', 'pin'], 'integer'],
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
            'type' => 'Type',
            'name' => 'Name',
            'pin' => 'Pin',
            'title' => 'Title',
            'icon' => 'Icon',
        ];
    }

    public function getTypes()
    {
        return [
            self::TYPE_RELAY => 'Реле',
        ];
    }

    public function getTypeLabel()
    {
        return $this->getTypes()[$this->type];
    }
}
