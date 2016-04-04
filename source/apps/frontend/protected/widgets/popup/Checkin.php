<?php

class Checkin extends CWidget {
	public function run() {
		$user =  Yii::app()->user->data();
		if($user){
			
			$model_location 		= UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
			
			if($model_location){
				$country_id		=	$model_location->current_country_id;
				$state_id		=	$model_location->current_state_id;
				$city_id		=	$model_location->current_city_id;
				$district_id	=	$model_location->current_district_id;
			}else{
				$model_profilesetting 	= UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
				
				$country_id		=	$model_profilesetting->country_id;
				$state_id		=	$model_profilesetting->state_id;
				$city_id		=	$model_profilesetting->city_id;
				$district_id	=	$model_profilesetting->district_id;
			}
	
			try {
				 
				$list_city	=	array();
				$list_state	=	array();
				$list_district	=	array();
				 
				$country_in_cache = new CountryonCache();
				$state_in_cache	=	new StateonCache();
				$city_in_cache = new CityonCache();
				$district_in_cache = new DistrictonCache();
	
				if(!$country_id){
					$current_country = $country_in_cache->getCurrentCountry();
					$country_id	=	$current_country['id'];
				}
				//get list all country
				$list_country = $country_in_cache->getListCountry();
				if ($list_country) {
					if (!$country_id) {
						$current_country = $country_in_cache->getCurrentCountry();
						$country_id	=	$current_country['id'];
					}
					//get state list
					$list_state	=	$state_in_cache->getlistStateinCountry($country_id);
					if($list_state){
						//get city list
						$list_city	=	$city_in_cache->getlistCityinState($state_id);
						if($list_city){
							//get district list
							$list_district	=	$district_in_cache->getlistDistrictinCity($city_id);
							if(!$list_district || !$district_id){
								$list_district	=	array();
							}
						}else{
							$list_city	=	array();
						}
					}else{
						$list_state	=	array();
					}
	
				}
			} catch (Exception $e) {
	
			}
			
			//get venues
			//check location
			/*
			if(!$model_location){
				$profile_location_model     =   new UsrProfileLocation();
				$profile_location_model->user_id       =   $user->id;
					
				$current_country = $country_in_cache->getCurrentCountry();
				if(isset($current_country['id'])){
					$profile_location_model->current_country_id	=	$current_country['id'];
				}
				$profile_location_model->save();
			}
			*/
			$venues	=	new venues();
			//check venues data
			$venues_data	=	array();
			if($model_location){
				$venues_data	=	$venues->load($model_location->venue_id);
			}
			
			$this->render('checkin', array(
						'profile_location'	=>	$model_location,
						'list_country'	=>	$list_country,
						'list_city'	=>	$list_city,
						'list_state'	=>	$list_state,
						'venues_data'	=>	isset($venues_data['hits']['hits'][0])	?	$venues_data['hits']['hits'][0]['_source']	:	false,
						'list_district'	=>	$list_district,
						'country_in_cache'	=>	$country_in_cache,
						'country_id'		=>	$country_id,
						'state_id'		=>	$state_id,
						'city_id'		=>	$city_id,
						'district_id'		=>	$district_id,
						'city_in_cache'	=>	$city_in_cache,
						'district_in_cache'	=>	$district_in_cache,
						'state_in_cache'	=>	$state_in_cache,
						'profile_settings'	=>	$user->profile_settings
				));
				
		}
		return false;
	}	
}