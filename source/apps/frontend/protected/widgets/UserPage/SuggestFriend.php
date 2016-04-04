<?php

class SuggestFriend extends CWidget {
	public $class_css	=	false;
    public function run() {
        if (!Yii::app()->user->isGuest) {
        	$country_in_cache   =   new CountryonCache();
        	$my_friendlist_ids	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        	
        	$my_friendlist_ids[] = Yii::app()->user->id;
        	//get my request
        	$request_friendids	=	YumFriendship::model()->getFriendIdFromRequest(Yii::app()->user->id);
        	if($request_friendids){
        		$my_friendlist_ids	=	array_merge($request_friendids, $my_friendlist_ids);
        	}
        	
        	$profile_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
        	
        	$offset = Yii::app()->request->getParam('offset', 0);
        	$limit = Yii::app()->params->search_result['suggest_friend_limit'];
        	
        	$filter = array();
        	 
        	 
        	if($profile_setting){
        	
        		//sex role
        		switch ($profile_setting->sex_role){
        			case ProfileSettingsConst::SEXROLE_TOP:
        				$filter['sex_role'] = array('in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE, ProfileSettingsConst::SEXROLE_VERSATILE));
        				break;
        			case ProfileSettingsConst::SEXROLE_BOTTOM:
        				$filter['sex_role'] = array('in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_TOP_VERSATILE, ProfileSettingsConst::SEXROLE_VERSATILE));
        				break;
        			default:
        				$filter['sex_role'] = array('in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_TOP_VERSATILE, ProfileSettingsConst::SEXROLE_VERSATILE, ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE));
        				break;
        		}
        	
        		//Sexuality
        		if($profile_setting->sexuality){
        			$filter['sexuality'] =	array('=' => $profile_setting->sexuality);
        		}
        	}
        	
        	//set by real IP
        	if(!isset(Yii::app()->session['checkin_country_id'])){
        		$current_country = $country_in_cache->getCurrentCountry();
        		if(isset($current_country['id'])){
        			$country_id	=	$current_country['id'];
        			$filter['current_country_id'] = array('=' => $country_id);
        		}else{
        			if (!empty($profile_location->current_country_id)) {
        				$filter['current_country_id'] = array('=' => $profile_location->current_country_id);
        			}
        		}
        	}else{
        		$filter['current_country_id'] = array('=' => Yii::app()->session['checkin_country_id']);
        	}
        	 
        	$search_conditions = array(
        			'filter' => $filter,
        			'keyword' => false,
        			'country_id'	=>	$filter['current_country_id']['=']
        	);
        	
        	$elasticsearch	=	new Elasticsearch();
        	$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $my_friendlist_ids, $offset, $limit);
        	
            $this->controller->renderPartial('//friend/partial/widget_friendsuggest', array(
            		'suggest_data' => $data_search['fulldata'],
            		'class_css'	=>	$this->class_css
            ));
        }
    }

}