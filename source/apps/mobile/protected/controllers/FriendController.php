<?php

/**
 * @author vinhnguyen
 * @desc Friends Controller
 */
class FriendController extends MemberController {

    public $_friend;
    
    /**
     * friends list
     */
    public function actionIndex($type = null){
    	if(!$this->user->isMe()){
    		throw new CHttpException ( 404, 'The requested page does not exist.' );
    	}
    	
    	$user	=	Yii::app()->user->data();
    	$limit_friendlist	=	Yii::app()->params->myfriend_list['limit_display'];
    	$myfriend_list = $user->getFriendlist($limit_friendlist);
    	$show_more_myfriendlist = ($myfriend_list['total_friends'] > $limit_friendlist) ? true : false;
    	
    	if(Yii::app()->request->isAjaxRequest){
    		$offset = $_POST['offset'];
    		$myfriend_list = $user->getFriendlist($limit_friendlist, $offset);
    		$is_show_more = ($myfriend_list['total_friends'] > ($offset + sizeof($myfriend_list['dbrow']))) ? true : false;
    		//$is_show_more = ($myfriend_list['total_friends'] > (count($myfriend_list['dbrow']) + $limit_friendlist) ? true : false);
    		if(count($myfriend_list['dbrow']) > 0){
    			$this->renderPartial('partial/my_friends_ajax',array(
    					'myfriend_list' => $myfriend_list,
    					'is_show_more' => $is_show_more
    			));
    			Yii::app()->end();
    		}
    	} else {
	    	$this->render('page/my_friends', array(
	    			'show_more_myfriendlist' => $show_more_myfriendlist,
	    			'myfriend_list' => $myfriend_list, 
	    			'limit_friendlist'	=>	$limit_friendlist,
	    	));
    	}
    }


    /**
     * @param string $alias_name
     * @return boolean
     */
    public function actionInvite($alias = null) {
        if (isset($_POST['alias']))
            $alias = $_POST['alias'];
        $invited = Member::model()->find("alias_name = '{$alias}'");

        if ($alias == null)
            return false;
        $user_id = $invited->id;

        if (isset($_POST['message']) && isset($user_id)) {
            $friendship = new YumFriendship;
            //Yii::app()->xmpp->addRoster($invited->username);
            if ($friendship->requestFriendship(Yii::app()->user->id, $user_id, $_POST['message'])) {
                $this->redirect($invited->createUrl('//friend/index'));
            }
        }

        $this->render('page/invitation', array(
            'inviter' => Member::model()->findByPk(Yii::app()->user->id),
            'invited' => $invited,
            'friendship' => isset($friendship) ? $friendship : null,
        ));
    }

    public function actionFind() {
        $cri = new CDbCriteria();
        $cri->limit = 100;
        $friends = Member::model()->findAll($cri);
        $this->renderPartial('page/find', array('friends' => $friends));
    }

    public function actionaddfriend() {
    	//Yii::app()->xmpp->confirmRoster($this->user->username);
        $inviter_id = $_POST['inviter_id'];
        $this->usercurrent->changeStatusFriendShip($inviter_id, YumFriendship::FRIENDSHIP_ACCEPTED);
       
        exit;
    }

    public function actiondeclinefriend() {
        $inviter_id = $_POST['inviter_id'];
        $this->usercurrent->changeStatusFriendShip($inviter_id, YumFriendship::FRIENDSHIP_REJECTED);
        exit;
    }

    public function actionshowmore_request() {
        $limit = Yii::app()->params->waiting_request_addfriend['limit_display'];
        $offset = $_POST['offset'];
        $waiting_request_addfriends =
                $this->usercurrent->getFriendships(YumFriendship::FRIENDSHIP_REQUEST, $limit, $offset);

        $show_more_request_addfriends = ($waiting_request_addfriends['total_request'] > ($offset + sizeof($waiting_request_addfriends['dbrow']))) ? true : false;
        $html	=	$this->renderPartial('partial/showmore_addfriendrequest', 
        	array('offset' => $limit, 
        	'waiting_request_addfriends' => $waiting_request_addfriends), true
        );
        
        $array_result_json	=	array(
        	'html'	=>	$html,
        	'show_more_request_addfriends'	=>	$show_more_request_addfriends
        );
        echo json_encode($array_result_json);
        exit;    
    }

