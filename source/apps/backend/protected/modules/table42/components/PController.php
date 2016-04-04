<?php
class PController extends Controller//SBaseController
{
	public $layout = '//layouts/column2';
	public $menu = array();
	public $breadcrumbs = array();
	
	public function beforeAction($action) {
		
		$this->menu=array(
			array(
				'label'=>'Rounds', 'url'=>array('//table42/table42Round/admin'),
				'items' => array(
					array('label'=>'Create a Round', 'url'=>array('//table42/table42Round/create'))											
				),
			),
			array(
					'label'=>'Profiles', 'url'=>array('//table42/table42Profile/admin'),
					'items' => array(
						array('label'=>'Not Approved', 'url'=>array('//table42/table42Profile/notapproved')),
						array('label'=>'Approved', 'url'=>array('//table42/table42Profile/approved'))
					),
			),
			array(
					'label'=>'Couples', 'url'=>array('//table42/Table42DatingRequest/admin'),
						'items' => array(
						array('label'=>'Win Couples', 'url'=>array('//table42/Table42DatingRequest/win')),
						array('label'=>'Top Vote Couples', 'url'=>array('//table42/Table42DatingRequest/topvote')),
					),
			),
			array(
					'label'=>'Results', 'url'=>array('//table42/Table42Result/admin'),
					'items' => array(
							array('label'=>'Create a Result', 'url'=>array('//table42/Table42Result/create'))
					),
			),												
		);
		
		return parent::beforeAction($action);
	}
}