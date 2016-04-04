<?php

class Member extends YumUser {
	const Level_Visitor 		= "Visitor";
	const Level_FreeMember 		= "FreeMember";
	const Level_Premium 		= "Premium";
	const Level_VIP				= "VIP";
	const Level_STAR			= "STAR";
	
	const PREFIX_VISITOR		= 'guest';
	const PREFIX_FREEMEMBER		= 'm';
	const PREFIX_PREMIUM		= 'p';
	const PREFIX_VIP			= 'vip';
	const PREFIX_STAR			= 'star';
	
	const PREFIX_VIP_URL		= '';
	const PREFIX_STAR_URL		= '';
	
	const LEVELS				= '(guest|m|p)';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function attributeLabels() {
		return array (
			'lastname' => 'Last Name',
			'firstname' => 'First Name',
			'alias_name' => 'Alias Url',
		);
	}
	
	public function relations(){
		$relations = array();
		$relations['balance'] = array(self::HAS_ONE, 'Balance', 'user_id');
// 		$relations['avatar'] = array(self::BELONGS_TO, 'Photo', 'avatar_id');
        $relations['profile_settings'] = array(self::HAS_ONE, 'UsrProfileSettings', 'user_id');
        $relations['profile_location'] = array(self::HAS_ONE, 'UsrProfileLocation', 'user_id');
		return CMap::mergeArray(
			$relations,
			parent::relations()
		);
	}
	
	public function rules(){
		
		$user = Member::getUserByUsername($this->username);
		if ($user->alias_name != $this->alias_name)
			$rules[] = array('alias_name', 
				'unique',
				'message' => Yum::t("This alias name already exists."));
		
		$rules[] = array(
				'alias_name',
				'match',
				'pattern' => '/^[a-z0-9]+$/u',
				'message' => Yum::t("Incorrect symbol (A-z0-9.)"));
		
		$rules[] = array('lastname, firstname, alias_name', 'safe');
		$parentRules = parent::rules();
		if(!empty($parentRules) && is_array($parentRules)){
			$rules = array_merge($rules, $parentRules);
		}
		return $rules;
	}
	
	public static function getUserByUsername($username){
		$criteria = new CDbCriteria;
		$criteria->addCondition('username = :username');
		$criteria->params = array(':username' => $username);
		$tmp = Member::model()->find($criteria);
		return $tmp ? $tmp : new Member();
	}
	
	public function getNoAvatar(){
		return Yii::app()->request->getHostInfo()."/public/images/no-user.jpg";
	}
	
	public function getAvatar($html = false, $thumb = false, $size = ""){
    	$urlAvatar	=	'/public/images/no-user.jpg';
    	$Elasticsearch	=	new Elasticsearch();
    	$e_user			=	$Elasticsearch->load($this->id);
    	if(!empty($e_user)){
    		$params = CParams::load ();
    		$urlAvatar = "http://{$params->params->img_webroot_url}{$e_user['avatar']}";
    	}else{    		
	    	if(!empty($this->avatar)){
	    		/*
		    	if(is_numeric($this->avatar)){
		    		$photo = Photo::model()->cache(500)->findByAttributes(array('id'=>$this->avatar, 'status'=>1));
		    		if(!empty($photo->name) && file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/thumb160x160/'. $photo->name)){
		    			$url = Yii::app()->createUrl($photo->path .'/thumb160x160/'. $photo->name);
			    		return $url;
		    		} 
		    	}else{
			        $url = VAvatar::model()->urlAvatar($this->avatar);		
			        if(!empty($url))
					    return $url;
		    	}
		    	*/
		    	
		    	if(is_numeric($this->avatar)){
		    		$photo = Photo::model()->findByAttributes(array('id'=>$this->avatar, 'status'=>1));
		    		if($photo){
		    			$urlAvatar = $photo->getImageThumbnail160x160(true);	  
		    		}  	
		    	}else{
		    		$urlAvatar = $imageLarge = VAvatar::model()->urlAvatar($this->avatar);
		    	}
	    	}else{
				$urlAvatar= $this->getNoAvatar();	
	    	}
    	}
    	
		if($html){
			$htmlOptions	=	array();
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-user.jpg'  .'\');';
			return CHtml::image($urlAvatar, 'avatar', $htmlOptions);
		}else{
			return $urlAvatar;
		}
	}
	
	public function createUrl($url, $params = array()) {
		return Yii::app()->createUrl($url, CMap::mergeArray(array('alias' => $this->getAliasName()), $params));
	}
	
	public function createAbsoluteUrl($url, $params = array()) {
		return Yii::app()->createAbsoluteUrl($url, CMap::mergeArray(array('alias' => $this->getAliasName()), $params));
	}
		
	public function getUserUrl($absolute = false){
		return ($absolute) ? $this->createAbsoluteUrl('//my/view', array()) : $this->createUrl('//my/view');
	}
		
	public function getUserFeedUrl(){
		return Yii::app()->homeUrl;
	}
	
	public function getUserLink($htmlOptions = array()){
		return $this->createLink($this->getDisplayName(), '//my/view', array(), $htmlOptions);
	}
	
	public function getDisplayName(){
	    return $this->username;
		$name = '';
		if(!empty($this->profile)){
			$name = sprintf('%s %s', $this->profile->firstname, $this->profile->lastname);
		}
		if(empty($name) || $name == ' '){
			$name = $this->username;
		}
		return $name;	
	}
	
	public function createLink($text, $url, $params, $htmlOptions = array()) {
		return CHtml::link($text, $this->createUrl($url, $params), $htmlOptions);
	}
	
	public function getAliasName(){
		if (!empty($this->alias_name)){
			return $this->alias_name;
		}
		return false;
	}
	
	public function checkRole($action){
		if(Yii::app()->user->checkAccess($action)){
			return true;
		}
		return false;		
	}
	
	public function isMe(){
		if(Yii::app()->user->id == $this->id){
			return true;
		}
		return false;
	}
	
	public function isFriendOf($invited_id)
	{
	    return (!empty($this->id)) ? YumFriendship::areFriends($this->id, $invited_id) : false;
	}
	
	public function getActivationUrl()
	{
	    /**
	     * Quick check for a enabled Registration Module
	     */
        if (!empty($this->activationKey)) {
            $params['key'] = $this->activationKey;
            $params['tk'] = Util::encryptRandCode($this->alias_name);
            return Yii::app()->controller->createAbsoluteUrl('/register/activation', $params);
        }
	    return Yum::t('Activation Url cannot be retrieved');
	}
	
	
	public function getEmail(){
		if($this->profile->email){
			return $this->profile->email;
		} 
		return false;
	}
	
	public function verifyIsMe($withpass){
		if(!empty($withpass) && !empty($this->id)){			
			if(YumEncrypt::validate_password($withpass, $this->password, $this->salt)){
				return true;
			}
		}		
		return false;
	}
}

?>