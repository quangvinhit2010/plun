<?php

class ImportForm extends CFormModel
{
	public $event;
	public $code;

	public function rules()
	{
		return array(
			array('event,code', 'required'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'event' => 'Event',
			'code' => 'Code',
		);
	}
}