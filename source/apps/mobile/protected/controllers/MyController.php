<?php

/**
 * @author vinhnguyen
 * @desc My Controller
 */
class MyController extends MemberController {

    /**
     * profile user
     */
    public function actionView($alias = null) {
        $get = Yii::app()->cache->get('viewProfiles_'.$this->usercurrent->id);
        $arrProfiles = array();
        if(!empty($get)){
            $arrProfiles = json_decode($get);
        }
        if(!$this->usercurrent->isFriendOf($this->user->id) && !in_array($this->user->id, $arrProfiles) && !$this->user->isMe()){
            if(!empty($arrProfiles) && count($arrProfiles) >= Yii::app()->config->get('view_profile_in_day')){
                Yii::app()->user->setFlash('limitViewProfileInDay', true);
                $this->redirect($this->usercurrent->getUserFeedUrl());
            }else{
                if(!in_array($this->user->id, $arrProfiles)){
                    array_push($arrProfiles, $this->user->id);
                }
                Yii::app()->cache->set('viewProfiles_'.$this->usercurrent->id, json_encode($arrProfiles), 86400);
            }
        }
        //check settings - create new settings then it's empty
    	if($this->user->isMe()){
			$user_id	=	$this->usercurrent->id;
			$user		=	$this->usercurrent;
		}else{
			$user_id	=	$this->user->id;
			$user		=	$this->user;
		}
		
		//get avatar		
		
		$avatar =  Member::model()->getNoAvatar();
		if(!empty($user->avatar)){
			if(is_numeric($user->avatar)){
				$photo = Photo::model()->findByAttributes(array('id'=>$user->avatar, 'status'=>1));
				if(!empty($photo->name)){
					$avatar = Yii::app()->createUrl($photo->path .'/thumb160x160/'. $photo->name);
				}
			}else{
				$avatar = VAvatar::model()->urlAvatar($user->avatar);
			}
		}
		
		
		
    	$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user_id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $user_id;
			$model->save();
		}
		$city_in_cache = new CityonCache();
		$country_in_cache   =   new CountryonCache();
		$state_in_cache	=	new StateonCache();
		$district_in_cache	=	new DistrictonCache();
		
		//get location
		$location	=	array();
		if(!empty($this->user->profile_settings->district_id)){
			$district =	$district_in_cache->getDistrictInfo($this->user->profile_settings->district_id);
			$location[]	=	$district['name'];
		}
		if(!empty($this->user->profile_settings->city_id)){
			$city =	$city_in_cache->getCityInfo($this->user->profile_settings->city_id);
			$location[]	=	$city['name'];
		}
		if(!empty($this->user->profile_settings->state_id)){
			$state =	$state_in_cache->getStateInfo($this->user->profile_settings->state_id);
			$location[]	=	$state['name'];
		}
		if(!empty($this->user->profile_settings->country_id)){
			$country =	$country_in_cache->getCountryInfo($this->user->profile_settings->country_id);
			$location[]	=	$country['name'];
		}
		//general location
		if(sizeof($location)){
			$location	=	implode(', ', $location);
		}else{
			$location	=	ProfileSettingsConst::EMPTY_DATA;
		}
		
