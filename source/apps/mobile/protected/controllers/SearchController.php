<?php

require_once('Zend/Search/Lucene.php');

/**
 * @author HuyDo
 * @desc My Controller
 */
class SearchController extends MemberController {

	public function actionSearchlocation() {
	
		$limit = Yii::app()->params->search_result['mobile_limit_display'];
		$offset = Yii::app()->request->getParam('offset', 0);
	
		$city_id = Yii::app()->request->getPost('city_id', false);
		$district_id = Yii::app()->request->getPost('district_id', false);
		$country_id = Yii::app()->request->getPost('country_id', false);
		$state_id = Yii::app()->request->getPost('state_id', false);
		$sex_role = Yii::app()->request->getPost('sex_role', false);
		$sexuality = Yii::app()->request->getPost('sexuality', false);
		$looking_for = Yii::app()->request->getPost('looking_for', false);
		$looking_foronline = Yii::app()->request->getPost('looking_foronline', false);
		$having_avatar = Yii::app()->request->getPost('have_avatar', false);
		$txt_name = Yii::app()->request->getPost('txt-name', false);
		$txt_age_from = Yii::app()->request->getPost('txt-age-from', false);
		$txt_age_to = Yii::app()->request->getPost('txt-age-to', false);
		$weight_from = Yii::app()->request->getPost('weight-from', false);
		$weight_to = Yii::app()->request->getPost('weight-to', false);
		$weight_unit = Yii::app()->request->getPost('weight-unit', false);
		$height_from = Yii::app()->request->getPost('height-from', false);
		$height_to = Yii::app()->request->getPost('height-to', false);
	
		$height_unit = Yii::app()->request->getPost('height-unit', false);
	
		$txt_body = Yii::app()->request->getPost('txt-body', false);
	
		$txt_relationship = Yii::app()->request->getPost('txt-relationship', false);
		$txt_safe_sex = Yii::app()->request->getPost('txt-safe-sex', false);
		$txt_ethnics = Yii::app()->request->getPost('txt-ethnics', false);
	
		$filter = array();
		$filter_cal = array();
		$search_global	=	0;
		if ($country_id) {
			$filter['current_country_id'] = array('=' => $country_id);
			$search_global	=	0;
	
		}else{
			$search_global	=	1;
		}
	
		if ($state_id) {
			$filter['current_state_id'] = array('=' => $state_id);
		}
		if ($city_id) {
			$filter['current_city_id'] = array('=' => $city_id);
		}
		if ($district_id) {
			$filter['current_district_id'] = array('=' => $district_id);
		}
		if ($having_avatar) {
			$filter['have_avatar'] = array('=' => $having_avatar);
		}
		//apply into filter
		if ($sex_role) {
			$filter['sex_role'] = array('in' => $sex_role);
		}
		if ($sexuality) {
			$filter['sexuality'] = array('in' => $sexuality);
		}
		if($looking_foronline){
			$filter['online_lookingfor'] = array('in' => $looking_foronline);;
		}
		if ($looking_for) {
			$filter['looking_for'] = array('in_set' => $looking_for);
		}
	
		if ($txt_body) {
			$filter['body'] = array('=' => $txt_body);
		}
		if ($txt_relationship) {
			$filter['relationship'] = array('=' => $txt_relationship);
		}
		if ($txt_safe_sex) {
			$filter['safer_sex'] = array('=' => $txt_safe_sex);
		}
		if ($txt_ethnics) {
			$filter['ethnic_id'] = array('=' => $txt_ethnics);
		}
	
		//for convert unit weight
		if ($weight_from) {
			//convert kg to pound
			if ($weight_unit == UsrProfileSettings::EN_UNIT) {
				$weight_from = $weight_from * Yii::app()->params['pound_to_kg'];
			}
		}
		if ($weight_to) {
			//convert pound to kg
			if ($weight_unit == UsrProfileSettings::EN_UNIT) {
				$weight_to = $weight_to * Yii::app()->params['pound_to_kg'];
			}
		}
	
		//for weight
		if($weight_from && $weight_to){
			$filter_cal['weight'][] = array('range' => array(array('lte' => $weight_to), array('gte' => $weight_from)));
		}else{
			if ($weight_from) {
				$filter_cal['weight'][] = array('>=' => $weight_from);
			}
			if ($weight_to) {
				$filter_cal['weight'][] = array('<=' => $weight_to);
			}
		}
	
		//convert unit height
		if ($height_from) {
			//convert m to cm
			if ($height_unit == UsrProfileSettings::EN_UNIT) {
				$height_from = $height_from * Yii::app()->params['feet_to_cm'];
			}
		}
		if ($height_to) {
			//convert pound to kg
			if ($height_unit == UsrProfileSettings::EN_UNIT) {
				$height_to = $height_to * Yii::app()->params['feet_to_cm'];
			}
		}
	
		//for height
		if($height_from && $height_to){
			$filter_cal['height'][] = array('range' => array(array('lte' => $height_to), array('gte' => $height_from)));
		}else{
			if ($height_from) {
				$filter_cal['height'][] = array('>=' => $height_from);
			}
			if ($height_to) {
				$filter_cal['height'][] = array('<=' => $height_to);
			}
		}
		/*
		 //for age
		*/
		if($txt_age_from && $txt_age_to){
			$filter_cal['age'][] = array('range' => array(array('lte' => $txt_age_to), array('gte' => $txt_age_from)));
		}else{
			if ($txt_age_from) {
				$filter_cal['age'][] = array('>=' => $txt_age_from);
			}
			if ($txt_age_to) {
				$filter_cal['age'][] = array('<=' => $txt_age_to);
			}
		}
	
		$sort_by = array();
	
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		//remove myself
		$user_id_except = array(Yii::app()->user->id);
	
		$search_conditions = array(
				'filter' => $filter,
				'filter_cal'    =>  $filter_cal,
				'sort_by' => $sort_by,
				'keyword' => false,
				'country_id'	=>	$country_id
		);
	
		$elasticsearch	=	new Elasticsearch();
		$sort_script	=	"doc['last_activity'].value";
		$elasticsearch->setSortRules($sort_script);
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
	
		$html = $this->renderPartial('search_location', array(
				'fullscreen'	=>	false,
				'my_friendlist'	=>	$my_friendlist,
				'show_more' => $data_search['show_more'],
				'data' => $data_search['fulldata'],
				'total' => $data_search['total']), true);
	
		$array_result_json = array(
				'html' => $html,
				'show_more' => $data_search['show_more'],
				'total' => $data_search['total'],
				'search_global'	=>	$search_global,
				'offset' => $offset + $limit,
				'limit' => $limit
		);
		echo json_encode($array_result_json);
		exit;
	}
	
