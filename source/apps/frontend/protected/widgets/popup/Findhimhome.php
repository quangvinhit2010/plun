<?php

class Findhimhome extends CWidget {

    public function run() {
        $list_country = array();
        $list_state = array();
        $list_city = array();
        $list_district = array();
        $city_id	=	0;
        $state_id	=	0;
               
        
        $country_id	=	false;
        $measurement	=	UsrProfileSettings::VN_UNIT;
        
        try {
            $country_in_cache = new CountryonCache();
            $state_in_cache = new StateonCache();
            $city_in_cache = new CityonCache();
            $district_in_cache = new DistrictonCache();
            
	        $current_country = $country_in_cache->getCurrentCountry();
	        if(isset($current_country['id'])){
	        	$country_id	=	!empty($current_country['id'])	?	$current_country['id']	:	HomeController::COUNTRY_ID_DEFAULT;
	        }else{
	        	$country_id	=	HomeController::COUNTRY_ID_DEFAULT;
	        }
	        
            //get list all country
            $list_country = $country_in_cache->getListCountry();
            if ($list_country) {
            	
                //get first country information
                $first_country = current($list_country);
			
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
        
        $this->render('findhimhome', array(
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