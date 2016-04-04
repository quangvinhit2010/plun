<?php

class GiftcodeForm extends CFormModel
{
	public $event;
	public $formula;
	public $numberOfDigit;
	public $quantity;
	public $type;
	
	public function rules()
	{
		return array(
			array('event,formula,quantity,type', 'required'),
			array('quantity,event,type,numberOfDigit', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
			'event' 	 => 'Event',
			'formula' 	 => 'Formula',
			'quantity' 	 => 'Quantity',
			'type' 		 => 'Type',
			'numberOfDigit' => 'Number of Digit in GiftCode',
		);
	}
}