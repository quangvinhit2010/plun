<?php
/**
 * @author vinhnguyen
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MemberController extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
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
	public $title ='';	
	public $user;	
	public $alias_name;
	public function init()
	{
		if(Yii::app()->user->isGuest){
		    Yii::app()->user->setFlash('msgLogin', Lang::t('login', 'Please sign-in to use this feature!'));
		    $this->redirect(Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl(Yii::app()->request->requestUri))));
			throw new CHttpException(403, 'You must login !');
		}		
		$params = $this->getActionParams();
		$flag = false;
		if(isset($params['alias'])){
			$this->alias_name = $params['alias'];
			$member = Member::model()->findByAttributes(array('alias_name' => $this->alias_name));
			if(isset($member)) {
				$flag = true;
				$this->user = $member;
			}else {
			    throw new CHttpException(403, 'The page you requested was not found.');
			}
		}
		if(!$this->isLogged()){
			if(Yii::app()->request->isAjaxRequest){
				echo Yii::t(null, 'Lost Session!');
				Yii::app()->end();
			}else{
				$this->redirect(Yii::app()->createUrl('//site/login'));
			}
		}		
// 		if(!Yii::app()->request->isAjaxRequest && !$flag){
// 			throw new CHttpException(403, 'The page you requested was not found.');
// 		}
		
	}
	
	public function beforeAction($action){
		if(!empty($action->controller->id)){
		    switch (strtolower($action->controller->id)) {
		        case 'my':
		        case 'photo':
		        case 'messages':
		        case 'bookmark':
		        case 'alerts':
		        case 'friend':
		        case 'settings':
		        case 'newsfeed':
		            if(!Yii::app()->request->isAjaxRequest && !empty($this->user)){
        		        $this->pageTitle = $this->user->getDisplayName()." | ".Yii::app()->name;
		            }
		            break;
		        default:
		            $this->pageTitle = Yii::app()->name;
		            break;
		    }
		}
		$this->layout = '//layouts/user-page';
		return parent::beforeAction($action);
	}
	
	public function isLogged(){
		if(!Yii::app()->user->isGuest)
			return true;
		return false;
	}
}