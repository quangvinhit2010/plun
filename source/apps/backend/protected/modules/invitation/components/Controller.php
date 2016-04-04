<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController //SBaseController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	public $layout = '//layouts/column2';
	public function beforeAction($action) {
		$user = Yii::app()->user;
		$this->menu=array(
			array(
				'label'=>'Invite History',
				'items' => array(
					array('label'=>'Danh sách', 'url'=>array('//invitation/history/admin')),
					array('label'=>'Tạo mới', 'url'=>array('//invitation/history/create')),
				)
			),
			array(
				'label'=>'Gamer nhận phần thưởng',
				'items' => array(
					array('label'=>'Danh sách', 'url'=>array('//invitation/bonus')),
				)
			),			
			array(
				'label'=>'Tỉ lệ',
				'items' => array(
					array('label'=>'Danh sách', 'url'=>array('//invitation/timelinerate/admin')),
					array('label'=>'Tạo mới', 'url'=>array('//invitation/timelinerate/create')),
				), 
				'visible' => ($user->name == 'admin') ? true : false,
				
			),			
		);
		
		return parent::beforeAction($action);
	}
}