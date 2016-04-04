<?php

class NewsFeedController extends MemberController {
	public function actionExplode(){
		
		/* White party */
		$userProfilr = UsrProfileSettings::model()->findByAttributes(array('user_id' => $this->usercurrent->id));
		if($userProfilr->country_id == 168) {
		
			$events = new Events();
			$whitePartyEvent = $events->find('title=:title AND enabled=1', array(':title'=>'White Party Manila'));
		
			if($whitePartyEvent) {
				$gifCodeModel = new Giftcode();
				$gifCode = $gifCodeModel->find('event_id=:event_id AND user_id=:user_id', array(':event_id'=>$whitePartyEvent->id, ':user_id'=>$this->usercurrent->id));
				 
				$avalableGifCode = $gifCodeModel->find('event_id=:event_id AND status=0', array(':event_id'=>$whitePartyEvent->id));
				 
				if(!$gifCode && $avalableGifCode) {
					$message = $whitePartyEvent->description;
					$eventUrl = Yii::app()->createUrl('event/WhitePartyManila');
						
					if($this->user->createtime > $whitePartyEvent->start && $this->user->createtime < $whitePartyEvent->end) {
						Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/event/event.js', CClientScript::POS_BEGIN);
						Yii::app()->clientScript->registerScript('whiteparty', "WhitePartyManila.init('$message','$eventUrl');", CClientScript::POS_END);
					}
				}
			}
		}
		/* White party */
		
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
		
		$measurement	=	!empty($this->usercurrent->profile_settings->measurement)	?	$this->usercurrent->profile_settings->measurement	:	false;
		
		$this->render('page/explode', array(
			'list_country' => $list_country,
			'country_id'	=>	$country_id,
			'measurement'	=>	$measurement,
			'list_city' => $list_city,
			'list_state'	=>	$list_state,
			'list_district' => $list_district,
			'city_id'	=>	$city_id,
			'state_id'	=>	$state_id				
		));
	}	
    /**
     * feed
     */
    public function actionFeed() {
        $model = new Activity();
        $model->user_id = $this->user->id;
        $model->is_feed = true;
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        
        $pindex = 0;
        if (isset($_GET['pindex']))
            $pindex = intval($_GET['pindex']);
        
        $limit = Yii::app()->params->news_feed['limit_display'];
        
        $activities = $model->searchActivitiesByUserPagging($limit, $pindex);
        $show_more = ($activities['total'] > $limit) ? true : false;
        
        $this->render('page/feed', array(
                'city_info' => $city_in_cache->getListCity(),
                'country_info' => $country_in_cache->getListCountry(),
                'sex_roles_title' => Yii::app()->params['constants']['sex_roles'],
                'show_more' => $show_more,
                'limit' => $limit,
                'activities' => $activities));
    }
    /**
     * feed real time
     */
    public function actionFeedUpdate() {
        $ids = Yii::app()->request->getPost('ids');
        if(!empty($ids)){
        	$limit = Yii::app()->params->news_feed['limit_display'];
            $criteria=new CDbCriteria;
            $criteria->addCondition("id IN ($ids)");
            $criteria->order = 'timestamp DESC';
            $criteria->limit = $limit;
            $activities = Activity::model()->findAll($criteria);
            $this->renderPartial('partial/feed-update', array(
                    'activities' => $activities));
        }
    }
    /**
     * profile user
     */
    public function actionIndex() {
        $model = new Activity(); 
        $model->user_id = $this->user->id;
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $state_in_cache	=	new StateonCache();
        $district_in_cache	=	new DistrictonCache();
        
        $pindex = 0;
        if (isset($_GET['pindex']))
            $pindex = intval($_GET['pindex']);

        $limit = Yii::app()->params->news_feed['limit_display'];

        $activities = $model->searchActivitiesByUserPagging($limit, $pindex);
        $show_more = ($activities['total'] > $limit) ? true : false;
        
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
		
		//get relationship
		$relationship	=	ProfileSettingsConst::getRelationshipLabel();
		$relationship	=	isset($relationship[$this->user->profile_settings->relationship])	?	$relationship[$this->user->profile_settings->relationship]	:	ProfileSettingsConst::EMPTY_DATA;
		
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
				if ($unit_height) {
				    $height = round($this->user->profile_settings->height * Yii::app()->params['cm_to_m'], 2) . ' m';
				} else {
				    $height = $this->user->profile_settings->height . ' cm';
				}
			endif;
			if(!empty($this->user->profile_settings->weight)):
				if ($unit_weight) {
				    $weight = round($this->user->profile_settings->weight * Yii::app()->params['kg_to_pound'], 2) . ' pound';
				} else {
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
		
		//get shoesize
		$shoesize	=	ProfileSettingsConst::getShoeSizeLabel();
		$shoesize	=	isset($shoesize[$this->user->profile_settings->dick_size])	?	$shoesize[$this->user->profile_settings->dick_size]	:	ProfileSettingsConst::EMPTY_DATA;

		//get Mannerism
		$mannerism	=	ProfileSettingsConst::getMannerismLabel();
		$mannerism	=	isset($mannerism[$this->user->profile_settings->mannerism])	?	$mannerism[$this->user->profile_settings->mannerism]	:	ProfileSettingsConst::EMPTY_DATA;
			
		//get cut
		$cut	=	ProfileSettingsConst::getCutLabel();
		$cut	=	isset($cut[$this->user->profile_settings->cut])	?	$cut[$this->user->profile_settings->cut]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Safe Sex
		$safer_sex	=	ProfileSettingsConst::getSafeSexLabel();
		$safer_sex	=	isset($safer_sex[$this->user->profile_settings->safer_sex])	?	$safer_sex[$this->user->profile_settings->safer_sex]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get How Out Are You?
		$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
		$public_information	=	isset($public_information[$this->user->profile_settings->public_information])	?	$public_information[$this->user->profile_settings->public_information]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get live with
		$live_with	=	ProfileSettingsConst::getLiveWithLabel();
		$live_with	=	isset($live_with[$this->user->profile_settings->live_with])	?	$live_with[$this->user->profile_settings->live_with]	:	ProfileSettingsConst::EMPTY_DATA;
							
		//get hobbies
		$hobbies	=	ProfileSettingsConst::EMPTY_DATA;
		if(!empty($this->user->profile_settings->hobbies)){
			$hobbies	=	json_decode($this->user->profile_settings->hobbies, true);
			$hobbies	=	implode(', ', $hobbies);
		}
		
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
						
			$basic_info_value	=	sizeof($basic_info)	?	implode(' <label></label> ', $basic_info) :	ProfileSettingsConst::EMPTY_DATA;
		//end get basic info
		

				
        $this->renderPartial('partial/index', array(
            'show_more' => $show_more,
        	'location'	=>	$location,
        	'height'	=>	$height,
        	'weight'	=>	$weight,
        	'basic_info_value'	=>	$basic_info_value,
        	'body_hair'	=>	$body_hair,
        	'piercing'	=>	$piercing,
        	'safer_sex'	=>	$safer_sex,
        	'cut'	=>	$cut,
        	'mannerism'	=>	$mannerism,
        	'tattoo'	=>	$tattoo,
        	'religion'	=>	$religion,
        	'hobbies'	=>	$hobbies,
        	'attributes_value'	=>	$attributes_value,
        	'types_value'	=>	$types_value,
        	'stuff_value'	=>	$stuff_value,
        	'public_information'	=>	$public_information,
        	'live_with'	=>	$live_with,
        	'shoesize'	=>	$shoesize,
        	'build'	=>	$build,
        	'relationship'	=>	$relationship,
        	'birthday_year'	=>	$birthday_year,
        	'looking_for_value'	=>	$looking_for_value,
        	'languages_value'	=>	$languages_value,
        	'occupation_value'	=>	$occupation_value,
            'limit' => $limit,
            'activities' => $activities));
    }
    /**
     * show more
     */
    public function actionViewMore(){
        $is_feed = Yii::app()->request->getParam('is_feed');
        $model = new Activity();
        $model->user_id = $this->user->id;
        $model->is_feed = $is_feed;
        
        $limit = Yii::app()->params->news_feed['limit_display'];
        $offset = Yii::app()->request->getPost('offset', 0);
        
        $activities = $model->searchActivitiesByUserPagging($limit, $offset);
        
        $show_more = ($activities['total'] > ($offset + sizeof($activities['data']))) ? true : false;

        
        $this->renderPartial('partial/newsfeed_viewmore', array(
            'user' => $this->user,
            'usercurrent' => $this->usercurrent,
            'show_more' => $show_more,
            'offset'   =>  $offset,
            'limit' => $limit,
            'activities' => $activities));      
        exit;
    }
    /**
     * post status
     */
    public function actionPostWall() {
        if (Yii::app()->user->isGuest) {
            return false;
        }
        $log = null;
        if ($_POST['WallStatus']) {
            $data = $_POST['WallStatus'];
            $log = Activity::model()->log(
                            Activity::LOG_POST_WALL, array(
                        '{userfrom}' => $this->usercurrent->username,
                        '{userto}' => $this->user->username,
                        '{message}' => $data['status'],
                            ), $this->user->id, $this->user->username, null
            );
        }

        $params = array('log' => $log, 'user_post' => null, 'user_receive' => null, 'message' => ProfileSettingsConst::EMPTY_DATA);

        if ($log) {
            $pprams = json_decode($log->params, true);
            $params['user_post'] = Member::getUserByUsername($pprams['{userfrom}']);
            $params['user_receive'] = Member::getUserByUsername($pprams['{userto}']);
            $params['message'] = $pprams['{message}'];
        }
        
        $this->renderPartial('partial/post-wall', array('data'=>$log));
        Yii::app()->end();
    }
    /**
     * like newsfeed 
     */
    public function actionLike($oid, $type) {
        $arr = Like::callLike(Util::decrypt($oid), $type, $this->usercurrent->id);
        if (!empty($arr)) {
            $activity = Activity::model()->findByPk(Util::decrypt($oid));
            $notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_LIKE_WALL));
            $notification_data = array(
                    'params' => array('{userfrom}'=>$this->usercurrent->username, '{userto}'=>$activity->owner->username),
                    'activity_id' => $activity->id,
                    'message' => Yum::t('{userfrom} {like} {userto} {status}'),
            );
                        
            $notiExist = XNotifications::model()->findByAttributes(array(
                    'userid'=>$this->usercurrent->id,
                    'ownerid'=>$activity->owner_id,
                    'owner_type'=>'user',
                    'notification_type'=>$notifiType->id,
                    'notification_data'=>addslashes(json_encode($notification_data)),
            ));
            if(empty($notiExist)){
            	$data = array(
            			'userid'=>$this->usercurrent->id,
            			'ownerid'=>$activity->owner_id,
            			'owner_type'=>'user',
            			'notification_type'=>$notifiType->id,
            			'notification_data'=>addslashes(json_encode($notification_data)),
            			'timestamp'=>time(),
            			'last_read'=>0,
            	);
                XNotifications::model()->saveNotifications($data);
            }else{
                $notiExist->timestamp = time();
                $notiExist->last_read = 0;
                $notiExist->save();
            }
        }
        echo htmlspecialchars(json_encode($arr), ENT_NOQUOTES);
        Yii::app()->end();
    }
    /**
     * comment newsfeed
     */
    public function actionCommentActivity() {
        $model = new Comment();

        if (isset($_POST['Comment'])) {
            $data = $_POST['Comment'];
            $item = json_decode(htmlspecialchars_decode($data['item']), true);
            $item['action'] = intval(Util::decrypt($item['action']));
            $item['object_id'] = intval(Util::decrypt($item['object_id']));
            $obj = Activity::model()->findByPk($item['object_id']);
                        
            if ($obj) {
                $params = array('item' => null);
                $model->attributes = $data;
                $model->created_date = time();
                $model->created_by = $this->usercurrent->id;
                $model->approved = 1;
                $model->type_id = Comment::COMMENT_ACTIVITY;
                $model->item_id = $obj->id;

                if ($model->save()) {
                    $sta = ActivityLogStats::model()->findByAttributes(array('log_id' => $obj->id));
                    if ($sta) {
                        $sta->comment_count++;
                        $sta->save();
                    } else {
                        $sta = new ActivityLogStats();
                        $sta->comment_count = 1;
                        $sta->log_id = $obj->id;
                        $sta->save();
                    }
                    $params['item'] = $model;
                    $this->renderPartial("partial/comment-item", $params);
                    
                    /**save alerts**/
                    $notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_COMMENT_WALL));
                    $notification_data = array(
                            'params' => array('{userfrom}'=>$this->usercurrent->username, '{userto}'=>$obj->owner->username),
                            'activity_id' => $obj->id,
                            'message' => Yum::t('{userfrom} commented on {userto} {status}'),
                    );
                    $notiExist = XNotifications::model()->findByAttributes(array(
                    		'userid'=>$this->usercurrent->id,
                    		'ownerid'=>$obj->owner->id,
                    		'owner_type'=>'user',
                    		'notification_type'=>$notifiType->id,
                    		'notification_data'=>addslashes(json_encode($notification_data)),
                    ));
                    if(empty($notiExist)){
	                    $data = array(
                            'userid'=>$this->usercurrent->id,
                            'ownerid'=>$obj->owner->id,
                            'owner_type'=>'user',
                            'notification_type'=>$notifiType->id,
                            'notification_data'=>addslashes(json_encode($notification_data)),
                            'timestamp'=>time(),
                            'last_read'=>0,
	                    );                    
                    	XNotifications::model()->saveNotifications($data);
                    }else{
                    	$notiExist->timestamp = time();
                    	$notiExist->last_read = 0;
                    	$notiExist->save();
                    }
                    /** send notify to user comment status **/
                    $lstComment = Comment::model()->getUserComment($obj->id, Comment::COMMENT_ACTIVITY);
                    if(!empty($lstComment)){
                    	foreach ($lstComment as $cmt){
                    		if($cmt->author->id != $this->usercurrent->id){
                    			$notification_data = array(
                    					'params' => array('{userfrom}'=>$this->usercurrent->username, '{userto}'=>$cmt->author->username),
                    					'activity_id' => $obj->id,
                    					'message' => Yum::t('{userfrom} commented on {userto} {status}'),
                    			);
                    			 
                    			$notiExist = XNotifications::model()->findByAttributes(array(
                    					'userid'=>$this->usercurrent->id,
                    					'ownerid'=>$cmt->author->id,
                    					'owner_type'=>'user',
                    					'notification_type'=>$notifiType->id,
                    					'notification_data'=>addslashes(json_encode($notification_data)),
                    			));
                    			if(empty($notiExist)){
	                    			$data = array(
                    					'userid'=>$this->usercurrent->id,
                    					'ownerid'=>$cmt->author->id,
                    					'owner_type'=>'user',
                    					'notification_type'=>$notifiType->id,
                    					'notification_data'=>addslashes(json_encode($notification_data)),
                    					'timestamp'=>time(),
                    					'last_read'=>0,
	                    			);
                    				XNotifications::model()->saveNotifications($data);
                    			}else{
                    				$notiExist->timestamp = time();
                    				$notiExist->last_read = 0;
                    				$notiExist->save();
                    			}
                    		}
                    	}
                    }
                    /**end**/
                    
                }
            }
            Yii::app()->end();
        }
        Yii::app()->end();
    }
    /**
     * get user liked 
     */
    public function actionGetUserLiked($oid, $type) {
        if(!empty($oid) && !empty($type)){
            $criteria=new CDbCriteria;
            $criteria->limit = 5;
            $objs = Like::model()->findAllByAttributes(array('item_id' => Util::decrypt($oid), 'type_id' => $type), $criteria);
            $total = Like::model()->countByAttributes(array('item_id' => Util::decrypt($oid), 'type_id' => $type));
            $this->renderPartial('partial/user-liked', array(
                'objs'=>$objs,
                'total'=>$total,
                'oid'=>$oid,
            ));
        }
        Yii::app()->end();
    }
    /**
     * show more user liked
     */
    public function actionMoreUserLiked($oid, $type) {
        if(!empty($oid) && !empty($type)){
            $objs = Like::model()->findAllByAttributes(array('item_id' => Util::decrypt($oid), 'type_id' => $type));
            $this->renderPartial('partial/user-liked-more', array(
                    'objs'=>$objs,
            ));
        }
        Yii::app()->end();
    }
    /**
     * status detail
     */
    public function actionStatusDetail($actid) {
        $activity = Activity::model()->findByPk(Util::decrypt($actid));
        $this->render('partial/status_detail', array(
                'data' => $activity,
        ));
        Yii::app()->end();
    }
    
    public function actionCommentsPrevious() {
        $page = Yii::app()->request->getParam('page', 1);
        $params['object_id'] = Yii::app()->request->getParam('object_id');        
        $params['data'] = Comment::model()->getComments($params['object_id'], Comment::COMMENT_ACTIVITY, $page);
        $this->renderPartial("//newsFeed/partial/previous-comment", $params);
        Yii::app()->end();
    }

}