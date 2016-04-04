<?php
class VstUserViewUser extends EMongoDocument
{
	public $view_id;
	public $view_username;
	public $viewed_id;
	public $viewed_username;
	public $timestamp;
	public $address_ip;
	public $device;
	
	public function getMongoDBComponent() {
		return Yii::app()->mongodb_plun;
	}	

// 	This has to be defined in every model, this is same as with standard Yii ActiveRecord
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

// 	This method is required!
	public function getCollectionName()
	{
		return 'vst_user_view_user';
	}

	public function rules()
	{
		return array(
				array('view_id, view_username, viewed_id, viewed_username', 'required'),
				array('view_id, viewed_id, timestamp', 'numerical'),
		);
	}

	public function attributeLabels()
	{
		return array(
				'view_id'   => 'User',
				'view_username'   => 'Username',
		);
	}
	
	public function saveUserViewUser($user_view, $user_viewed, $address_ip = null, $device = null){
		if(empty($user_viewed) || empty($user_view)){
			return false;
		}
		$userView = VstUser::model()->getUser($user_view);
		$userViewed = VstUser::model()->getUser($user_viewed);
		if($userView->isLimitRightToViewProfile()){
			return false;
		}
		
		$detect = Yii::app()->mobileDetect;
		$_device = 'Desktop';
		if($detect->isMobile()){
			$_device = 'Mobile';
		}
		if($detect->isTablet()){
			$_device = 'Tablet';
		}
		$address_ip = !empty($address_ip) ? $address_ip : Yii::app()->request->getUserHostAddress();
		$device = !empty($device) ? $device : $_device;
		
		$from = mktime('0','0','0');
		$to = mktime('23','59','59');
			
		$c = new EMongoCriteria;
		$c->view_id('==', $user_view->id);
		$c->viewed_id('==', $user_viewed->id);
		$vstUserViewUser = VstUserViewUser::model()->find($c);		
		
		if(empty($vstUserViewUser)){
			$vstUserViewUser = new VstUserViewUser();
			$vstUserViewUser->view_id = $user_view->id;
			$vstUserViewUser->viewed_id = $user_viewed->id;		
			/* view +1 if profile not see time */
			$userView->view->total += 1;
			$userViewed->viewed->total += 1;
		}
		/**
		 * (1): Profile is not view
		 */
		$c->timestamp('>=', $from);
		$c->timestamp('<=', $to);
		$userViewUserInToDay = VstUserViewUser::model()->find($c);
		if(empty($userViewUserInToDay)){
			$userView->viewProfile();
			$vstUserViewUser->timestamp = time();		
		}
		/**
		 * end (1)
		 */
		$vstUserViewUser->view_username = $user_view->username;		
		$vstUserViewUser->viewed_username = $user_viewed->username;
		$vstUserViewUser->address_ip = $address_ip;
		$vstUserViewUser->device = $device;
		$vstUserViewUser->validate();
		if(!$vstUserViewUser->hasErrors()){
			$vstUserViewUser->save();
			$userView->save();
			$userViewed->save();
			return $vstUserViewUser;
		}		
	}	

}