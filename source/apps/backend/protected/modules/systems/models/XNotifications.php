<?php
class XNotifications extends Notifications
{
	const SYS_POST_WALL 				= "SYS_POST_WALL";
	const SYS_LIKE_WALL 				= "SYS_LIKE_WALL";
	const SYS_COMMENT_WALL 				= "SYS_COMMENT_WALL";
	const SYS_ISU_MATCH_LOCATION		= "SYS_ISU_MATCH_LOCATION";
	const SYS_FRIEND_ACCEPT 			= "SYS_FRIEND_ACCEPT";
	const SYS_SYSTEM_ALERT 				= "SYS_SYSTEM_ALERT";
	const ACTIVE						=	'1';
	const INACTIVE						=	'0';
		
	public static function model($className=__CLASS__)
	{
	    return parent::model($className);
	}
	
	public function relations()
	{
	    // NOTE: you may need to adjust the relation name and the related
	    // class name for the relations automatically generated below.
	    $relations['member'] = array(self::BELONGS_TO, 'Member', 'userid');
	    $relations['owner'] = array(self::BELONGS_TO, 'Member', 'ownerid');
	    return CMap::mergeArray(
	            $relations,
	            parent::relations()
	    );
	}
	
	public function getNotificationsTypes($attributes){
		$notifiType = NotificationsTypes::model()->cache(500)->findByAttributes($attributes);
		if(!empty($notifiType))
			return $notifiType;
		return false;
	}
	
	public function saveNotificationsFromActivity($activity){
		$uid = Yii::app()->user->id;
		$member = new Member();
		if(!empty($activity)){
			$flgSaveNotify = true;
			switch ($activity->action) {
				case Activity::LOG_POST_WALL:
					$notifiType = $this->getNotificationsTypes(array('variables'=>self::SYS_POST_WALL));					
					break;
			}
			if(!empty($notifiType) && $flgSaveNotify==true && $activity->user_id!=$activity->owner_id){
				$params = (array)json_decode($activity->params);
				$message = $activity->message;
				$owner_id = 0;
				if(!empty($activity->owner_id)){
					$owner_id = $activity->owner_id;
				}					
				$notification_data = array(
					'params'=>$params,
					'message'=>$message,
					'activity_id'=>$activity->id
				); 
				$data = array(
					'userid'=>$activity->user_id,
					'ownerid'=>$owner_id,
					'owner_type'=>'user',
					'notification_type'=>$notifiType->id,
					'notification_data'=>addslashes(json_encode($notification_data)),
					'timestamp'=>time(),
					'last_read'=>0,
				);				
				$this->saveNotifications($data);
			}
		}
	}
	
	public function saveNotifications($data){
		if(!empty($data) && is_array($data)){
			$notifi = new XNotifications();
			$notifi->attributes = $data;
			$notifi->validate();
			if(!$notifi->getErrors() && $notifi->userid != $notifi->ownerid){				
				$notifi->save();
			}
		}
	}
	
