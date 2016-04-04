<?php

class ActivityActiveRecord extends CActiveRecord
{

    private static $db_activity = null;

    public $total;

    protected static function getDbLogConnection ()
    {
        if (self::$db_activity !== null)
            return self::$db_activity;
        else {
            self::$db_activity = Yii::app()->db_activity;
            if (self::$db_activity instanceof CDbConnection) {
                self::$db_activity->setActive(true);
                return self::$db_activity;
            } else
                throw new CDbException(
                        Yii::t('yii', 
                                'Active Record requires a "db" CDbConnection application component.'));
        }
    }
}