		$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => $user_id	));
		
		$current_location	=	array();
		if($model_location->current_district_id){
			$district =	$district_in_cache->getDistrictInfo($model_location->current_district_id);
			$current_location[]	=	$district['name'];
		}
		if($model_location->current_city_id){
			$city =	$city_in_cache->getCityInfo($model_location->current_city_id);
			$current_location[]	=	$city['name'];
		}
		if($model_location->current_state_id){
			$state =	$state_in_cache->getStateInfo($model_location->current_state_id);
			$current_location[]	=	$state['name'];
		}
		if($model_location->current_country_id){
			$country =	$country_in_cache->getCountryInfo($model_location->current_country_id);
			$current_location[]	=	$country['name'];
		}
		
		//general current location
		if(!empty($current_location)){
			$current_location	=	implode(', ', $current_location);
		}else{
			$current_location	=	'';
		}
				
		//get relationship
		$relationship	=	ProfileSettingsConst::getRelationshipLabel();
    	if($user->profile_settings->relationship != ProfileSettingsConst::RELATIONSHIP_PREFER_NOTTOSAY){
			$relationship	=	isset($relationship[$user->profile_settings->relationship])	?	$relationship[$user->profile_settings->relationship]	:	ProfileSettingsConst::EMPTY_DATA;
		}else{
			$relationship	=	Lang::t('settings', 'Relationship Status') . ": " . $relationship[$user->profile_settings->relationship];
		}		
		//get looking for
		$looking_for	=	ProfileSettingsConst::getLookingforLabel();
		$looking_for_value	=	ProfileSettingsConst::EMPTY_DATA;
		$looking_for_arr	=	array();
		
		if(!empty($this->user->profile_settings->looking_for)){
			$looking_for_choose	=	explode(',', $this->user->profile_settings->looking_for);
			if(!empty($looking_for_choose)){
				foreach($looking_for_choose AS $l):
				if(!empty($looking_for[$l])){
					$looking_for_arr[]	=	$looking_for[$l];
				}
				endforeach;
				$looking_for_value	=	sizeof($looking_for_arr)	?	implode(', ', $looking_for_arr) :	ProfileSettingsConst::EMPTY_DATA;
			}
		}
		
		//end get looking for
		
		//get language you understand
		$languages	=	ProfileSettingsConst::getLanguagesLabel();
		$languages_value	=	array();
		if(!empty($this->user->profile_settings->languages)){
			$languages_choose	=	explode(',', $this->user->profile_settings->languages);
			foreach($languages_choose AS $l):
			if(!empty($languages[$l])){
				$languages_value[]	=	$languages[$l];
			}
			endforeach;
		}
		$languages_value	=	sizeof($languages_value)	?	implode(', ', $languages_value) :	ProfileSettingsConst::EMPTY_DATA;
		//end get language you understand
		
		//get Occupation
		$occupation	=	ProfileSettingsConst::getOccupationLabel();
		$occupation_value	=	array();
		if(!empty($this->user->profile_settings->occupation)){
			$occupation_choose	=	explode(',', $this->user->profile_settings->occupation);
			foreach($occupation_choose AS $l):
			if(!empty($occupation[$l])){
				$occupation_value[]	=	$occupation[$l];
			}
			endforeach;
		}
		$occupation_value	=	sizeof($occupation_value)	?	implode(', ', $occupation_value) :	ProfileSettingsConst::EMPTY_DATA;
		//end get Occupation
		
		//get Attributes
		$attributes	=	ProfileSettingsConst::getAttributesLabel();
		$attributes_value	=	ProfileSettingsConst::EMPTY_DATA;
		$attributes_value_arr	=	array();
		if(!empty($this->user->profile_settings->my_attributes)){
			$attributes_choose	=	explode(',', $this->user->profile_settings->my_attributes);
			if(!empty($attributes_choose)){
				foreach($attributes_choose AS $l):
				if(!empty($attributes[$l])){
					$attributes_value_arr[]	=	$attributes[$l];
				}
				endforeach;
				$attributes_value	=	sizeof($attributes_value_arr)	?	implode(', ', $attributes_value_arr) :	ProfileSettingsConst::EMPTY_DATA;
			}
		}
		
		//end get Attributes
		
		//get my types
		$types	=	ProfileSettingsConst::getMyTypesLabel();
		$types_value	=	ProfileSettingsConst::EMPTY_DATA;
		$types_value_arr	=	array();
		if(!empty($this->user->profile_settings->my_types)){
			$types_choose	=	explode(',', $this->user->profile_settings->my_types);
			if(!empty($types_choose)){
				foreach($types_choose AS $l):
				if(!empty($types[$l])){
					$types_value_arr[]	=	$types[$l];
				}
				endforeach;
				$types_value	=	sizeof($types_value_arr)	?	implode(', ', $types_value_arr) :	ProfileSettingsConst::EMPTY_DATA;
			}
		}
		//end get types
		
		//get my Stuff
		$stuff	=	ProfileSettingsConst::getStuffLabel();
		$stuff_value	=	ProfileSettingsConst::EMPTY_DATA;
		$stuff_value_arr	=	array();
		if(!empty($this->user->profile_settings->into_stuff)){
			$stuff_choose	=	explode(',', $this->user->profile_settings->into_stuff);
			//find prefer not to say
			if(!empty($stuff_choose)){
				foreach($stuff_choose AS $l):
				if(!empty($stuff[$l])){
					$stuff_value_arr[]	=	$stuff[$l];
				}
				endforeach;
				$stuff_value	=	sizeof($stuff_value_arr)	?	implode(', ', $stuff_value_arr) :	ProfileSettingsConst::EMPTY_DATA;
			}
		}
		
		//end get Stuff
			
		//get Religion
		$religion	=	ProfileSettingsConst::getReligionLabel();
		$religion	=	isset($religion[$this->user->profile_settings->religion])	?	$religion[$this->user->profile_settings->religion]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//end get Religion
		$user_measurement	=	$this->usercurrent->profile_settings->measurement;

		//it's your
		$your_measurement	=	$this->user->profile_settings->measurement;
		
		if($user_measurement != $your_measurement){
			//convert to my unit
			if($user_measurement == UsrProfileSettings::VN_UNIT){
				$height	=	round($this->user->profile_settings->height * Yii::app()->params['feet_to_cm'], 2) . ' cm';
				$weight	=	round($this->user->profile_settings->weight * Yii::app()->params['pound_to_kg'], 2) . ' kg';
			}else{
				$height	=	round($this->user->profile_settings->height / Yii::app()->params['feet_to_cm'], 2) . ' ft';
				$weight	=	round($this->user->profile_settings->weight / Yii::app()->params['pound_to_kg'], 2) . ' lbs';
			}
		}else{
			if($user_measurement == UsrProfileSettings::VN_UNIT){
				$height	=	$this->user->profile_settings->height . ' cm';
				$weight	=	$this->user->profile_settings->weight . ' kg';
			}else{
				$height	=	$this->user->profile_settings->height . ' ft';
				$weight	=	$this->user->profile_settings->weight . ' lbs';
			}
		}
		if(empty($this->user->profile_settings->height)){
			$height	=	ProfileSettingsConst::EMPTY_DATA;
		}
		if(empty($this->user->profile_settings->weight)){
			$weight	=	ProfileSettingsConst::EMPTY_DATA;
		}		
		/*
		//get weight and height
		$user_unit = json_decode($this->usercurrent->profile_settings->persional_unit, true);
		$your_unit = json_decode($this->user->profile_settings->persional_unit, true);
			
		$unit_height = 0;
		$unit_weight = 0;
		$height	=	ProfileSettingsConst::EMPTY_DATA;
		$weight	=	ProfileSettingsConst::EMPTY_DATA;
		$your_unit_height = 0;
		$your_unit_weight = 0;
			
		if (isset($user_unit['unit_height'])) {
			$unit_height = $user_unit['unit_height'];
		}
		if (isset($user_unit['unit_weight'])) {
			$unit_weight = $user_unit['unit_weight'];
		}
		if (isset($your_unit['unit_height'])) {
			$your_unit_height = $your_unit['unit_height'];
		}
		if (isset($your_unit['unit_weight'])) {
			$your_unit_weight = $your_unit['unit_weight'];
		}
		if(!empty($this->user->profile_settings->height)):
			if ($unit_height == '1') {
				$height = round($this->user->profile_settings->height * Yii::app()->params['cm_to_m'], 2) . ' m';
			}else
			if ($unit_height == '2') {
				$height = round($this->user->profile_settings->height * Yii::app()->params['cm_to_feet'], 2) . ' feet';
			}else
			if ($unit_height == '3') {
				$height = round($this->user->profile_settings->height * Yii::app()->params['cm_to_inch'], 2) . ' inches';
			}else{
				$height = $this->user->profile_settings->height . ' cm';
			}
		endif;
		if(!empty($this->user->profile_settings->weight)):
		if ($unit_weight) {
			$weight = round($this->user->profile_settings->weight * Yii::app()->params['kg_to_pound'], 2) . ' pound';
		} else {
			$weight = $this->user->profile_settings->weight . ' kg';
		}
		
		if ($unit_weight == '1') {
			$weight = round($this->user->profile_settings->weight * Yii::app()->params['kg_to_pound'], 2) . ' pound';
		}else
		if ($unit_weight == '2') {
			$weight = round($this->user->profile_settings->weight * Yii::app()->params['g_to_kg'], 2) . ' gram';
		}else{
			$weight = $this->user->profile_settings->weight . ' kg';
		}		
		
		endif;
		//end get weight and height
		*/
		
		//get build
		$build	=	ProfileSettingsConst::getBuildLabel();
		$build	=	isset($build[$this->user->profile_settings->body])	?	$build[$this->user->profile_settings->body]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get body hair
		$body_hair	=	ProfileSettingsConst::getBodyHairLabel();
		$body_hair	=	isset($body_hair[$this->user->profile_settings->body_hair])	?	$body_hair[$this->user->profile_settings->body_hair]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Tattoos
		$tattoo	=	ProfileSettingsConst::getTattoosLabel();
		$tattoo	=	isset($tattoo[$this->user->profile_settings->tattoo])	?	$tattoo[$this->user->profile_settings->tattoo]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Piercings
		$piercing	=	ProfileSettingsConst::getPiercingsLabel();
		$piercing	=	isset($piercing[$this->user->profile_settings->piercings])	?	$piercing[$this->user->profile_settings->piercings]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Mannerism
		$mannerism	=	ProfileSettingsConst::getMannerismLabel();
		$mannerism	=	isset($mannerism[$this->user->profile_settings->mannerism])	?	$mannerism[$this->user->profile_settings->mannerism]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Safe Sex
		$safer_sex	=	ProfileSettingsConst::getSafeSexLabel();
		$safer_sex	=	isset($safer_sex[$this->user->profile_settings->safer_sex])	?	$safer_sex[$this->user->profile_settings->safer_sex]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get How Out Are You?
		$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
		$public_information	=	isset($public_information[$this->user->profile_settings->public_information])	?	$public_information[$this->user->profile_settings->public_information]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get live with
		$live_with	=	ProfileSettingsConst::getLiveWithLabel();
		$live_with	=	isset($live_with[$this->user->profile_settings->live_with])	?	$live_with[$this->user->profile_settings->live_with]	:	ProfileSettingsConst::EMPTY_DATA;
			
		
		//get basic info
		$basic_info	=	array();
		//for Ethnicity
		$ethnicity=	ProfileSettingsConst::getEthnicityLabel();
		if(!empty($this->user->profile_settings->ethnic_id)){
			$basic_info[]	=	$ethnicity[$this->user->profile_settings->ethnic_id];
		}
		//for Age
		$birthday_year   =   isset($this->user->profile_settings->birthday_year)  ?  $this->user->profile_settings->birthday_year    :   false ;
		if($birthday_year){
			$basic_info[]	=	(date('Y') - $birthday_year);
		}
		//for Sexuality
		$sexuality	=	ProfileSettingsConst::getSexualityLabel();
		if(!empty($this->user->profile_settings->sexuality)){
			$basic_info[]	=	$sexuality[$this->user->profile_settings->sexuality];
		}
		//for Role
		$roles	=	ProfileSettingsConst::getSexRoleLabel();
		if(!empty($this->user->profile_settings->sex_role)){
			$basic_info[]	=	$roles[$this->user->profile_settings->sex_role];
		}
		//for relationship
		if(!empty($relationship)){
			$basic_info[]	=	$relationship;
		}
		//for looking for
		if(!empty($looking_for_value)){
			// 			    $basic_info[]	=	$looking_for_value;
		}
		
		$basic_info_value	=	sizeof($basic_info)	?	implode(' | ', $basic_info) :	ProfileSettingsConst::EMPTY_DATA;
		
		//get online status
		$elasticsearch	=	new Elasticsearch();
		$online_data	=	$elasticsearch->checkOnlineStatus(array($user_id));
		$online_status	=	isset($online_data[$user_id]);
		
		//get friendship status
		$friendship  =   YumFriendship::model()->getFriendShipStatus($this->usercurrent->id, $user_id);
		$request_itsmy  =   false;
		$friendship_status  =   false;
		if($friendship){
			$friendship_status  =   $friendship->status;
			$request_itsmy  =   ($friendship->inviter_id == $this->usercurrent->id) ?   true : false;
		}			
			
		//end get basic info					
        $this->render('page/index', array(
        	'user'	=>	$user,
        	'current_user'	=>	$this->usercurrent,
        	'location'	=>	$location,
        	'user_id'	=>	$user_id,
        	'online_status'	=>	$online_status,
        	'friendship_status' => $friendship_status,
        	'request_itsmy'	=>	$request_itsmy,
        	'current_location'	=>	$current_location,
        	'avatar'	=>	$avatar,
        	'height'	=>	$height,
        	'weight'	=>	$weight,
        	'basic_info_value'	=>	$basic_info_value,
        	'body_hair'	=>	$body_hair,
        	'piercing'	=>	$piercing,
        	'safer_sex'	=>	$safer_sex,
        	'mannerism'	=>	$mannerism,
        	'tattoo'	=>	$tattoo,
        	'religion'	=>	$religion,
        	'attributes_value'	=>	$attributes_value,
        	'types_value'	=>	$types_value,
        	'stuff_value'	=>	$stuff_value,
        	'public_information'	=>	$public_information,
        	'live_with'	=>	$live_with,
        	'build'	=>	$build,
        	'relationship'	=>	$relationship,
        	'birthday_year'	=>	$birthday_year,
        	'looking_for_value'	=>	$looking_for_value,
        	'languages_value'	=>	$languages_value,
        	'occupation_value'	=>	$occupation_value,
            ));
    }
    
    public function actionPhotosSetAvatar() {
        	$params = CParams::load ();

        Yii::import ( "backend.extensions.plunUploader.upload");
        $uploader = new upload($params->params->uploads->upload_method);
        $uploader->allowedExtensions = array (
        		'jpg',
        		'jpeg',
        		'png'
        );
        $uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
        $thumb160x160 = $params->params->uploads->photo->thumb160x160;
                
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/avatar.js?t=' .time(), CClientScript::POS_END);
         
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && isset($_POST['photo_id'])){
            $photo = Photo::model()->findByPk($_POST['photo_id']);
            if(isset($photo)){
            	
            	/*
                $thumb160x160 = $params->params->uploads->photo->thumb160x160;
                $photo_origin = Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path . DS. 'origin'.  DS .$photo->name;
                $save_to = Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .DS. 'thumb160x160';
                VHelper::checkDir ( $save_to );
                */
            	
                //load images resource
                $src = DS . $photo->path .'/origin/'. $photo->name;
                $thumb160x160_folder	=	DS . $photo->path .'/thumb160x160/';
                
                $uploader->loadImageResource($src);
                $uploader->checkDir($thumb160x160_folder);
                $uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
                $uploader->crop($thumb160x160->w, $thumb160x160->h, 'top');
                $uploader->saveImg($thumb160x160_folder . $photo->name);
                $uploader->detroyResource();
                                
                /*
                list ( $width, $height ) = getimagesize ( $photo_origin );
                if ($width > $height) {
                    $resize_type = Image::HEIGHT;
                } else {
                    $resize_type = Image::WIDTH;
                }
                Yii::app()->image->load($photo_origin)->resize($thumb160x160->w, $thumb160x160->h, $resize_type)->crop ( $thumb160x160->w, $thumb160x160->h, 'top' )->sharpen(20)->save($save_to . DS. $photo->name);
                */
                
                $this->usercurrent->avatar = $photo->id;
                if($this->usercurrent->save()){
                    //update index search
                    $Elasticsearch = new Elasticsearch();
                    $Elasticsearch->updateSearchIndexUser($this->usercurrent->id);
                    echo 1;
                } else {
                    echo 0;
                }
                Yii::app()->end();
            }
        }
    	
        $public_photos = Photo::model ()->getPhotoByType($this->usercurrent->id, null, Photo::PUBLIC_PHOTO , $params->params->uploads->photo->limit_display->public_thumbnail);
        
        
        $this->render('page/photos-setavatar', array(
            'public_photos' => $public_photos,                
        ));
    }

    public function actionUploadAvatar() {
        $params = CParams::load();
    	Yii::import ( "backend.extensions.plunUploader.upload");
    	$uploader = new upload($params->params->uploads->upload_method);
    	$uploader->allowedExtensions = array (
    			'jpg',
    			'jpeg',
    			'png'
    	);
    	$uploader->inputName	=	'image';
    	$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes    	
    	
        //$imageFile = CUploadedFile::getInstanceByName('image');
                
        $thumb160x160 = $params->params->uploads->photo->thumb160x160;
        $thumb160x160_folder = $uploader->setPath ( $thumb160x160->p , false );
        
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && !isset($_POST['photo_id'])){
        	
        	list ( $width, $height ) = getimagesize ( $_FILES[$uploader->inputName]['tmp_name'] );
        	if ($width < 200 || $height < 200) {
        		echo Lang::t('photo', 'Please choose an image with minimum size is {limit_w}x{limit_h}px.', array('{limit_w}' => 200, '{limit_h}' => 200));
        		Yii::app()->end();
        	}
        	        	
            $limit_public_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PUBLIC_PHOTO));
            if($limit_public_upload >= $params->params->uploads->photo->limit_upload->public){
            	echo Lang::t('general', 'Public photo is full, can be removed for upload new avatar');
            	Yii::app()->end();
            }
            
            $photo  = VAvatar::model()->autoResizeAvatar();  
            $v	=	Yii::app()->request->getParam('v', false);
            $view = 'upload-avatar';
            if(!empty($v)){
                $view = $v;
            }
            $this->renderPartial('partial/'.$view,array('photo'=>$photo), false, true);
            Yii::app()->end();
        }
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && isset($_POST['photo_id'])){
            $photo = Photo::model()->findByPk($_POST['photo_id']);
        	$photo->status = 1;
        	$photo->save();
        	$this->usercurrent->avatar = $photo->id;
        	$this->usercurrent->register_step = YumUser::REGISTER_STEP_COMPLETE;
        	$this->usercurrent->save();
        	$Elasticsearch = new Elasticsearch();
        	$Elasticsearch->updateSearchIndexUser($this->usercurrent->id);
        }
        $this->renderPartial('partial/upload-avatar',array('photo'=>$photo), false, true);
        Yii::app()->end();
    }
    
	public function actionQuicksearch(){
		$q	=	Yii::app()->request->getParam('q', false);
		
		$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if(isset($profile_location->current_country_id)){
			$country_id	=	$profile_location->current_country_id;
		}
		if(!$country_id){
			$current_country = $country_in_cache->getCurrentCountry();
			$country_id	=	$current_country['id'];
		}
		$country_in_cache = new CountryonCache();
		$state_in_cache = new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache = new DistrictonCache();
		$list_country = $country_in_cache->getListCountry();
		
		//get list state default by above first country
		$list_state	=	$state_in_cache->getlistStateinCountry($country_id);
		if($list_state){
			$first_state = current($list_state);
			$state_id	 =	    $first_state['id'];
			//get list city default by above first country
			$list_city = $city_in_cache->getlistCityinState($state_id);
			if ($list_city) {
				//get  first city information
				$first_city = current($list_city);
				$city_id	=	$first_city['id'];
		
				//get list district default by above first country
				$list_district = $district_in_cache->getlistDistrictinCity($city_id);
				if (!$list_district) {
					$list_district = array();
				}
			} else {
				$list_city = array();
			}
		}
				
		$this->render('page/search', array(
			'q'	=>	$q,
			'list_country' => $list_country,
			'country_id'	=>	$country_id,
			'list_city' => $list_city,
			'list_state'	=>	$list_state,
			'list_district' => $list_district,
			'city_id'	=>	$city_id,
			'state_id'	=>	$state_id				
		));
		
	}
	
	public function actionGetUsersSuggest() {
		/*
	    $q = Yii::app()->request->getParam('q');
	    $user_id = Yii::app()->user->id;
	    if(!empty($q) && $user_id){
	        $sql = "SELECT tbl.user_id FROM (
                    SELECT inviter_id AS user_id  FROM usr_friendship WHERE (inviter_id = $user_id OR friend_id = $user_id) AND STATUS = 2
                    UNION ALL
                    SELECT friend_id AS user_id  FROM usr_friendship WHERE (inviter_id = $user_id OR friend_id = $user_id) AND STATUS = 2
                    ) tbl
                    WHERE tbl.user_id != $user_id
                    GROUP BY tbl.user_id";
    	    $cri = new CDbCriteria();
    	    $cri->addCondition("username LIKE :key AND id IN ($sql)");
    	    $cri->params = array(':key'=>"%$q%");
    	    $parents = Member::model()->findAll($cri);
    	    $results = array();
    	    foreach($parents as $p) {
    	        $results[] = array(
    	                'id' => $p->username,
    	                'text' => $p->username
    	        );
    	    }
    	    echo CJSON::encode($results);
	    }
	    Yii::app()->end();
	    */
	    $keyword	=	Yii::app()->request->getParam('q', false);
	    $offset = Yii::app()->request->getParam('offset', 0);
	    	
	    //set keyword search
	    $search_conditions = array(
	    		'keyword' => strtolower($keyword),
	    		'country_id'	=>	0
	    );
	    $my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
	    //remove myself
	    $user_id_except = array(Yii::app()->user->id);
	    	
	    $elasticsearch	=	new Elasticsearch();
	    $sort_script	=	"doc['username'].value.length()";
	    $elasticsearch->setSortRules($sort_script, 'asc');
	    $data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, 5);
	    	
	    $dbrows	=	array();
	    foreach($data_search['fulldata'] AS $row){
	    	$row	=	$row['_source'];
	    	$dbrows[]	=	array(
	    			'id' => $row['username'],
	    			'text' => $row['username']
	    	);
	    }
	    echo CJSON::encode($dbrows);
	    Yii::app()->end();	    
	}
}