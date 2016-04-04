<?php

/**
 * @author vinhnguyen
 * @desc Settings Controller
 */
class SettingsController extends MemberController {

	public function actionIndex() {
	    if(!$this->user->isMe()){
	        throw new CHttpException ( 404, 'The requested page does not exist.' );
	    }
	    
	    
	    /*
		$cs = Yii::app()->clientScript;
		
		$cs->registerCssFile(Yii::app()->theme->baseUrl . '/resources/css/jquery.autocompletefb.css');
		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.bgiframe.min.js');
		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocomplete.js');
		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocompletefb.js');
		$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/settings/common.js');
		*/
		$this->render('page/index', array());
	}
	public function actionBasicinfo(){		
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}
				
		$country_on_cache = new CountryonCache();
		$state_on_cache = new StateonCache();
		$city_on_cache = new CityonCache();
		$district_on_cache  =   new DistrictonCache();
		
		
		$list_city = array();
		$list_district =   array();
		$list_state = array();
		$list_country = $country_on_cache->getListCountry();
		
		if ($list_country) {
			if (!$model->country_id) {
				$current_country = $country_on_cache->getCurrentCountry();
				$country_id	=	$current_country['id'];
			}else{
				$country_id	=	$model->country_id;
			}
			//get state list
			$list_state	=	$state_on_cache->getlistStateinCountry($country_id);
				
			if($list_state){
				//get city list
				if($model->state_id){
					$list_city	=	$city_on_cache->getlistCityinState($model->state_id);
					if($list_city){
						//get district list
						if($model->city_id){
							$list_district	=	$district_on_cache->getlistDistrictinCity($model->city_id);
						}

					}
				}
			}
		
		}
		
		//set birthday
		if (!empty($model->birthday)) {
			//build month
			$model->birthday_month = date('n', $model->birthday);
			//build day
			$model->birthday_day = date('j', $model->birthday);
		}		
		