	public function actionSearchcheckin() {
		 
		$offset = Yii::app()->request->getParam('offset', 0);
		$limit = Yii::app()->params->search_result['limit_display'];
	
		$search_conditions = array();
		$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
	
		$filter = array();
	
		if (!empty($profile_location->current_country_id)) {
			$filter['current_country_id'] = array('=' => $profile_location->current_country_id);
		}
	
		if (!empty($profile_location->current_state_id)) {
			$filter['current_state_id'] = array('=' => $profile_location->current_state_id);
		}
	
		if (!empty($profile_location->current_city_id)) {
			$filter['current_city_id'] = array('=' => $profile_location->current_city_id);
		}
		if (!empty($profile_location->current_district_id)) {
			$filter['current_district_id'] = array('=' => $profile_location->current_district_id);
		}
	
	
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		//remove myself
		$user_id_except = array(Yii::app()->user->id);
	
		$search_conditions = array(
				'filter' => $filter,
				'keyword' => false,
				'country_id'	=>	!empty($profile_location->current_country_id)	?	$profile_location->current_country_id	:	0
		);
	
		$elasticsearch	=	new Elasticsearch();
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
	
		$html = $this->renderPartial('search_location', array(
				'fullscreen'	=>	false,
				'my_friendlist'	=>	$my_friendlist,
				'show_more' => $data_search['show_more'],
				'data' => $data_search['fulldata'],
				'total' => $data_search['total']), true);
	
		$array_result_json = array(
				'html' => $html,
				'show_more' => $data_search['show_more'],
				'total' => $data_search['total'],
				'offset' => $offset + $limit,
				'limit' => $limit
		);
		echo json_encode($array_result_json);
		exit;
	}
	   
