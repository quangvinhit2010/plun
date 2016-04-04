<?php
class PController extends Controller//SBaseController
{
	public $layout = '//layouts/column2';
	public $menu = array();
	public $breadcrumbs = array();
	
	public function beforeAction($action) {
		
		$this->menu=array(
			array(
				'label'=>'Events', 'url'=>array('//purpleguy/purpleguyEvent/admin'),
				'items' => array(
					array('label'=>'Create a Event', 'url'=>array('//purpleguy/purpleguyEvent/create'))											
				),
			),
			array(
				'label'=>'Round', 'url'=>array('//purpleguy/purpleguyRound/admin'),
				'items' => array(
					array('label'=>'Create a Round', 'url'=>array('//purpleguy/purpleguyRound/create')),
				),
			),
			array(
				'label'=>'Profile', 'url'=>array('//purpleguy/purpleguyProfile/admin'),
			),
		);
		
		return parent::beforeAction($action);
	}
}