		$this->render('page/basicinfo', array(
            'model' => $model,
			'list_country'	=>	$list_country,
            'list_city' => ($list_city ? $list_city : array()),
			'list_state' => ($list_state ? $list_state : array()),
            'list_district' => ($list_district ? $list_district : array()),
		));
	}
	public function actionWhatyousee(){
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}
		/*
		$unit = json_decode($model->persional_unit, true);
		
		//build unit weight
		if (isset($unit['unit_weight'])) {
			$model->unit_weight = $unit['unit_weight'];
			if ($unit['unit_weight'] == '1') {
				$model->weight = round($model->weight * Yii::app()->params['kg_to_pound'], 2);
			}
			if ($unit['unit_weight'] == '2') {
				$model->weight = round($model->weight * Yii::app()->params['g_to_kg'], 2);
			}						
		}else{
			$model->unit_weight	=	0;
		}
		
		//build unit height
		if (isset($unit['unit_height'])) {
			$model->unit_height = $unit['unit_height'];
			if ($unit['unit_height'] == '1') {
				$model->height = round($model->height * Yii::app()->params['cm_to_m'], 2);
			}
			if ($unit['unit_height'] == '2') {
				$model->height = round($model->height * Yii::app()->params['cm_to_feet'], 2);
			}
			if ($unit['unit_height'] == '3') {
				$model->height = round($model->height * Yii::app()->params['cm_to_inch'], 2);
			}						
		}else{
			$model->unit_height = 0;
		}

		*/

		if($model->measurement == 1){
			$model->unit_weight = 1;
			$model->unit_height = 1;
			$weight_unit_label	=	'kg';
			$height_unit_label	=	'cm';
		}else{
			$model->unit_weight = 2;
			$model->unit_height = 2;
			$weight_unit_label	=	'lbs';
			$height_unit_label	=	'ft';
		}		
		$this->render('page/whatyousee', array(
				'model' => $model,
				'weight_unit_label'	=>	$weight_unit_label,
				'height_unit_label'	=>	$height_unit_label,
		));		
		
		
	}
	public function actionExtra(){		
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));
				
		$this->render('page/extra', array(
				'model' => $model,
		));		
	}
	public function actionChangepass(){
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));	
		$model_profile = YumProfile::model()->findByAttributes(array('user_id' => $user->id));
		$this->render('page/changepass', array(
				'model' => $model,
				'model_profile'	=>	$model_profile
		));			
	}
	public function actionMeasurement(){
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}		
		$this->render('page/measurement', array(
				'model' => $model
		));		
	}
	public function actionLanguages(){		
		if(!$this->user->isMe()){
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}	
		//get list languages
		$list_languages	=	SysLanguage::model()->getListLanguages();
		
		$this->render('page/languages', array(
				'model' => $model,
				'list_languages'	=>	$list_languages
		));			
	}
	public function actionSettings() {
		$user	=	Yii::app()->user->data();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user->id));

		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $user->id;
			$model->save();
		}
		
		$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if(!$model_location){
			//create profile current location
			$model_location     =   new UsrProfileLocation();
			$model_location->user_id       =   Yii::app()->user->id;
			$model_location->save();
		}
		
		$model_profile = YumProfile::model()->findByAttributes(array('user_id' => $this->usercurrent->id));

		$unit = json_decode($model->persional_unit, true);

		if (!empty($model->birthday)) {
			//build month
			$model->birthday_month = date('n', $model->birthday);
			//build day
			$model->birthday_day = date('j', $model->birthday);
		}

		//build unit weight
		if (isset($unit['unit_weight'])) {
			$model->unit_weight = $unit['unit_weight'];
			if ($unit['unit_weight'] == '1') {
				$model->weight = round($model->weight * Yii::app()->params['kg_to_pound'], 2);
			}
		}

		//build unit height
		if (isset($unit['unit_height'])) {
			$model->unit_height = $unit['unit_height'];
			if ($unit['unit_height'] == '1') {
				$model->height = round($model->height * Yii::app()->params['cm_to_m'], 2);
			}
		}
		//build looking for mannerism
		if(!empty($model->mannerism)){
			$model->mannerism	=	explode(',', $model->mannerism);
		}
		
		$country_in_cache = new CountryonCache();
		$state_in_cache = new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache  =   new DistrictonCache();
		

		$list_city = array();
		$list_district =   array();
		$list_state = array();

		$list_country = $country_in_cache->getListCountry();
		if ($list_country) {
			if (!$model->country_id) {
				$current_country = $country_in_cache->getCurrentCountry();
				$model->country_id	=	$current_country['id'];
			}
			//get state list
			$list_state	=	$state_in_cache->getlistStateinCountry($model->country_id);
			
			if($list_state){
				//get city list
				if(!$model->state_id){
					$first_state	=	current($list_state);
					$model->state_id	=	$first_state['id'];
				}
				$list_city	=	$city_in_cache->getlistCityinState($model->state_id);
				if($list_city){
					//get district list
					if(!$model->city_id){
						$first_city	=	current($list_city);
						$model->city_id	=	$first_city['id'];
					}
					$list_district	=	$district_in_cache->getlistDistrictinCity($model->city_id);
					if(!$list_district){
						$list_district	=	array();
					}
					if(!$model->district_id){
						$first_district	=	current($list_district);
						$model->district_id	=	$first_district['id'];
					}
				}else{
					$list_city	=	array();
				}
			}else{
				$list_state	=	array();
			}			

		}


		$this->renderPartial('partial/settings', array(
            'model' => $model,
            'model_profile' => $model_profile,
            'list_country' => $country_in_cache->getListCountry(),
            'list_city' => ($list_city ? $list_city : array()),
			'list_state' => ($list_state ? $list_state : array()),
            'list_district' => ($list_district ? $list_district : array()),

		));
	}
	public function actionSaveBasicInfo(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('UsrProfileSettings');
			$post['country_id']	=	!empty($post['country_id'])	?	$post['country_id']	:	0;
			$post['state_id']	=	!empty($post['state_id'])	?	$post['state_id']	:	0;
			$post['city_id']	=	!empty($post['city_id'])	?	$post['city_id']	:	0;
			$post['district_id']	=	!empty($post['district_id'])	?	$post['district_id']	:	0;
			$post['birthday'] = mktime(0, 0, 0, $post['birthday_month'], $post['birthday_day'], $post['birthday_year']);
			
			//for looking for
			$looking_for	=	!empty($post['looking_for'])		?	$post['looking_for']	:	false;
			if($looking_for){
				if(!empty($looking_for[0])){
					$post['looking_for'] = implode(',', $looking_for);
				}else{
					$post['looking_for'] = '';
				}
			}else{
				$post['looking_for'] = '';
			}
												
			//for languages
			$languages	=	!empty($post['languages'])		?	$post['languages']	:	false;
			
			
			if($languages){
				if(!empty($languages[0])){
					$post['languages'] = implode(',', $languages);
				}else{
					$post['languages'] = '';
				}
			}else{
				$post['languages'] = '';
			}
			
			$model->attributes = $post;
			$model->validate();
			
			if (!$model->hasErrors()) {
				$model->save();				
				//save current location
				$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
				if(!$model_location->published){
						$data	=	array(
							'current_country_id'	=>	$post['country_id'],
							'current_state_id'	=>	$post['state_id'],
							'current_city_id'	=>	$post['city_id'],
							'current_district_id'	=>	$post['district_id'],
						);
						$model_location->attributes = $data;
						$model_location->save();
				}				
				//save user search conditions
					$mysearch_conditions    =  	 array();
                    //set filter for search conditions
                    if(!empty($post['sex_role'])){
                    	$filter	=	array();
                        switch ($post['sex_role']){
		                    case ProfileSettingsConst::SEXROLE_TOP:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );
		                    break;
		                    case ProfileSettingsConst::SEXROLE_BOTTOM:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_TOP_VERSATILE, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;
		                    case ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;
		                    case ProfileSettingsConst::SEXROLE_TOP_VERSATILE:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;		                    	                     
		                    default: 
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_BOTTOM), 
		                        );
		                    break;
	                	}         
	                	$mysearch_conditions['filter'] = $filter;           	
                    }
                    // end set filter for search conditions					
					$this->usercurrent->updateAll(array('search_conditions' => json_encode($mysearch_conditions)), "id={$this->usercurrent->id}");
				
			}
		}
		//update index search
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateSearchIndexUser(Yii::app()->user->id);
		Yii::app()->end();		
	}
	public function actionSaveExtra(){
	    $model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
	        $post = Yii::app()->request->getPost('UsrProfileSettings');
	        
	        //for attributes
	        $my_attributes	=	isset($post['my_attributes'])		?	$post['my_attributes']	:	false;
	        if($my_attributes){
	            if(!empty($my_attributes[0])){
	                $post['my_attributes'] = implode(',', $my_attributes);
	            }else{
	                $post['my_attributes'] = '';
	            }
	        }else{
	            $post['my_attributes'] = '';
	        }
	        	        	        	        
	        $model->attributes = $post;
	        $model->validate();
	        	
	        if (!$model->hasErrors()) {
	            $model->save();
	        }
	    }
	    Yii::app()->end();	    
	}
	public function actionSaveWhatYouSee(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
	
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('UsrProfileSettings');
									
			$model->attributes = $post;
			$model->validate();
			
			if (!$model->hasErrors()) {
				$model->save();
			}
		}
		Yii::app()->end();		
	}	
	public function actionSaveLanguage(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('UsrProfileSettings');
			$model->attributes = $post;
			$model->validate();			
			if (!$model->hasErrors()) {
				$model->save();
				VLang::model()->setLangDefault();
			}			
		}
		
		Yii::app()->end();
	}
	public function actionSavemeasurement(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('UsrProfileSettings');
			$model->attributes = $post;
			$model->validate();
			if (!$model->hasErrors()) {
				$model->save();
			}
		}
		
		Yii::app()->end();		
	}
	public function actionSave() {
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		$unit = json_decode($model->persional_unit, true);
		if (!$unit) {
			$unit = array();
		}
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('UsrProfileSettings');

			if (isset($post['unit_height'])) {
				$unit['unit_height'] = $post['unit_height'];
			}
			if (isset($post['unit_weight'])) {
				$unit['unit_weight'] = $post['unit_weight'];
			}
			//convert pound to kg
			if ($post['unit_weight'] == '1') {
				$post['weight'] = round($post['weight'] / Yii::app()->params['kg_to_pound'], 2);
			}
			//convert g to kg
			if ($post['unit_weight'] == '2') {
				$post['weight'] = round($post['weight'] / Yii::app()->params['g_to_kg'], 2);
			}		
				
			//convert m to cm
			if ($post['unit_height'] == '1') {
				$post['height'] = round($post['height'] / Yii::app()->params['cm_to_m'], 2);
			}
			//convert feet to cm
			if ($post['unit_height'] == '2') {
				$post['height'] = round($post['height'] / Yii::app()->params['cm_to_feet'], 2);
			}
			//convert inch to cm
			if ($post['unit_height'] == '3') {
				$post['height'] = round($post['height'] / Yii::app()->params['cm_to_inch'], 2);
			}

			
			$post['birthday'] = mktime(0, 0, 0, $post['birthday_month'], $post['birthday_day'], $post['birthday_year']);
			
			$post['hobbies']	=	isset($post['hobbies'])	?	json_encode($post['hobbies'])	:	'';
			
			$post['persional_unit'] = json_encode($unit);
			
			//for looking for
			$looking_for	=	isset($post['looking_for'])		?	$post['looking_for']	:	false;
			if($looking_for){
				if(!empty($looking_for[0])){
					$post['looking_for'] = implode(',', $looking_for);
				}else{
					$post['looking_for'] = '';
				}
			}
			
						
			//for attributes
			$attributes	=	isset($post['my_attributes'])		?	$post['my_attributes']	:	false;
			if($attributes){
				$post['my_attributes'] = implode(',', $attributes);
			}else{
				$post['my_attributes'] = '';
			}
			
												
			//for languages
			$languages	=	isset($post['languages'])		?	$post['languages']	:	false;
			if($languages){
				$post['languages'] = implode(',', $languages);
			}else{
				$post['languages'] = '';
			}
			
			//set state, city, district value
			$post['state_id']	=	isset($post['state_id'])	?	$post['state_id']	:	0;
			$post['city_id']	=	isset($post['city_id'])	?	$post['city_id']	:	0;
			$post['district_id']	=	isset($post['district_id'])	?	$post['district_id']	:	0;
			
			$model->attributes = $post;
			$model->validate();
			
			if (!$model->hasErrors()) {
				$model->save();
				
				//save user search conditions
					$mysearch_conditions    =  	 array(
                        'location'  =>  array(
                            'country_id'  =>  array('=' => $post['country_id'])
						)
					);
                    //set filter for search conditions
                    if(!empty($post['sex_role'])){
                    	$filter	=	array();
                        switch ($post['sex_role']){
		                    case ProfileSettingsConst::SEXROLE_TOP:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );
		                    break;
		                    case ProfileSettingsConst::SEXROLE_BOTTOM:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;
		                    case ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;
		                    case ProfileSettingsConst::SEXROLE_TOP_VERSATILE:
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_VERSATILE), 
		                        );		                    	
		                    break;		                    	                     
		                    default: 
		                        $filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_BOTTOM), 
		                        );
		                    break;
	                	}         
	                	$mysearch_conditions['filter'] = $filter;           	
                    }
                    // end set filter for search conditions					
					$this->usercurrent->updateAll(array('search_conditions' => json_encode($mysearch_conditions)), "id={$this->usercurrent->id}");
				
			}
		}
		
		Yii::app()->end();
	}
	public function actionSaveAccountSettings() {

		$model_profile = YumProfile::model()->findByAttributes(array('user_id' => $this->usercurrent->id));
		$model_member = Member::model()->findByAttributes(array('id' => $this->usercurrent->id));		
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('YumProfile');

			$user_post = Yii::app()->request->getPost('Member');
			$error_msg	=	array();

			if(!YumEncrypt::validate_password($user_post['password'], $model_member->password, $model_member->salt)){
				$error_msg[]	=	Lang::t('settings', 'current password not correct!');
			}else{
				//check email exist and not exist
				if($post['email'] != $model_profile->email){
					if($model_profile->checkEmailExist($model_profile->email, $post['email'])){
						$error_msg[]	=	Lang::t('settings', 'Email already exists!');
					}
				}
				
				if(!empty($user_post['new_password'])){
					//change pass
					$model_member->salt = YumEncrypt::generateSalt();
					$model_member->password = YumEncrypt::encrypt($user_post['new_password'], $model_member->salt);
					$model_member->save();
				}
				$model_profile->email = $post['email'];
				$model_profile->save();	
			}
		}
		if(sizeof($error_msg)){
			$data	=	array(
					'status'	=>	'0',
					'msg'	=>	$error_msg
			);
		}else{
			$data	=	array(
					'status'	=>	'1'
			);			
		}
		echo json_encode($data);
		Yii::app()->end();
	}
	public function actionChangelookingfor(){
		$post 	= 	Yii::app()->request->getPost('YumProfile');
		
		$user	=	Yii::app()->user->data();
		$user->profile->online_lookingfor	=	$post['lookingfor_id'];
		if($user->profile->save()){
			echo '1';
		}else{
			echo '0';
		}
	}
	public function actiongetAccountSettings(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $this->usercurrent->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}
		$model_profile = YumProfile::model()->findByAttributes(array('user_id' => $this->usercurrent->id));

		$this->renderPartial('partial/account_settings', array(
            'model' => $model,
            'model_profile' => $model_profile,
		));		
	}	
	public function actionLanguageSettings(){
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $this->usercurrent->id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $this->usercurrent->id;
			$model->save();
		}
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
		    $usr_setting_post = Yii::app()->request->getPost('UsrProfileSettings');
			$usr_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => $this->usercurrent->id));
			$usr_setting->attributes = $usr_setting_post;
			$usr_setting->validate();
			if (!$usr_setting->hasErrors()) {
			    $usr_setting->save();
			    VLang::model()->setLangDefault();
    			$arr = array(
    			        'status' => '1',
    			);
			}else{
    			$arr = array(
    			        'status' => '0',
    			        'msg' => $usr_setting->errors
    			);
			}
			echo json_encode($arr);
			Yii::app()->end();
		}

		$this->renderPartial('partial/language_settings', array(
            'model' => $model,
		));		
	}	
	public function beforeAction($action) {
		if( parent::beforeAction($action) ) {
			$cs = Yii::app()->clientScript;			
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.bgiframe.min.js');
			Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/settings/common.js', CClientScript::POS_END);
			return true;
		}
		return false;
	}	
}