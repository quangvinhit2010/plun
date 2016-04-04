<?php

/**
 * @author HuyDo
 * @desc My Controller
 */
class SearchController extends MemberController {
	public function actionIndex(){
		
		$country_in_cache   =   new CountryonCache();
		$search_conditions = array();
		
		$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		
		$state_info		=	array();
		$city_info		=	array();
		$district_info	=	array();
		$country_info	=	array();
		
		$filter = array();
		
		$limit = Yii::app()->params->search_result['limit_display'];
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
		$country_info	=	SysCountry::model()->getCountryInfo($filter['current_country_id']['=']);
		
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
		if(($key = array_search(Yii::app()->user->id, $my_friendlist)) !== false) {
			unset($my_friendlist[$key]);
		}
		
		if(!Yii::app()->user->data()->is_vip){
			//remove myself
			//$user_id_except = array(Yii::app()->user->id);
			$user_id_except = false;
		}else{
			$user_id_except	=	array();
		}
		
		$elasticsearch	=	new Elasticsearch();
		$sort_script	=	"(doc['have_avatar'].value == 1)	?	doc['last_activity'].value + 7500000 : doc['last_activity'].value";
		$elasticsearch->setSortRules($sort_script);
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
			
		$this->renderPartial('index', array(
				'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel(),
				'fullscreen'	=>	false,
				'my_friendlist'	=>	$my_friendlist,
				'total_result' => $data_search['total'],
				'data' => $data_search['fulldata'],
				'show_more' => $data_search['show_more'],
				'current_country_name'	=>	isset($country_info['name'])	?	$country_info['name']	:	false,
				'current_state_name'	=>	isset($state_info['name'])	?	$state_info['name']	:	false,
				'current_city_name'	=>	isset($city_info['name'])	?	$city_info['name']	:	false,
				'current_district_name'	=>	isset($district_info['name'])	?	$district_info['name']	:	false,	
				'offset' => $offset + $limit
		));
	}
    public function actionSearchlocation() {

        $limit = Yii::app()->params->search_result['limit_display'];
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
    	if(!Yii::app()->user->data()->is_vip){
			//remove myself
			//$user_id_except = array(Yii::app()->user->id);
    		$user_id_except = false;
		}else{
			$user_id_except	=	array();
		}
        
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
        //$user_id_except = array(Yii::app()->user->id);
        if(!Yii::app()->user->data()->is_vip){
        	//$user_id_except = array(Yii::app()->user->id);
        	$user_id_except = false;
        }else{
        	$user_id_except	=	array();
        }
        
        $search_conditions = array(
            'filter' => $filter,
            'keyword' => false,
        	'country_id'	=>	!empty($profile_location->current_country_id)	?	$profile_location->current_country_id	:	0
        );        
        
        $elasticsearch	=	new Elasticsearch();
        $sort_script	=	"(doc['have_avatar'].value == 1)	?	doc['last_activity'].value + 7500000 : doc['last_activity'].value";
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
        
        $limit = Yii::app()->params->search_result['limit_display'];
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
        $country_info	=	SysCountry::model()->getCountryInfo($filter['current_country_id']['=']);
        		
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
        $sort_script	=	"(doc['have_avatar'].value == 1)	?	doc['last_activity'].value + 7500000 : doc['last_activity'].value";
        $elasticsearch->setSortRules($sort_script);
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
	public function actionSearchUsersSuggest(){
		$keyword	=	Yii::app()->request->getParam('q', false);
        $limit = Yii::app()->params->search_result['limit_display'];
        $offset = Yii::app()->request->getParam('offset', 0);
        		
	    //set keyword search 
        $search_conditions = array(
            'keyword' => $keyword,
            'country_id'	=>	0
        );       
        $my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        //remove myself
        $user_id_except = array(Yii::app()->user->id);

        $elasticsearch	=	new Elasticsearch();
        $sort_script	=	"doc['username'].value.length()";
        $elasticsearch->setSortRules($sort_script, 'asc');
        $data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, 3);        
        
        $params = CParams::load ();
        $img_webroot_url	=	$params->params->img_webroot_url;
        
		$dbrows	=	array();
        foreach($data_search['fulldata'] AS $row){
        	$row	=	$row['_source'];
	        $url = Yii::app()->createUrl('//my/view', array('alias' => $row['alias_name']));
	        if($row['have_avatar']){
	        	$avatar	=	"http://{$img_webroot_url}{$row['avatar']}";
	        }else{
	        	$avatar	=	$row['avatar'];
	        }
			$dbrows[]	=	array(
    	                'id' => $row['user_id'],
    	                'text' => $row['username'],
						'ava'	=>	$avatar,
						'url'	=>	$url,
						'disabled'	=>	true
    	    );
		}
		$dbrows[]	=	array(
		        'id' => 0,
		        'text' => Lang::t('search', 'Search for').": $keyword",
		        'ava'	=>	'',
		        'url'	=>	Yii::app()->user->data()->createUrl('//search/quicksearch', array('q'=>$keyword)),
		        'disabled'	=>	true
		);
        echo CJSON::encode($dbrows);
        Yii::app()->end();  
	}
    public function actionQuickSearch(){
    	        
        $limit = Yii::app()->params->search_result['limit_display'];
        $offset = Yii::app()->request->getParam('offset', 0);
    	$keyword	=	Yii::app()->request->getParam('q', false);

    	//set keyword search 
        $search_conditions = array(
            'keyword' => $keyword,
            'country_id'	=>	0
        ); 
                
                
        $my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        //remove myself
        //$user_id_except = array(Yii::app()->user->id);
        //remove myself
        if(!Yii::app()->user->data()->is_vip){
        	//remove myself
        	//$user_id_except = array(Yii::app()->user->id);
        	$user_id_except = false;
        }else{
        	$user_id_except	=	array();
        }
        
        $elasticsearch	=	new Elasticsearch();
        $sort_script	=	"doc['username'].value.length()";
        $elasticsearch->setSortRules($sort_script, 'asc');
        $data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
                
        if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
        	$html = $this->renderPartial('search_location', array(
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
        }else{
        	
        	$model = new Activity();
        	$model->is_feed = true;
        	$model->user_id = $this->user->id;
        	$pindex = 0;
        	if (isset($_GET['pindex']))
        		$pindex = intval($_GET['pindex']);
        	
        	$limit_newsfeed = Yii::app()->params->news_feed['limit_display'];
        	
        	$criteria = $model->getActivitiesCriteria($this->user->id, $limit);
        	$total_newsfeed    = $model->count($criteria);
        	        	
        	$this->render('quicksearch', array(
        			'sex_roles_title' => ProfileSettingsConst::getSexRoleLabel(),
        			'my_friendlist'	=>	$my_friendlist,
        			'total_result' => $data_search['total'],
        			'keyword'	=>	$keyword,
        			'search_data' => $data_search['fulldata'],
        			'total_newsfeed' => $total_newsfeed,
        			'show_more' => $data_search['show_more'],
        			'offset' => $offset + $limit,
        			'limit_newsfeed'	=>	$limit_newsfeed
        	));
        }
    }
    public function actionUpdatePosition(){
    	$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
    		$post = Yii::app()->request->getPost('UsrProfileSettings');
    		 
    		//update index search
    		$elasticsearch	=	new Elasticsearch();
    		$elasticsearch->updatePosition(Yii::app()->user->id, $post['latitude'], $post['longitude']);
    
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
    
    public function actionSuggestChat(){
    	$country_in_cache   =   new CountryonCache();
    	
    	$limit = Yii::app()->params->search_result['limit_display'];
    	$offset = Yii::app()->request->getParam('offset', 0);
    	$sort_by = array();
    	
    	$profileSettings = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	switch ($profileSettings->sex_role){
    		case ProfileSettingsConst::SEXROLE_TOP: 
    			$sex_role	=	ProfileSettingsConst::SEXROLE_BOTTOM;
    		break;
    		case ProfileSettingsConst::SEXROLE_BOTTOM:
    			$sex_role	=	ProfileSettingsConst::SEXROLE_TOP;
    			break;
    		case ProfileSettingsConst::SEXROLE_VERSATILE: 
    			$sex_role	=	ProfileSettingsConst::SEXROLE_BOTTOM;
    			break;    			 
    		default: 
    			$sex_role	=	ProfileSettingsConst::SEXROLE_TOP;
    			break;    			   		
    	}
    	
    	
    	$filter = array();
    	$filter_cal = array();
    	
   
    	$current_country = $country_in_cache->getCurrentCountry();
    	
    	if(isset($current_country['id'])){
    		$country_id	=	!empty($current_country['id'])	?	$current_country['id']	:	HomeController::COUNTRY_ID_DEFAULT;
    	}else{
    		$country_id	=	HomeController::COUNTRY_ID_DEFAULT;
    	}
    	 	 	
    	$filter_cal['last_activity'][] = array('range' => array(array('lte' => time()), array('gte' => time() - 900)));
    	
    	$user_id_except = array(Yii::app()->user->id);
    	
    	$elasticsearch	=	new Elasticsearch();
    	$sort_script	=	"(doc['have_avatar'].value == 1)	?	(doc['sex_role'].value == $sex_role ? 3 : 2) : 1" ;
    	$elasticsearch->setSortRules($sort_script);
    	
    	$search_conditions = array(
    			'filter' => $filter,
    			'filter_cal'    =>  $filter_cal,
    			'sort_by' => $sort_by,
    			'keyword' => false,
    			'country_id'	=>	$country_id
    	);
    	
    	$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
    	
    	if($data_search['total'] > 0){
    		//get random 
    		$user 	= 	$data_search['fulldata'][array_rand($data_search['fulldata'])];
    		echo json_encode(array('result' => true, 'data' => array('username' => $user['_source']['username'], 'avatar' => $user['_source']['avatar'])));
    	}else{
    		echo json_encode(array('result' => false));
    	}
    	
    	Yii::app()->end();
    }
    public function actionGetBirthdayList(){
    	$country_in_cache   =   new CountryonCache();
    	 
    	$limit = Yii::app()->request->getParam('limit', 0);
    	$offset = Yii::app()->request->getParam('offset', 0);
    	$date = Yii::app()->request->getParam('date', date('m-d'));
    	
    	$sort_by = array();
    	 
    	$profileSettings = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	switch ($profileSettings->sex_role){
    		case ProfileSettingsConst::SEXROLE_TOP:
    			$sex_role	=	ProfileSettingsConst::SEXROLE_BOTTOM;
    			break;
    		case ProfileSettingsConst::SEXROLE_BOTTOM:
    			$sex_role	=	ProfileSettingsConst::SEXROLE_TOP;
    			break;
    		case ProfileSettingsConst::SEXROLE_VERSATILE:
    			$sex_role	=	ProfileSettingsConst::SEXROLE_BOTTOM;
    			break;
    		default:
    			$sex_role	=	ProfileSettingsConst::SEXROLE_TOP;
    			break;
    	}
    	 
    	 
    	$filter = array();
    	$filter_cal = array();
    	 
    	 
    	$current_country = $country_in_cache->getCurrentCountry();
    	 
    	if(isset($current_country['id'])){
    		$country_id	=	!empty($current_country['id'])	?	$current_country['id']	:	HomeController::COUNTRY_ID_DEFAULT;
    	}else{
    		$country_id	=	HomeController::COUNTRY_ID_DEFAULT;
    	}

    	$filter['birthday_date'] = array('=' => $date);
    	
    	$user_id_except = false;
    	 
    	$elasticsearch	=	new Elasticsearch();
    	$sort_script	=	"(doc['have_avatar'].value == 1)	?	(doc['sex_role'].value == $sex_role ? 3 : 2) : 1" ;
    	$elasticsearch->setSortRules($sort_script);
    	 
    	$search_conditions = array(
    			'filter' => $filter,
    			'filter_cal'    =>  $filter_cal,
    			'sort_by' => $sort_by,
    			'keyword' => false,
    			'country_id'	=>	$country_id
    	);
    	 
    	$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
    	
   	 
    	if($data_search['total'] > 0){
    		$data	=	array();
    		$params = CParams::load ();
    		$img_webroot_url	=	$params->params->img_webroot_url;
    		foreach ($data_search['fulldata'] AS $row){
    			if($row['_source']['have_avatar']){
    				$avatar	=	"http://{$img_webroot_url}{$row['_source']['avatar']}";
    			}else{
    				$avatar	=	$row['_source']['avatar'];
    			}
    			$data[]	=	array('birthday' => $row['_source']['birthday'], 'username' => $row['_source']['username'], 'avatar' => $avatar);
    		}
    		echo json_encode(array('result' => true, 'data' => $data));
    	}else{
    		echo json_encode(array('result' => false));
    	}
    	 
    	Yii::app()->end();
    }
}