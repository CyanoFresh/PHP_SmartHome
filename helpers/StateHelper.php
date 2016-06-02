<?php

namespace app\helpers;

/**
 * Class StateHelper
 *
 * For converting types of states
 *
 * @package app\helpers
 */
class StateHelper
{
    const STATE_INT_ON = 1;
    const STATE_INT_OFF = 0;

    /**
     * @param integer $int
     * @return string
     */
    public static function intToState($int)
    {
        return $int === 1 ? 'On' : 'Off';
    }

    /**
     * @param boolean $bool
     * @return string
     */
    public static function boolToState($bool)
    {
        return $bool ? 'On' : 'Off';
    }

    /**
     * @param integer $int
     * @return bool
     */
    public static function intToBool($int)
    {
        return $int === 1 ? true : false;
    }

    /**
     * @param boolean $bool
     * @return int
     */
    public static function boolToInt($bool)
    {
        return $bool ? 1 : 0;
    }

    /**
     * @return array
     */
    public static function getIntStatesArray()
    {
        return [
            self::STATE_INT_ON => 'Включение',
            self::STATE_INT_OFF => 'Выключение',
        ];
    }

    /**
     * @param $int
     * @return string
     */
    public static function getIntStateLabel($int)
    {
        return self::getIntStatesArray()[$int];
    }
}