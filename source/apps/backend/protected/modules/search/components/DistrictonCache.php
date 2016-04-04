<?php

class DistrictonCache {

    public $redis = null;
    public $key_districtlistall = '';
    public $key_district_in_city= '';
    public $key_district_in_country= '';
    public $key_district_in_state	= '';
    
    public function __construct($key_districtlistall = 'list_district_all', $key_district_in_city = 'key_district_in_city', $key_district_in_country = 'key_district_in_country', $key_district_in_state = 'key_district_in_state') {
        $this->redis = Yii::app()->cache;
        $this->key_districtlistall = $key_districtlistall;
        $this->key_district_in_city = $key_district_in_city;
        $this->key_district_in_country = $key_district_in_country;
        $this->key_district_in_state = $key_district_in_state;
    }

    public function addDistrictList($list_district_info) {
        $district_in_country = $this->redis->get($this->key_district_in_country);
        $district_in_state = $this->redis->get($this->key_district_in_state);
        $district_in_city = $this->redis->get($this->key_district_in_city);
        $district_list_all = $this->redis->get($this->key_districtlistall);
        
        if ($district_list_all) {
            //delete old list
            $this->redis->delete($this->key_districtlistall);
        }
        if ($district_in_country) {
            //delete old list
            $this->redis->delete($this->key_district_in_country);
        }
        if ($district_in_city) {
            //delete old list
            $this->redis->delete($this->key_district_in_city);
        }
        if ($district_in_state) {
            //delete old list
            $this->redis->delete($this->key_district_in_state);
        }        
        //add to list all
        $this->redis->set($this->key_districtlistall, json_encode($list_district_info));
        
        //add to listall city
        $arr_all_city = array();
        foreach ($list_district_info AS $value) {
            $arr_all_city[$value['city_id']][$value['id']]    =   $value;
        }
        $this->redis->set($this->key_district_in_city, json_encode($arr_all_city));
        
         //add to listall country
        $arr_all_country = array();
        foreach ($list_district_info AS $value) {
            $arr_all_country[$value['country_id']][$value['id']]    =   $value;
        }
        $this->redis->set($this->key_district_in_country, json_encode($arr_all_country));
        
        //add to listall state
        $arr_all_state = array();
        foreach ($list_district_info AS $value) {
            $arr_all_state[$value['state_id']][$value['id']]    =   $value;
        }        
        $this->redis->set($this->key_district_in_state, json_encode($arr_all_state));        
        
    }
    public function addDistrict($district_info){
       $district_list_all = json_decode($this->redis->get($this->key_districtlistall), true);
         if (!$district_list_all) {
            $district_list_all  =   array();
        }      
        $district_list_all[$district_info['id']] = $district_info;
        
        //add to list all
        $this->redis->set($this->key_districtlistall, json_encode($district_list_all));
        
       $district_in_city = json_decode($this->redis->get($this->key_district_in_city), true);
         if (!$district_in_city) {
            $district_in_city  =   array();
        }      
        $district_in_city[$district_info['city_id']][$district_info['id']] = $district_info;  
        //add to list city
        $this->redis->set($this->key_district_in_city, json_encode($district_in_city));  
        
       $district_in_country = json_decode($this->redis->get($this->key_district_in_country), true);
         if (!$district_in_country) {
            $district_in_country  =   array();
        }      
        $district_in_country[$district_info['country_id']][$district_info['id']] = $district_info;  
        //add to list country
        $this->redis->set($this->key_district_in_country, json_encode($district_in_country)); 

       //add to state list
        $district_in_state = json_decode($this->redis->get($this->key_district_in_state), true);
         if (!$district_in_state) {
            $district_in_state  =   array();
        }      
        $district_in_state[$district_info['state_id']][$district_info['id']] = $district_info;  
        //add to list state
        $this->redis->set($this->key_district_in_state, json_encode($district_in_state));         
   }

