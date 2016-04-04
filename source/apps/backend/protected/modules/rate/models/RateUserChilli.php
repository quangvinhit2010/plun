<?php
class RateUserChilli extends EMongoEmbeddedDocument
{
	public $total;
	public $lastupdate;
	
	public function rules()
	{
		return array(	
			array('total, lastupdate', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
				'total'   => 'Total',
		);
	}
	
}