	public function getNotificationData($type='text') {
		$uid = Yii::app()->user->id;
		$output = new stdClass();
		$member = new Member();
		$criteria=new CDbCriteria;
		$notification_data = (array)json_decode(stripslashes($this->notification_data));		
		if(!empty($notification_data)){
			$params = (array)$notification_data['params'];
			$message = $notification_data['message'];
			$notifiType = $this->getNotificationsTypes(array('id'=>$this->notification_type));
			
			switch ($notifiType->variables) {
				case self::SYS_POST_WALL:
				case self::SYS_LIKE_WALL:	
        			$activity = Activity::model()->findByPk($notification_data['activity_id']);
				    if(!empty($activity->owner)){
    				    $params['{userfrom}'] 			= $this->getWho();
    				    if ($activity->owner->id == Yii::app()->user->id){
    				    	$params['{userto}'] 			= $this->getWhom();
    				    }else{
    				    	$params['{userto}'] 			= $activity->owner->getUserLink(array('class'=>'')).'\'s';;
    				    }
    				    $htmlOption['class'] = 'alertViewStatus';
    				    $likeStt = strtolower(Lang::t('alerts', 'unlike'));
    				    if(!empty($notification_data['activity_id'])){
    				        $htmlOption['rel'] = $this->member->createUrl("//newsFeed/statusDetail", array('actid' => Util::encrypt($notification_data['activity_id'])));
    				        $ob = Like::model()->findByAttributes(array('item_id' => $notification_data['activity_id'], 'type_id' => Like::LIKE_ACTIVITY, 'like_by' => $this->member->id));		
    				        if(!empty($ob->id)){
    				            $likeStt = strtolower(Lang::t('alerts', 'like'));
    				        }		        
    				    }
    				    $params['{like}'] 			    = $likeStt;
    				    $params['{status}'] 			= CHtml::link(strtolower(Lang::t('alerts', 'status')), 'javascript:void(0);', $htmlOption);
				    }
				    break;
				case self::SYS_COMMENT_WALL:	
				    $activity = Activity::model()->findByPk($notification_data['activity_id']);
				    if(!empty($activity->owner)){
    					$params['{userfrom}'] 			= $this->getWho();
    					if ($activity->owner->id == Yii::app()->user->id){
    						$params['{userto}'] 			= $this->getWhom();
    					}else{
    						$params['{userto}'] 			= $activity->owner->getUserLink(array('class'=>'')).'\'s';;
    					}
    					$htmlOption['class'] = 'alertViewStatus';
    					if(!empty($notification_data['activity_id'])){
    					    $htmlOption['rel'] = $this->member->createUrl("//newsFeed/statusDetail", array('actid' => Util::encrypt($notification_data['activity_id'])));
    					}
    					$params['{status}'] 			= CHtml::link(strtolower(Lang::t('alerts', 'status')), 'javascript:void(0);', $htmlOption);
				    }
					break;
				case self::SYS_ISU_MATCH_LOCATION:
					if(isset($params['{number}'])){
						$isu_url = Yii::app()->createUrl('//isu');
						$params['{number}'] = CHtml::link($params['{number}'], $isu_url, array('target' => '_blank'));
						
					} else {
						$new_isu_url = Yii::app()->createUrl('//isu/load', array('id' => $params['{isu_id}']));
						$params['{isu_id}'] = CHtml::link(Lang::t('alerts', 'new ISU'), $new_isu_url, array('target' => '_blank'));
					}
					$params['{username}'] = $this->getWho();
					$params['{location}'] = '<b>'.$params['{location}'].'</b>';
					
					
					
					break;
				case self::SYS_FRIEND_ACCEPT:
				    $params['{username}'] = $this->getWho();
					break;
				case self::SYS_SYSTEM_ALERT:
					$params['{username}'] = Yii::app()->user->_data->username;
					$params['{domain}'] = $_SERVER['SERVER_NAME'];
					$message = (array) $message;
					$message = ($message[Yii::app()->language]) ? $message[Yii::app()->language] : $message[VLang::LANG_EN];
					break;
				default:
					break;
			}
			$message = Lang::t('alerts', $message, $params);
			if(!empty($activity->params)){
			    $activity_params = (array)json_decode($activity->params);
			    if(!empty($activity_params['{message}'])) {
			    	$alert_message = Util::partString($activity_params['{message}'], 0, 90);
			    	$message .= ': “'.htmlspecialchars($alert_message).'”';
			    }
			}			
			$output->message = $message;
			return $output;
		}
		return false;
	}
	
	public function getWho() {
	    if ($this->member->id == Yii::app()->user->id){
	        $msg = ucfirst(Lang::t('newsfeed', 'You'));
	    }else{
	        $msg = $this->member->getUserLink(array('class'=>'user'));
	    }
	    return $msg;
	}
	
	public function getWhom() {
	    if ($this->owner->id == Yii::app()->user->id){
	        $msg = strtolower(Lang::t('newsfeed', 'Your'));
	    }else{
	        $msg = $this->owner->getUserLink(array('class'=>'user')).'\'s';
	    }
	    return $msg;
	}
	
	public function read(){
	    if(!empty($this->id) && $this->last_read == 0){
	        $this->last_read = 1;
	        return $this->save();
	    }
	}
	public function hideNotifications($type, $object_id, $status = XNotifications::INACTIVE){
		$this->updateAll(array('status' => $status), "(notification_type = '{$type}') AND (object_id = '{$object_id}')");
	}
	public function updateNotification($limit =  5000){
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("object_id = 0 AND notification_data LIKE '%activity_id%'");
		$criteria->limit = $limit;
		$data	=	$this->findAll($criteria);

		foreach($data AS $row){
			$notification_data	=	json_decode(stripcslashes ($row->notification_data), true);

			if(isset($notification_data['activity_id'])){
				$row->object_id	=	$notification_data['activity_id'];
				$row->save();
			}
			
		}
	}
}