<?php
class VstUserView extends EMongoEmbeddedDocument
{
	public $total;
	public $limit;
	public $limit_pay;	
	public $expire;	
	
	public function rules()
	{
		return array(	
			array('total, limit, limit_pay, expire', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
				'total'   => 'Total',
		);
	}
	
}