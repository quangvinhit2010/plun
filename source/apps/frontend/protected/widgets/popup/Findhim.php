<?php

class Findhim extends CWidget {

    public function run() {
        $list_country = array();
        $list_state = array();
        $list_city = array();
        $list_district = array();
        $city_id	=	0;
        $state_id	=	0;
        
        //$search_conditions_string = $this->controller->usercurrent->search_conditions;
        //$mysearch_conditions = json_decode($search_conditions_string, true);
        
        //set selected country and city
        
        $profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
        
        
        $country_id	=	isset($this->controller->usercurrent->profile_settings->country_id)	?	$this->controller->usercurrent->profile_settings->country_id	:	false;
        $measurement	=	isset($this->controller->usercurrent->profile_settings->measurement)	?	$this->controller->usercurrent->profile_settings->measurement	:	false;
        
        if(isset($profile_location->current_country_id)){
        	$country_id	=	$profile_location->current_country_id;
        }

        try {
            $country_in_cache = new CountryonCache();
            $state_in_cache = new StateonCache();
            $city_in_cache = new CityonCache();
            $district_in_cache = new DistrictonCache();
            
            if(!$country_id){
	        	$current_country = $country_in_cache->getCurrentCountry();
	        	$country_id	=	$current_country['id'];
	        }
            //get list all country
            $list_country = $country_in_cache->getListCountry();
            if ($list_country) {
            	
                //get first country information
                $first_country = current($list_country);
                $country_id = $country_id	?	$country_id	:	$first_country['id'];	
			
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
            } else {
                $list_country = array();
            }
        } catch (Exception $e) {
            
        }
        

                
        if (!empty($this->controller->user) &&  in_array($this->controller->id, array('my', 'messages', 'alerts', 'settings', 'friend', 'search'))) {
            $this->render('findhim', array(
                'list_country' => $list_country,
                'list_city' => $list_city,
            	'list_state'	=>	$list_state,
                'list_district' => $list_district,
            	'measurement'	=>	$measurement,
            	'country_id'	=>	$country_id,
            	'city_id'	=>	$city_id,
            	'state_id'	=>	$state_id
            ));
        }
    }

}