<?php
class Controller extends CController//SBaseController
{
	public $layout = '//layouts/column2';
	public $menu = array();
	public $breadcrumbs = array();
	
	public function beforeAction($action) {
		
		$this->menu=array(
			array(
				'label'=>'Event',
				'items' => array(
					array('label'=>'Events Manager', 'url'=>array('//coupon/events/admin')),
					array('label'=>'Create a Event', 'url'=>array('//coupon/events/create'))											
				),
			),
			array(
				'label'=>'Gift Code',
				'items' => array(
					array('label'=>'Giftcode Manager', 'url'=>array('//coupon/giftcode/admin')),
					array('label'=>'Import Giftcode', 'url'=>array('//coupon/giftcode/import')),						
					array('label'=>'Generate Giftcode', 'url'=>array('//coupon/giftcode/generate')),						
				),
			),
			array('label'=>'Manage White Party Manila', 'url'=>array('//coupon/whitePartyManila/Admin')),
		);
		
		return parent::beforeAction($action);
	}
}