<?php
class RateHistory extends EMongoDocument
{
	public $view_id;
	public $view_username;
	public $viewed_id;
	public $viewed_username;
	public $rate_type;
	public $address_ip;
	public $device;
	public $lastupdate;
	
	
	public function getMongoDBComponent() {
		return Yii::app()->mongodb_plun;
	}	

	// This has to be defined in every model, this is same as with standard Yii ActiveRecord
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// This method is required!
	public function getCollectionName()
	{
		return 'rate_history';
	}

	public function rules()
	{
		return array(
			array('view_id', 'required'),
			array('view_id', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
				'user_id'   => 'UserID',
				'username'   => 'Username',
		);
	}
	
	public function embeddedDocuments()
	{
		return array(
				// property name => embedded document class name
				'view'=>'VstUserView',
				'viewed'=>'VstUserViewed',
		);
	}	
}