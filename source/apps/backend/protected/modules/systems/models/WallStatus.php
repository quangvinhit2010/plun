<?php

class WallStatus extends CFormModel {
	public $status;
	
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'safe', 'on'=>'search'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'status' => 'Status',
		);
	}
}

?>