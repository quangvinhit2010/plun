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
	public $e_user		=	null;
	
	public function beforeAction($action){
	    $this->option_html['body']['attributes'] = array();
	    if(VHelper::activeMenu(null, 'site', 'index')){
	        $this->option_html['body']['attributes']['class'] = 'bg_homepage';
    	    $this->layout = '//layouts/home';
	    }
	    /*
	     * set attributes for master page
	     */
	    if($action->id == 'login'){
	        $this->option_html['body']['attributes']['class'] = 'bg_signin'; 
	    }
	    if(!Yii::app()->user->isGuest){
	    	$Elasticsearch	=	new Elasticsearch();
	    	$Elasticsearch->updateLastActivity(Yii::app()->user->id, time());
	    	$this->e_user			=	$Elasticsearch->load(Yii::app()->user->id);
	    		
	    	if(empty($this->e_user['total_photo_request'])){
	    		$update_data	=	array(
	    				'friendlist'	=>	'',
	    				'total_photo_request'	=>	DataNodejs::getTotalPhotorequest(Yii::app()->user->id),
	    				'total_addfriend_request'	=>	DataNodejs::getTotalAddFriend(Yii::app()->user->id),
	    				'total_alert'	=>	DataNodejs::getTotalAlert(Yii::app()->user->id),
	    				'total_message'	=>	DataNodejs::getTotalMessage(Yii::app()->user->id),
	    		);
	    		$this->e_user	=	$update_data;
	    		$Elasticsearch->update($update_data, 0, Yii::app()->user->id);
	    	}
	    		
	    	//update friendlist
	    	//is null
	    	if(empty($this->e_user['friendlist'])){
	    		if(!empty($this->e_user) && !empty($this->e_user['current_country_id'])){
	    			$country_id	=	$this->e_user['current_country_id'];
	    		}else{
	    			$model = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
	    			$country_id	=	$model->current_country_id;
	    		}
	    		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
	    		$update_data	=	array(
	    				'friendlist'	=>	implode(',', $my_friendlist)
	    		);
	    		if(!empty($country_id) && is_numeric($country_id)){
	    			$Elasticsearch->update($update_data, $country_id, Yii::app()->user->id);
	    		}
	    	}
	    }
	    return parent::beforeAction($action);
	}
	
	public function checkLogin($redirect = null){
	    $redirect = !empty($redirect) ? $redirect : Yii::app()->homeUrl;
	    if(Yii::app()->user->isGuest){
	        $this->redirect($redirect);
	    }
	}
}