<?php
class VstUserViewed extends EMongoEmbeddedDocument
{
	public $total;
	public $limit;
	public $expire;
	
	public function rules()
	{
		return array(
				array('total, limit, expire', 'numerical', 'integerOnly'=>true),
		);
	}
	
	public function attributeLabels()
	{
		return array(
				'total'   => 'Total',
		);
	}	
}