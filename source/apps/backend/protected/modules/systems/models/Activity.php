<?php

class Activity extends ActivityLog {
    const LOG_POST_WALL					= 1;
    const LOG_PHOTO_UPLOAD				= 2;
    const LOG_CHECK_IN					= 3;
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function log($action, $params = '', $owner_id, $owner_name, $object_id = 0, $time = 0, $group_id = 0) {
		if(Yii::app()->user->isGuest)
			return false;
		$log = new Activity();
		
		$log->user_id 			= intval(Yii::app()->user->id);
		$log->user_name 		= Yii::app()->user->data()->username;
		$log->action 			= $action;
		$log->message 			= $this->setMessage($action);
		$log->params 			= json_encode($params);
		$log->ip_address 		= Yii::app()->request->userHostAddress;
		$log->timestamp 		= $time == 0 ? time() : $time;
		$log->date_last_update 	= time();
		$log->object_id 		= $object_id;
		$log->owner_id			= $owner_id;
		$log->owner_name		= $owner_name;
		$log->group_id			= $group_id;
		
		$log->save();
		$notifi = new XNotifications();
		$notifi->saveNotificationsFromActivity($log);
		return $log;
	}
	
	public function setMessage($action) {
		$message = "";
		switch ($action) {
			case self::LOG_POST_WALL:
				$message = Yum::t('{userfrom} said to {userto} : "{message}"');
				break;
			case self::LOG_PHOTO_UPLOAD:
				$message = Yum::t('{user} added {photo} to {typePhoto}');
				break;
			case self::LOG_CHECK_IN:
				$message = Yum::t('{userfrom} Checked-in at {venue}');
				break;				
			default: 			
				$message = "";
		}
		return $message;
	}
	
	public function getParams() {
		$params = (array)json_decode($this->params);
		switch ($this->action) {
			case self::LOG_ALBUM_COMMENT:
			case self::LOG_ALBUM_LIKE:
				$album = Album::model()->findByPk($params['{album_id}']);
				if ($album){
					$photo = $album->getThumb(false);
					if ($photo) $params['thumbnail'] = $photo->getThumb();
				}
				break;
		}
		return $params;
	}
	
	public function getMessage() {
		$params = (array)json_decode($this->params);
		$message = $this->message;
		$ob = Like::model()->countByAttributes(array('item_id' => $this->id, 'like_by' => Yii::app()->user->id, 'type_id' => Like::LIKE_ACTIVITY));
		$this->is_like = false;
		if ($ob){
		    $this->is_like = true;
		}
		switch ($this->action) {
			case self::LOG_POST_WALL:
				$message = $params['{message}'];
				break;
			case self::LOG_PHOTO_UPLOAD:
			    $count_photos = $this->model()->countByAttributes(array('group_id'=>$this->id, 'status'=> 1));
			    $lblPhoto = Lang::t('newsfeed', 'a new public photo');
			    if(!empty($count_photos) && $count_photos >= 1){			   
			        $lblPhoto = Lang::t('newsfeed', 'new public photos');
			    }
			    $message = Lang::t('newsfeed', $this->message, array('{user}'=> '', '{photo}'=>$lblPhoto));
				break;
				case self::LOG_CHECK_IN:
				$venue = $params['{venue}'];
				$message = Lang::t('newsfeed', $this->message, array('{userfrom}'=> '', '{venue}' => $venue));
				break;				 
		}
		return $message;
	}
	
	
	public function getObject($limit = 0) {
		$objects = array();
		switch ($this->action) {			
			case self::LOG_PHOTO_UPLOAD:
			    $indx = 0;
			    $criteria = new CDbCriteria();
			    $criteria->addCondition("status = 1");
// 			    $criteria->limit = 2;
				$activities = $this->model()->findAllByAttributes(array('group_id'=>$this->id), $criteria);
				$photo1 = Photo::model()->findByAttributes(array('id'=>$this->object_id, 'status'=>1));
				if(!empty($photo1)){
				    $indx++;
					$objects[] = $photo1;
				}
				if(!empty($activities)){
					foreach ($activities as $activity){
						$photo2 = Photo::model()->findByAttributes(array('id'=>$activity->object_id, 'status'=>1));
						if(!empty($photo2)){
						    if($indx >= $limit){
						        break;
						    }else{
						        $indx++;
						    }
							$objects[] = $photo2;
						} 
					}
				}
				break;
		}
		return $objects;
	}
	
	public function getHeader(){
		$header = "";
		$header .= $this->getWho();
		$params = json_decode($this->params, true);
		switch ($this->action) {
			case self::LOG_POST_WALL:
				break;
			case self::LOG_PHOTO_UPLOAD:
				break;
		}
		$header .= "";
		return $header;
	}
	
	public function getWho() {
	    if ($this->member->id == Yii::app()->user->id){
	        $msg = CHtml::link(Lang::t('newsfeed', 'You'), $this->member->getUserUrl());
	    }else{
	        $msg = $this->member->getUserLink();
	    }
	    return $msg;
	}
	
	public function getWhom() {
	    if ($this->owner->id == Yii::app()->user->id){
	        $msg = strtolower(Lang::t('newsfeed', 'Your'));
	    }else{
	        $msg = $this->owner->getUserLink().'\'s';
	    }
	    return $msg;
	}
	public function deleteCheckinNewsFeed($venue_id){
		$criteria = new CDbCriteria();
		$criteria->addCondition("action = " . Activity::LOG_CHECK_IN);
		$criteria->addCondition("object_id = " . $venue_id);
		return $this->deleteAll($criteria);
	}
}