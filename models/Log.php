<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
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
 *
 * @property User $user
 * @property Item $item
 */
class Log extends ActiveRecord
{
    const TYPE_STATE = 10;
    const TYPE_LOGIN = 50;
    const TYPE_LOGOUT = 60;

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
            [['type', 'user_id'], 'required'],
            [['type', 'user_id', 'item_id', 'value'], 'integer'],
            [['date'], 'safe'],
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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_STATE => 'Переключение',
            self::TYPE_LOGIN => 'Вошел',
            self::TYPE_LOGOUT => 'Вышел',
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
