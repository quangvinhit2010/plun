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
	public $usercurrent;
	
	public function beforeAction($action){
	    $this->pageTitle = Yii::app()->name;
	    $this->option_html['divheader']['attributes'] = array();
	    if(VHelper::activeMenu(null, 'site', 'home')){
    	    $this->layout = '//layouts/home';
	    }
// 	    $_assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('pathroot').Yii::app()->theme->baseUrl . '/resources/js/scripts/');
	    /*
        $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
        $arrayUrl = array('site/page/view/about', 'site/page/view/about-gallery', 'site/page/view/about-ourcompany', 'site/page/view/about-what-people-say');
	    $this->option_html['body']['attributes'] = array();
	    $this->option_html['main']['attributes'] = array();
	    $this->option_html['main']['attributes']['class'] = 'main clearfix';
	    $this->option_html['divplun']['attributes']['class'] = 'plun';
	    if(VHelper::activeMenu(null, 'site', null)){
	        $this->option_html['body']['attributes']['class'] = 'hotbox';
	        if(in_array($url, $arrayUrl)){
	            $this->option_html['body']['attributes']['class'] = 'bg_white';
        	    $this->option_html['main']['attributes']['class'] = 'clearfix bg_white';
        	    $this->option_html['divplun']['attributes']['class'] = 'plun plun_about';
	        }
	    }
	    */
	    if(!Yii::app()->user->isGuest){
	    	$this->usercurrent = Yii::app()->user->data();
	    	
		    $Elasticsearch	=	new Elasticsearch();
		    $Elasticsearch->updateLastActivity(Yii::app()->user->id, time());
			$this->e_user			=	$Elasticsearch->load(Yii::app()->user->id);
			
			if(!empty($this->e_user) && empty($this->e_user['total_photo_request'])){
				$update_data	=	array(
						'total_candy_alert'	=>	isset(Yii::app()->user->data()->balance->new_transaction)	?	Yii::app()->user->data()->balance->new_transaction : 0,
						'total_photo_request'	=>	DataNodejs::getTotalPhotorequest(Yii::app()->user->id),
						'total_addfriend_request'	=>	DataNodejs::getTotalAddFriend(Yii::app()->user->id),
						'total_alert'	=>	DataNodejs::getTotalAlert(Yii::app()->user->id),
// 						'total_message'	=>	DataNodejs::getTotalMessage(Yii::app()->user->id),
				);
				$this->e_user	=	array_merge($this->e_user, $update_data);
				$Elasticsearch->update($update_data, 0, Yii::app()->user->id);
			}
			
			//update friendlist
			//is null
			if(!empty($this->e_user) && empty($this->e_user['friendlist'])){
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
				$this->e_user	=	array_merge($this->e_user, $update_data);
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