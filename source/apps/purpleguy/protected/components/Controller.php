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
	public $option_html = array();
	public $usercurrent;
	
	public function beforeAction($action){
	    
	    $this->pageTitle = Yii::app()->name;
	    /** body element **/
	    $this->option_html['body']['attributes'] = array();
	    /** div main element **/
	    $this->option_html['container']['attributes'] = array();
	    $this->option_html['container']['attributes']['class'] = 'plun';
	    
	    $this->option_html['main']['attributes'] = array();
	    $this->option_html['main']['attributes']['class'] = 'main clearfix';
	    
	    if(VHelper::activeMenu(null, 'site', 'index')){
	        $this->option_html['container']['attributes']['class'] = 'plun hotbox';
	        $this->option_html['main']['attributes']['class'] = 'home clearfix bg_white';
    	    $this->layout = '//layouts/home';
	    }
	    /*
	     * set attributes for master page
	    */
        $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
        $arrayUrl = array('site/page/view/about', 'site/page/view/about-gallery', 'site/page/view/about-ourcompany', 'site/page/view/about-what-people-say');
	    if(VHelper::activeMenu(null, 'site', null)){
	        if(in_array($url, $arrayUrl)){
	        }
	    }
	    
	    if(!Yii::app()->user->isGuest){
	        $this->usercurrent = Yii::app()->user->data();
	    }
	    return parent::beforeAction($action);
	}
	
	public function isLogin(){
	    if(Yii::app()->user->isGuest){
	        $session = Util::getSession();
	        $session->add ( 'must_login', true);
	        $this->redirect('/');
	    }
	}
	
}