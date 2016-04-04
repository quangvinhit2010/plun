<?php
class EventActiveRecord extends CActiveRecord{
    private static $db_event = null;

    public function getDbConnection ()
    {
        if (self::$db_event !== null)
            return self::$db_event;
        else {
            self::$db_event = Yii::app()->db_event;
            if (self::$db_event instanceof CDbConnection) {
                self::$db_event->setActive(true);
                return self::$db_event;
            } else
                throw new CDbException(
                        Yii::t('yii', 
                                'Active Record requires a "db" CDbConnection application component.'));
        }
    }
}