    public function actionshowmore_friendlist() {
        $limit = Yii::app()->params->myfriend_list['limit_display'];
        $offset = $_POST['offset'];
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $state_in_cache	=	new StateonCache();
		$user	=	Yii::app()->user->data();
		
        $myfriend_list = $user->getFriendlist($limit, $offset);
      
		
        $show_more_myfriendlist = ($myfriend_list['total_friends'] > ($offset + sizeof($myfriend_list['dbrow']))) ? true : false;

        $html	=	$this->renderPartial('partial/showmore_friendlist', array('limit' => $limit, 
            'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel(),
            'myfriend_list' => $myfriend_list,
            'city_info' => $city_in_cache->getListCity(), 
        	'state_info' => $state_in_cache->getListState(),
            'country_info' => $country_in_cache->getListCountry()
                ), true);
        $array_result_json	=	array(
        	'html'	=>	$html,
        	'show_more_myfriendlist'	=>	$show_more_myfriendlist
        );
        echo json_encode($array_result_json);
        exit;
    }

    public function actionFriendShipAdd() {
    	
    	//Yii::app()->xmpp->addRoster($this->user->username);
    	$user	=	Yii::app()->user->data();

        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_REQUEST,
            'inviter_id'=>  $user->id,
            'friend_id' =>  $post['friend_id'],
        	'requesttime'	=>	time()
        );        
        if($user->status){
	        if($model_friendship->getFriendShipStatus($user->id, $post['friend_id']) !== false){
	            $model_friendship->updateAll($data_update, "(inviter_id={$user->id} AND friend_id={$post['friend_id']}) OR (inviter_id={$post['friend_id']} AND friend_id={$user->id})");
	        }else{
	            $model_friendship->status       =   YumFriendship::FRIENDSHIP_REQUEST;
	            $model_friendship->inviter_id   =   $user->id;
	            $model_friendship->friend_id    =   $post['friend_id'];
	            $model_friendship->requesttime    =   time();
	            $model_friendship->acknowledgetime  =   0;
	            $model_friendship->updatetime  =   0;
	            $model_friendship->message      =   '';
	
	            $model_friendship->save();
	        }
	        echo '1';
        }else{
        	echo '0';
        }
        exit;
    }
    public function actionCancelRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_NONE
        );
        YumFriendship::model()->updateAll($data_update, "inviter_id={$this->usercurrent->id} AND friend_id={$post['friend_id']}");
    }
    public function actionAgreeRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_ACCEPTED
        );
        YumFriendship::model()->updateAll($data_update, "inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}");        
    }
    public function actionDeclineRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_REJECTED
        );
        //Yii::app()->xmpp->removeRoster($this->user->username);
        YumFriendship::model()->updateAll($data_update, "inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}");        
    }
    public function actionunFriendRequest(){
        //Yii::app()->xmpp->removeRoster($this->user->username);
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_NONE
        );
        
        YumFriendship::model()->updateAll($data_update, "(inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}) OR (inviter_id={$this->usercurrent->id} AND friend_id={$post['friend_id']})");        
    }    
    
    public function actionRequest(){
    	
    	if(!$this->user->isMe()){
    		throw new CHttpException ( 404, 'The requested page does not exist.' );
    	}
    	
    	$limit_request = Yii::app()->params->waiting_request_addfriend['limit_display'];
    	$waiting_request_addfriends = $this->usercurrent->getFriendships(YumFriendship::FRIENDSHIP_REQUEST, $limit_request);
    	$show_more_request_addfriend = ($waiting_request_addfriends['total_request'] > $limit_request) ? true : false;

    	
    	if(Yii::app()->request->isAjaxRequest){
    		$offset = $_POST['offset'];
    		$waiting_request_addfriends = $this->usercurrent->getFriendships(YumFriendship::FRIENDSHIP_REQUEST, $limit_request, $offset);
    		$is_show_more = ($waiting_request_addfriends['total_request'] > ($offset + sizeof($waiting_request_addfriends['dbrow']))) ? true : false;
    		
    		if(count($waiting_request_addfriends['dbrow']) > 0){
    			$this->renderPartial('partial/my_friends_request_ajax',array(
    					'waiting_request_addfriends' => $waiting_request_addfriends,
    					'limit_request'	=>	$limit_request,
    					'is_show_more' => $is_show_more,
    			));
    			Yii::app()->end();
    		}
    	} else {
    		$this->render('page/my_friends_request', array(
    				'waiting_request_addfriends' => $waiting_request_addfriends,
    				'limit_request'	=>	$limit_request,
    				'show_more_request_addfriend'	=>	$show_more_request_addfriend,
    		));
    	}
    	
    }
    
    public function beforeAction($action) {
    	if( parent::beforeAction($action) ) {
    		$cs = Yii::app()->clientScript;
    		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/timeago.js');
    		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/friendship.js');
    		return true;
    	}
    	return false;
    }
}