    public function delDistrict($district_id){
        //delete in listall
        $district_list_all = json_decode($this->redis->get($this->key_districtlistall), true);
        if(isset($district_list_all[$district_id])){
            unset($district_list_all[$district_id]);
            $this->redis->set($this->key_districtlistall, json_encode($district_list_all));
        }
        
        //delete all list country
        $district_in_country = json_decode($this->redis->get($this->key_district_in_country), true);
        foreach($district_in_country AS $country_id => $districts){
            if(is_array($districts)){
                foreach ($districts AS $k => $v){
                    if($k == $district_id){
                        unset($district_in_country[$country_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_district_in_country, json_encode($district_in_country));
        
        //delete all list city
        $district_in_city = json_decode($this->redis->get($this->key_district_in_city), true);
        foreach($district_in_city AS $city_id => $districts){
            if(is_array($districts)){
                foreach ($districts AS $k => $v){
                    if($k == $district_id){
                        unset($district_in_city[$city_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_district_in_city, json_encode($district_in_city)); 

        //delete all list state
        $district_in_state = json_decode($this->redis->get($this->key_district_in_state), true);
        foreach($district_in_state AS $state_id => $districts){
            if(is_array($districts)){
                foreach ($districts AS $k => $v){
                    if($k == $district_id){
                        unset($district_in_state[$state_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_district_in_state, json_encode($district_in_state));           
    }
    
    /**
     * get info a district
     * @param type $district_id
     * @return boolean or array
     */
    public function getDistrictInfo($district_id) {
        $district_list_all = json_decode($this->redis->get($this->key_districtlistall), true);
        if (isset($district_list_all[$district_id])) {
            return $district_list_all[$district_id];
        } else {
            return false;
        }
    }
    /**
     * get list district by country id
     * @param type $country_id
     * @return boolean or array (list district in a country)
     */
    public function getlistDistrictinCountry($country_id) {
        $district_in_country = json_decode($this->redis->get($this->key_district_in_country), true);
        if (isset($district_in_country[$country_id])) {
            return $district_in_country[$country_id];
        } else {
            return false;
        }
    }
    
    /**
     * get list district by city id
     * @param type $city_id
     * @return boolean or array (list district in a city)
     */
    public function getlistDistrictinCity($city_id) {
    	/*
        $district_in_city = json_decode($this->redis->get($this->key_district_in_city), true);
        if (isset($district_in_city[$city_id])) {
            return $district_in_city[$city_id];
        } else {
            //get from database 
            $SysDistrict = new SysDistrict();
            $list_district_info = $SysDistrict->getDistrictList();
            if ($list_district_info) {
                $this->addDistrictList($list_district_info);
                $district_in_city    =   $SysDistrict->getDistrictByCity($city_id);
                if($district_in_city){
                    return $district_in_city;
                }else{
                    return false;
                }
            } else {
                return false;
            }
        }
        */
    	$SysDistrict = new SysDistrict();
    	$district_in_city    =   $SysDistrict->getDistrictByCity($city_id);
    	if($district_in_city){
    		return $district_in_city;
    	}else{
    		return false;
    	}    	
    }
    /**
     * get list district by state id
     * @param type $state_id
     * @return boolean or array (list district in a state)
     */
    public function getlistDistrictinState($state_id) {
        $district_in_state = json_decode($this->redis->get($this->key_district_in_state), true);
        if (isset($district_in_state[$state_id])) {
            return $district_in_state[$state_id];
        } else {
            //get from database 
            $SysDistrict = new SysDistrict();
            $list_district_info = $SysDistrict->getDistrictList();
            if ($list_district_info) {
                $this->addDistrictList($list_district_info);
                $district_in_state    =   $SysDistrict->getDistrictByState($state_id);
                if($district_in_state){
                    return $district_in_state;
                }else{
                    return false;
                }
            } else {
                return false;
            }
        }
    }    
    /**
     *  get list all district
     * @return boolean or array (list district)
     */
    public function getListDistrict(){
        $district_list_all = json_decode($this->redis->get($this->key_districtlistall), true);
        if (isset($district_list_all)) {
            return $district_list_all;
        } else {
            
            //get from database 
            $SysDistrict = new SysDistrict();
            $list_district_info = $SysDistrict->getDistrictList();
            
            if ($list_district_info) {
                $this->addDistrictList($list_district_info);
                return $list_district_info;
            } else {
                return false;
            }            
        }        
    }

}

?>
