<?php

class SearchActiveRecord extends CActiveRecord
{

    private static $db_search = null;

    public $total;

    public function getDbConnection ()
    {
        if (self::$db_search !== null)
            return self::$db_search;
        else {
            self::$db_search = Yii::app()->db_search;
            if (self::$db_search instanceof CDbConnection) {
                self::$db_search->setActive(true);
                return self::$db_search;
            } else
                throw new CDbException(
                        Yii::t('yii', 
                                'Active Record requires a "db" CDbConnection application component.'));
        }
    }
}