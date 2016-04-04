<?php 
class ChatActiveRecord extends CActiveRecord {
    
	public function getDbConnection()
	{
		return Yii::app()->db_openfire;
	}
	
}