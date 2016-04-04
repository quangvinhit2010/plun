<?php
class RateUser extends EMongoDocument
{
	public $user_id;
	public $username;
	
	public function getMongoDBComponent() {
		return Yii::app()->mongodb_plun;
	}	

	// This has to be defined in every model, this is same as with standard Yii ActiveRecord
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// This method is required!
	public function getCollectionName()
	{
		return 'rate_user';
	}

	public function rules()
	{
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
				'user_id'   => 'UserID',
				'username'   => 'Username',
		);
	}
	
	public function embeddedDocuments()
	{
		return array(
				// property name => embedded document class name
				'eggplant'=>'RateUserEggplant',
				'real'=>'RateUserReal',
				'fake'=>'RateUserFake',
				'queenie'=>'RateUserQueenie',
				'bunny'=>'RateUserBunny',
				'chilli'=>'RateUserChilli',
				'hot_meat'=>'RateUserHotMeat',
				'handsome'=>'RateUserHandsome',
				'kissable_guy'=>'RateUserKissableGuy',
				'for_one_night'=>'RateUserForOneNight',
		);
	}
	/**
	 * @param $user
	 * @return Ambigous <RateUser, the, EMongoDocument, NULL>
	 */
	public function getUser($user){
		$RateUser = RateUser::model()->findByAttributes(array('user_id'=>$user->id));
		if(empty($RateUser)){			
			$RateUser = new RateUser();
			$RateUser->user_id = $user->id;
			$RateUser->username = $user->username;			
			$RateUser->validate();
			if(!$RateUser->hasErrors()){
				$RateUser->save();
			}
		}
		return $RateUser;
	}	
	/**
	 * @param $user_view
	 * @param $user_viewed
	 * @return Ambigous <RateHistory, the, EMongoDocument, NULL>
	 */
	public function rateHim($user_viewed, $rate_type, $time = NULL){
		$time = !empty($time) ? $time : time();
		if(empty($user_viewed) || empty($rate_type)){
			return false;
		}
		$RateHistory = $this->getRateHim($user_viewed, $rate_type);		
		if(!empty($RateHistory) && $RateHistory->lastupdate < $time){
			$RateHistory->lastupdate = time();
			$RateHistory->validate();
			if(!$RateHistory->hasErrors()){
				if($RateHistory->save()){
					$this->countRateHim($user_viewed, $rate_type, $time);
				}
			}
		}	
		
		return $RateHistory;
	}
	/**
	 * @param $user_viewed
	 * @param $rate_type
	 * @param $time
	 */
	public function countRateHim($user_viewed, $rate_type, $time){
		$from = mktime('0','0','0');
		$to = mktime('23','59','59');
		
		$him = $this->findByAttributes(array('user_id'=>$user_viewed->id));
		$type = RateType::model()->getEmbeddedTypes($rate_type);
		
		$lastupdate = $him->$type->lastupdate;
		if(empty($him->$type->lastupdate)){
			$him->$type->lastupdate = $time;
			$him->$type->total = 1;
			$him->save();
		}else{								
			if($from <= $lastupdate && $lastupdate <= $to){								
				$him->$type->total += 1;
				$him->save();
			}else{
				$him->$type->lastupdate = $time;
				$him->$type->total = 1;
				$him->save();
			}
		}
	}
	/**
	 * @param $user_viewed
	 * @param $rate_type
	 * @param $time
	 * @return Ambigous <RateHistory, the, EMongoDocument, NULL>
	 */
	public function getRateHim($user_viewed, $rate_type){
		$RateHistory = RateHistory::model()->findByAttributes(array('view_id'=>$this->user_id, 'viewed_id'=>$user_viewed->id));
		if(empty($RateHistory)){
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
				
			$RateHistory = new RateHistory();
			$RateHistory->view_id = $this->id;
			$RateHistory->view_username = $this->username;
			$RateHistory->viewed_id = $user_viewed->id;
			$RateHistory->viewed_username = $user_viewed->username;
			$RateHistory->rate_type = null;
			$RateHistory->address_ip = $address_ip;
			$RateHistory->device = $device;
			$RateHistory->lastupdate = time();
				
			$RateHistory->validate();
			if(!$RateHistory->hasErrors()){
				$RateHistory->save();
			}
		}
		return $RateHistory;
	}
	
}