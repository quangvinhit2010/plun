<?php
class VstUser extends EMongoDocument
{
	public $user_id;
	public $username;
	const NOT_LIMIT_VIEWED = -2;
	
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
		return 'vst_user';
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
				'view'=>'VstUserView',
				'viewed'=>'VstUserViewed',
		);
	}
	
	public function getUser($user){
		$VstUser = VstUser::model()->findByAttributes(array('user_id'=>$user->id));
		if(empty($VstUser)){			
			$VstUser = new VstUser();
			$VstUser->user_id = $user->id;
			$VstUser->username = $user->username;		
			$VstUser->viewed->total = 0;			
			$VstUser->viewed->expire = 0;
			$VstUser->viewed->limit = (int)Yii::app()->config->get('vst_userviewed_in_day');
			
			$VstUser->view->total = 0;
			$VstUser->view->limit = (int)Yii::app()->config->get('view_profile_in_day');
			$VstUser->view->limit_pay = 0;
			$VstUser->view->expire = mktime('23','59','59');
			$VstUser->validate();
			if(!$VstUser->hasErrors()){
				$VstUser->save();
			}
		}
		return $VstUser;
	}
	/**
	 * get limit right to view visitor
	 */
	public function getLimitRightToViewVisitor(){
		if(!empty($this->viewed->expire)){
			if($this->viewed->expire <= time() && $this->viewed->limit == self::NOT_LIMIT_VIEWED){
				$this->viewed->limit = (int)Yii::app()->config->get('vst_userviewed_in_day');
				$this->viewed->expire = 0;
				$this->save();
			}
		}
		return $this->viewed->limit;
	}
	/**
	 * check limit right to view visitor	 
	 */
	public function isLimitRightToViewVisitor(){
		$limit = $this->getLimitRightToViewVisitor();
		if($limit != self::NOT_LIMIT_VIEWED){
			return true;
		}
		return false;
	}
	/**
	 * set limit right to view visitor
	 */
	public function setLimitRightToViewVisitor($days){		
		if(!empty($this->viewed->limit)){
			$this->viewed->limit = self::NOT_LIMIT_VIEWED;
			$this->viewed->expire = strtotime("+$days day");
			$this->save();
			return $this;
		}
	}
	/**
	 * get limit right to view profile
	 */
	public function getLimitRightToViewProfile(){
		if(!empty($this->view->expire)){
			if($this->view->expire <= time()){
				$this->view->expire = mktime('23','59','59');
				$this->view->limit = (int)Yii::app()->config->get('view_profile_in_day');
				$this->save();
			}
		}else{
			$this->view->expire = mktime('23','59','59');
			$this->save();
		}
		return ($this->view->limit + $this->view->limit_pay) ;
	}
	/**
	 * check limit right to view profile
	 */
	public function isLimitRightToViewProfile(){
		$limitViewProfile = $this->getLimitRightToViewProfile();
		if(empty($limitViewProfile) || $limitViewProfile <= 0){
			return true;
		}
		return false;
	}
	/**
	 * view profile
	 */
	public function viewProfile($save = false){
		if($this->view->limit > 0){
			$this->view->limit -= 1;
		}elseif($this->view->limit == 0 && $this->view->limit_pay > 0){
			$this->view->limit_pay -= 1;
		}
		if($save){
			$this->save();
		}
	}
	/**
	 * pay limit right to view profile
	 */
	public function payLimitViewProfile($limit_pay){
		$this->view->limit_pay += $limit_pay;		
		$this->save();
	}
	/**
	 * check visitor view the profile not yet
	 */
	public function isViewProfile($user_viewed){
		$from = mktime('0','0','0');
		$to = mktime('23','59','59');
			
		$c = new EMongoCriteria;
		$c->view_id('==', $this->user_id);
		$c->viewed_id('==', $user_viewed->id);
		$c->timestamp('>=', $from);
		$c->timestamp('<=', $to);
		$userViewUserInToDay = VstUserViewUser::model()->find($c);
		if(!empty($userViewUserInToDay)){
			return true;
		}
		return false;
	}
	
}