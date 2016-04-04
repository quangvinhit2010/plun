<?php

class Checkin extends CWidget {
	public function run() {
		$user =  Yii::app()->user->data();
		if($user){
			if($user->isMe()){
				$usercurrent = Yii::app()->user->data();
			}else{
				$usercurrent = $this->controller->user;
			}
			if($usercurrent->profile_location){
				$country_id		=	$usercurrent->profile_location->current_country_id;
				$state_id		=	$usercurrent->profile_location->current_state_id;
				$city_id		=	$usercurrent->profile_location->current_city_id;
				$district_id	=	$usercurrent->profile_location->current_district_id;
			}else{
				$country_id		=	$usercurrent->profile_settings->country_id;
				$state_id		=	$usercurrent->profile_settings->state_id;
				$city_id		=	$usercurrent->profile_settings->city_id;
				$district_id	=	$usercurrent->profile_settings->district_id;
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
			$venues	=	new venues();
	
			$venues_data	=	$venues->load($user->profile_location->venue_id);
			
			$this->render('checkin', array(
						'profile_location'	=>	$user->profile_location,
						'list_country'	=>	$list_country,
						'list_city'	=>	$list_city,
						'list_state'	=>	$list_state,
						'venues_data'	=>	isset($venues_data['hits']['hits'][0])	?	$venues_data['hits']['hits'][0]['_source']	:	false,
						'list_district'	=>	$list_district,
						'usercurrent'	=>	$usercurrent,
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