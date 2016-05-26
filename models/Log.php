<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $item_id
 * @property integer $date
 * @property integer $value
 */
class Log extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'item_id', 'date', 'value'], 'required'],
            [['type', 'user_id', 'item_id', 'date', 'value'], 'integer'],
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
            'user_id' => 'Пользователь',
            'item_id' => 'Компонент',
            'value' => 'Значение',
            'date' => 'Дата',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
