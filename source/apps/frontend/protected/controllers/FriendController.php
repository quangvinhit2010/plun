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
    public function actionIndex() {

        if(!$this->user->isMe()){
            throw new CHttpException ( 404, 'The requested page does not exist.' );
        }
        $state_in_cache	=	new StateonCache();
        $district_in_cache = new DistrictonCache();
        $country_in_cache   =   new CountryonCache();
		$user	=	Yii::app()->user->data();
		
        $popup_limit_display = Yii::app()->params->waiting_request_addfriend['popup_limit_display'];
		$limit_friendlist	=	Yii::app()->params->myfriend_list['limit_display'];
        
        if (isset($_POST['YumFriendship']['inviter_id']) && isset($_POST['YumFriendship']['friend_id'])) {
            $friendship = YumFriendship::model()->find(
                    'inviter_id = :inviter_id and friend_id = :friend_id', array(
                ':inviter_id' => $_POST['YumFriendship']['inviter_id'],
                ':friend_id' => $_POST['YumFriendship']['friend_id']));

            if (isset($friendship))
                if ($friendship->inviter_id == Yii::app()->user->id || $friendship->friend_id == Yii::app()->user->id)
                    if (isset($_POST['YumFriendship']['add_request'])) {
                        $friendship->acceptFriendship();
                    } elseif (isset($_POST['YumFriendship']['deny_request'])) {
                        $friendship->status = 3;
                        $friendship->save();
                    } elseif (isset($_POST['YumFriendship']['ignore_request'])) {
                        $friendship->status = 0;
                        $friendship->save();
                    } elseif (isset($_POST['YumFriendship']['cancel_request']) || isset($_POST['YumFriendship']['remove_friend'])) {
                        $friendship->delete();
                    }
            $this->redirect($this->usercurrent->getUserUrl());
        }
        $waiting_request_addfriends = $user->getFriendships(YumFriendship::FRIENDSHIP_REQUEST, $popup_limit_display);
        
        $show_more_request_addfriend = ($waiting_request_addfriends['total_request'] > $popup_limit_display) ? true : false;
        $myfriend_list = $user->getFriendlist($limit_friendlist);
        $show_more_myfriendlist = ($myfriend_list['total_friends'] > $limit_friendlist) ? true : false;

        $my_friendlist_ids	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        $elasticsearch	=	new Elasticsearch();
        $online_data	=	$elasticsearch->checkOnlineStatus($my_friendlist_ids);
        
        $this->render('partial/myfriends', array(
        	'online_data'	=>	$online_data,
        	'popup_limit_display'	=>	$popup_limit_display,
        	'show_more_myfriendlist' => $show_more_myfriendlist, 
        	'myfriend_list' => $myfriend_list,
        	'limit_friendlist'	=>	$limit_friendlist,
            'show_more' => $show_more_request_addfriend, 
            'waiting_request_addfriends' => $waiting_request_addfriends,
            'country_info' => $country_in_cache->getListCountry(),
            'state_info'	=>	$state_in_cache->getListState(),
            'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel()
            
        ));
    }
    /**
     */
    public function actionRequest() {
    	$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	$my_friendlist_ids	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
    	
    	$offset = Yii::app()->request->getParam('offset', 0);
    	$limit_request = Yii::app()->params->waiting_request_addfriend['limit_display'];
    	    	
    	$request_limit_display = Yii::app()->params->waiting_request_addfriend['limit_display'];
    	$user	=	Yii::app()->user->data();
    	$waiting_request_addfriends = $user->getFriendships(YumFriendship::FRIENDSHIP_REQUEST, $request_limit_display);
    	
    	$show_more_request_addfriend = ($waiting_request_addfriends['total_request'] > $limit_request) ? true : false;
    	
  
    	$elasticsearch	=	new Elasticsearch();
    	$online_data	=	$elasticsearch->checkOnlineStatus($waiting_request_addfriends['friend_id']);
    	
    	$this->render('partial/request', array(
    		'online_data'	=>	$online_data,
    		'show_more' => $show_more_request_addfriend,
    		'limit_request'	=>	$limit_request,
    		'waiting_request_addfriends'	=>	$waiting_request_addfriends
    	));    	
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
        $notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_FRIEND_ACCEPT));
        if(!empty($notifiType)){
            $notification_data = array(
                    'params' => array('{username}'=>$this->usercurrent->username),
                    'message' => Yum::t('{username} has accepted your friend request'),
            );
            
            $notiExist = XNotifications::model()->findByAttributes(array(
                    'userid'=>$this->usercurrent->id,
                    'ownerid'=>$inviter_id,
                    'owner_type'=>'user',
                    'notification_type'=>$notifiType->id,
                    'notification_data'=>addslashes(json_encode($notification_data)),
            ));
            if(empty($notiExist)){
                $data = array(
                        'userid'=>$this->usercurrent->id,
                        'ownerid'=>$inviter_id,
                        'owner_type'=>'user',
                        'notification_type'=>$notifiType->id,
                        'notification_data'=>addslashes(json_encode($notification_data)),
                        'timestamp'=>time(),
                        'last_read'=>0,
                );
                XNotifications::model()->saveNotifications($data);
            }else{
                $notiExist->timestamp = time();
                $notiExist->last_read = 0;
                $notiExist->save();
            }
        }
        DataNodejs::updateTotalAddFriend(Yii::app()->user->id);
        DataNodejs::updateTotalAlert($inviter_id);
        DataNodejs::updateFriendlist(Yii::app()->user->id);
        DataNodejs::updateFriendlist($inviter_id);
        exit;
    }

    public function actiondeclinefriend() {
        $inviter_id = $_POST['inviter_id'];
        $this->usercurrent->changeStatusFriendShip($inviter_id, YumFriendship::FRIENDSHIP_REJECTED);
        DataNodejs::updateTotalAddFriend(Yii::app()->user->id);
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
        
        $elasticsearch	=	new Elasticsearch();
        $online_data	=	$elasticsearch->checkOnlineStatus($waiting_request_addfriends['friend_id']);
        
        $array_result_json	=	array(
        	'html'	=>	$html,
        	'online_data'	=>	$online_data,
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

        $my_friendlist_ids	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        $elasticsearch	=	new Elasticsearch();
        $online_data	=	$elasticsearch->checkOnlineStatus($my_friendlist_ids);        
        
        $html	=	$this->renderPartial('partial/showmore_friendlist', array('limit' => $limit, 
            'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel(),
        	'online_data'	=>	$online_data,
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
        DataNodejs::updateTotalAddFriend($post['friend_id']);
        DataNodejs::updateFriendlist(Yii::app()->user->id);
        DataNodejs::updateFriendlist($post['friend_id']);
        exit;
    }
    public function actionCancelRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_NONE
        );
        YumFriendship::model()->updateAll($data_update, "inviter_id={$this->usercurrent->id} AND friend_id={$post['friend_id']}");
        DataNodejs::updateTotalAddFriend($post['friend_id']);
    }
    public function actionAgreeRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_ACCEPTED
        );
        YumFriendship::model()->updateAll($data_update, "inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}");   

        
        $notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_FRIEND_ACCEPT));
        if(!empty($notifiType)){
            $notification_data = array(
                    'params' => array('{username}'=>$this->usercurrent->username),
                    'message' => Yum::t('{username} has accepted your friend request'),
            );
        
            $notiExist = XNotifications::model()->findByAttributes(array(
                    'userid'=>$this->usercurrent->id,
                    'ownerid'=>$post['friend_id'],
                    'owner_type'=>'user',
                    'notification_type'=>$notifiType->id,
                    'notification_data'=>addslashes(json_encode($notification_data)),
            ));
            if(empty($notiExist)){
                $data = array(
                        'userid'=>$this->usercurrent->id,
                        'ownerid'=>$post['friend_id'],
                        'owner_type'=>'user',
                        'notification_type'=>$notifiType->id,
                        'notification_data'=>addslashes(json_encode($notification_data)),
                        'timestamp'=>time(),
                        'last_read'=>0,
                );
                XNotifications::model()->saveNotifications($data);
            }else{
                $notiExist->timestamp = time();
                $notiExist->last_read = 0;
                $notiExist->save();
            }
        }  

        DataNodejs::updateTotalAddFriend(Yii::app()->user->id);
        DataNodejs::updateTotalAlert($this->usercurrent->id);
        DataNodejs::updateFriendlist(Yii::app()->user->id);
        DataNodejs::updateFriendlist($this->usercurrent->id);
    }
    public function actionDeclineRequest(){
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_REJECTED
        );
        //Yii::app()->xmpp->removeRoster($this->user->username);
        YumFriendship::model()->updateAll($data_update, "inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}");        
        DataNodejs::updateTotalAddFriend(Yii::app()->user->id);
    }
    public function actionunFriendRequest(){
        //Yii::app()->xmpp->removeRoster($this->user->username);
        $model_friendship = new YumFriendship();
        
        $post = Yii::app()->request->getPost('YumFriendship');
        $data_update   =   array(
            'status'    =>  YumFriendship::FRIENDSHIP_NONE
        );
        
        YumFriendship::model()->updateAll($data_update, "(inviter_id={$post['friend_id']} AND friend_id={$this->usercurrent->id}) OR (inviter_id={$this->usercurrent->id} AND friend_id={$post['friend_id']})");     
        DataNodejs::updateFriendlist(Yii::app()->user->id);
        DataNodejs::updateFriendlist($this->usercurrent->id);
        
    }    
}