    public function actionSearchByUserSetting() {
    
    	$country_in_cache   =   new CountryonCache();
    	$search_conditions = array();
    
    	$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    
    	$state_info		=	array();
    	$city_info		=	array();
    	$district_info	=	array();
    	$country_info	=	array();
    
    	$filter = array();
    
    	$limit = Yii::app()->params->search_result['mobile_limit_display'];
    	$offset = Yii::app()->request->getParam('offset', 0);
    
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
    
    	//get country info
    	$country_info	=	SysCountry::model()->getCountryInfo($filter['current_country_id']);
    
    	if(isset(Yii::app()->session['checkin_state_id'])){
    		$filter['current_state_id'] = array('=' => Yii::app()->session['checkin_state_id']);
    		$state_info	=	LocationState::model()->getStateInfo(Yii::app()->session['checkin_state_id']);
    	}
    	if(isset(Yii::app()->session['checkin_city_id'])){
    		$filter['current_city_id'] = array('=' => Yii::app()->session['checkin_city_id']);
    		$city_info	=	SysCity::model()->getCityInfo(Yii::app()->session['checkin_city_id']);
    	}
    	if(isset(Yii::app()->session['checkin_district_id'])){
    		$filter['current_district_id'] = array('=' => Yii::app()->session['checkin_district_id']);
    		$district_info	=	SysDistrict::model()->getDistrictInfo(Yii::app()->session['checkin_district_id']);
    	}
    
    	$search_conditions = array(
    			'filter' => $filter,
    			'keyword' => false,
    			'country_id'	=>	$filter['current_country_id']['=']
    	);
    	$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
    	//remove myself
    	$user_id_except = array(Yii::app()->user->id);
    
    	$elasticsearch	=	new Elasticsearch();
    	$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
    
    	$html = $this->renderPartial('search_location', array(
    			'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel(),
    			'fullscreen'	=>	false,
    			'my_friendlist'	=>	$my_friendlist,
    			'total' => $data_search['total'],
    			'data' => $data_search['fulldata']), true);
    
    	$array_result_json = array(
    			'html' => $html,
    			'show_more' => $data_search['show_more'],
    			'current_country_name'	=>	isset($country_info['name'])	?	$country_info['name']	:	0,
    			'current_state_name'	=>	isset($state_info['name'])	?	$state_info['name']	:	0,
    			'current_city_name'	=>	isset($city_info['name'])	?	$city_info['name']	:	0,
    			'current_district_name'	=>	isset($district_info['name'])	?	$district_info['name']	:	0,
    			'total' => $data_search['total'],
    			'offset' => $offset + $limit,
    			'limit' => $limit
    	);
    	echo json_encode($array_result_json);
    	exit;
    }

    public function actionQuickSearch(){
    	        
        $limit = Yii::app()->params->search_result['mobile_limit_display'];
        $offset = Yii::app()->request->getParam('offset', 0);
    	$keyword	=	Yii::app()->request->getParam('q', false);

    	//set keyword search 
        $search_conditions = array(
            'keyword' => $keyword,
            'country_id'	=>	0
        ); 
                
        $my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        //remove myself
        $user_id_except = array(Yii::app()->user->id);

        $elasticsearch	=	new Elasticsearch();
        $data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
                
        $html = $this->renderPartial('search_location', array(
        	'fullscreen'	=>	true,
        	'my_friendlist'	=>	$my_friendlist,
        	'total' => $data_search['total'],
            'data' => $data_search['fulldata']), true);

        $array_result_json = array(
            'html' => $html,
            'show_more' => $data_search['show_more'],
            'total' => $data_search['total'],
            'offset' => $offset + $limit
        );
        echo json_encode($array_result_json);
        exit;    	
    }
    public function actionUpdatePosition(){
    	$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
    		$post = Yii::app()->request->getPost('UsrProfileSettings');
    			
    		//update index search
    		$elasticsearch	=	new Elasticsearch();
    		$elasticsearch->updatePosition(Yii::app()->user->id, $profile_location->current_country_id, $post['latitude'], $post['longitude']);    		
    		
    		$model->latitude = $post['latitude'];
    		$model->longitude = $post['longitude'];
    		$model->attributes = $post;
    		$model->validate();
    			
    		if (!$model->hasErrors()) {
    			$model->save();
    			
    		}else{
    			print_r($model->errors);
    			exit();
    		}
    	}
    	Yii::app()->end();    	
    }
    public function actionUpdateLastActivity(){
    	$elasticsearch	=	new Elasticsearch();
    	$elasticsearch->updateLastActivity(Yii::app()->user->id, time());
    	echo 1;
    	exit;
    }
}