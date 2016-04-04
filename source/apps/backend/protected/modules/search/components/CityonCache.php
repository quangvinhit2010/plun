<?php

class CityonCache {

    public $redis = null;
    public $key_citylistall = '';
    public $key_cityofstate = '';
    public $key_cityofcountry = '';

    public function __construct($key_citylistall = 'list_city_all', $key_cityofcountry = 'listcity_in_country', $key_cityofstate = 'listcity_in_state') {
        $this->redis = Yii::app()->cache;
        $this->key_citylistall = $key_citylistall;
        $this->key_cityofcountry = $key_cityofcountry;
        $this->key_cityofstate = $key_cityofstate;
    }

    public function addCityList($list_city_info) {
        $city_list_ofcountry = $this->redis->get($this->key_cityofcountry);
        $city_list_ofstate = $this->redis->get($this->key_cityofstate);
        $city_list_all = $this->redis->get($this->key_citylistall);
        
        if ($city_list_all) {
            //delete old list
            $this->redis->delete($this->key_citylistall);
        }
        if ($city_list_ofcountry) {
            //delete old list
            $this->redis->delete($this->key_cityofcountry);
        }
        if ($city_list_ofstate) {
            //delete old list
            $this->redis->delete($this->key_cityofstate);
        }        
        //add to list all
        $this->redis->set($this->key_citylistall, json_encode($list_city_info));
        
         //add to listall country
        $arr_all_country = array();
        foreach ($list_city_info AS $value) {
            $arr_all_country[$value['country_id']][$value['id']]    =   $value;
        }
        $this->redis->set($this->key_cityofcountry, json_encode($arr_all_country));

         //add to listall state
        $arr_all_state = array();
        foreach ($list_city_info AS $value) {
            $arr_all_state[$value['state_id']][$value['id']]    =   $value;
        }
        $this->redis->set($this->key_cityofstate, json_encode($arr_all_state));
                
    }
    private function addCity($city_info){
    	//add to list city in country
       $city_in_country = json_decode($this->redis->get($this->key_cityofcountry), true);
         if (!$city_in_country) {
            $city_in_country  =   array();
        }      
        $city_in_country[$city_info['country_id']][$city_info['id']] = $city_info; 
        $this->redis->set($this->key_cityofcountry, json_encode($city_in_country));
        
        //add to list city in state
       $city_in_state = json_decode($this->redis->get($this->key_cityofstate), true);
         if (!$city_in_state) {
            $city_in_state  =   array();
        }      
        $city_in_state[$city_info['state_id']][$city_info['id']] = $city_info; 
        $this->redis->set($this->key_cityofstate, json_encode($city_in_state));
        
        //add to list all
       $city_list_all = json_decode($this->redis->get($this->key_citylistall), true);
         if (!$city_list_all) {
            $city_list_all  =   array();
        }      
        $city_list_all[$city_info['id']] = $city_info;
        return $this->redis->set($this->key_citylistall, json_encode($city_list_all));
   }
   
    public function delCity($city_id){
        //delete in listall
        $city_listall = json_decode($this->redis->get($this->key_citylistall), true);
        if(isset($city_listall[$city_id])){
            unset($city_listall[$city_id]);
            
            $this->redis->set($this->key_citylistall, json_encode($city_listall));
        }
        //delete all list city in country
        $city_listincountry = json_decode($this->redis->get($this->key_cityofcountry), true);
        foreach($city_listincountry AS $country_id => $cities){
            if(is_array($cities)){
                foreach ($cities AS $k => $v){
                    if($k == $city_id){
                        unset($city_listincountry[$country_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_cityofcountry, json_encode($city_listincountry));
        
        //delete all list city in state
        $city_listinstate = json_decode($this->redis->get($this->key_cityofstate), true);
        foreach($city_listinstate AS $state_id => $cities){
            if(is_array($cities)){
                foreach ($cities AS $k => $v){
                    if($k == $city_id){
                        unset($city_listinstate[$state_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_cityofstate, json_encode($city_listincountry));        
    }
    /**
     * get info a city
     * @param type $city_id
     * @return boolean or array
     */
    public function getCityInfo($city_id) {
        $city_list = json_decode($this->redis->get($this->key_citylistall), true);
        if (isset($city_list[$city_id])) {
            return $city_list[$city_id];
        } else {
            $SysCity = new SysCity();
            $cityInfo    =   $SysCity->getCityInfo($city_id);
            if($cityInfo){
                $this->addCity($cityInfo);
                return $cityInfo;
            }else{
                return false;
            }            
        }
    }
    /**
     * get list city by country id
     * @param type $country_id
     * @return boolean or array (list city in a country)
     */
    public function getlistCityinCountry($country_id) {
        $city_list = json_decode($this->redis->get($this->key_cityofcountry), true);
        if (isset($city_list[$country_id])) {
            return $city_list[$country_id];
        } else {
            
            //get from database 
            $SysCity = new SysCity();
            $list_city_info = $SysCity->getCityList();
            if ($list_city_info) {
                $this->addCityList($list_city_info);
                $city_in_country    =   $SysCity->getCityByCountry($country_id);
                if($city_in_country){
                    return $city_in_country;
                }else{
                    return false;
                }
            } else {
                return false;
            }
            
        }
    }
    /**
     * get list city by state id
     * @param type $state_id
     * @return boolean or array (list city in a state)
     */
    public function getlistCityinState($state_id) {
    	$SysCity = new SysCity();
    	$city_in_state    =   $SysCity->getCityByState($state_id);
        if($city_in_state){
        	return $city_in_state;
        }else{
        	return false;
        }    	
    	/*
        $city_list = json_decode($this->redis->get($this->key_cityofstate), true);
        if (isset($city_list[$state_id])) {
            return $city_list[$state_id];
        } else {
            
            //get from database 
            $SysCity = new SysCity();
            $list_city_info = $SysCity->getCityList();
            if ($list_city_info) {
                $this->addCityList($list_city_info);
                $city_in_state    =   $SysCity->getCityByState($state_id);
                if($city_in_state){
                    return $city_in_state;
                }else{
                    return false;
                }
            } else {
                return false;
            }
            
        }
        */
    }    
    /**
     *  get list all city
     * @return boolean or array (list city)
     */
    public function getListCity(){
        $city_list = json_decode($this->redis->get($this->key_citylistall), true);        
        if (isset($city_list)) {
            return $city_list;
        } else {
            //get from database 
            $SysCity = new SysCity();
            $list_city_info = $SysCity->getCityList();
            if ($list_city_info) {
                $this->addCityList($list_city_info);
                return $list_city_info;
            } else {
                return false;
            }            
        }        
    }

}

?>
