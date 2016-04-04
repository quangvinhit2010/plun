<?php

class CandyActiveRecord extends CActiveRecord
{

    private static $db_candy = null;

    public $total;

    protected static function getDbLogConnection ()
    {
        if (self::$db_candy !== null)
            return self::$db_candy;
        else {
            self::$db_candy = Yii::app()->db_candy;
            if (self::$db_candy instanceof CDbConnection) {
                self::$db_candy->setActive(true);
                return self::$db_candy;
            } else
                throw new CDbException(
                        Yii::t('yii', 
                                'Active Record requires a "db" CDbConnection application component.'));
        }
    }
}