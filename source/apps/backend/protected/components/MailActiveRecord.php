<?php
class MailActiveRecord extends CActiveRecord{
    private static $db_mail = null;
    public $total;

    public function getDbConnection ()
    {
        if (self::$db_mail !== null)
            return self::$db_mail;
        else {
            self::$db_mail = Yii::app()->db_mail;
            if (self::$db_mail instanceof CDbConnection) {
                self::$db_mail->setActive(true);
                return self::$db_mail;
            } else
                throw new CDbException(
                        Yii::t('yii', 
                                'Active Record requires a "db" CDbConnection application component.'));
        }
    }
}