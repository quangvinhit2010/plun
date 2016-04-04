<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
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
	
	public $currentround	=	null;
	
	public $signup_ok		=	false;
	
	public function init(){
		// register class paths for extension captcha extended
		Yii::$classMap = array_merge( Yii::$classMap, array(
			'CaptchaExtendedAction' => Yii::getPathOfAlias('backend').DIRECTORY_SEPARATOR. 'extensions' . DIRECTORY_SEPARATOR . 'captchaExtended' . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
			'CaptchaExtendedValidator' => Yii::getPathOfAlias('backend').DIRECTORY_SEPARATOR. 'extensions' . DIRECTORY_SEPARATOR . 'captchaExtended' . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
		));
		
		$this->currentround	=	Table42Round::model()->getCurrentRound();		
		$my_profile		=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
		if($my_profile){
			if($my_profile->status == Table42Profile::STATUS_APPROVED){
				$this->signup_ok = true;
			}else{
				$this->signup_ok = false;
			}
		}else{
			$this->signup_ok = false;
		}
		//get profile in event		
	}
}