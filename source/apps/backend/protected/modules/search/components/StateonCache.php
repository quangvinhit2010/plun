<?php

class StateonCache {

    public $redis = null;
    public $key_statelistall = '';
    public $key_stateofcountry = '';

    public function __construct($key_statelistall = 'list_state_all', $key_stateofcountry = 'liststate_in_country') {
        $this->redis = Yii::app()->cache;
        $this->key_statelistall = $key_statelistall;
        $this->key_stateofcountry = $key_stateofcountry;
    }

    public function addStateList($list_state_info) {
        $state_list_ofcountry = $this->redis->get($this->key_stateofcountry);
        $city_list_all = $this->redis->get($this->key_statelistall);
        
        if ($city_list_all) {
            //delete old list
            $this->redis->delete($this->key_statelistall);
        }
        if ($state_list_ofcountry) {
            //delete old list
            $this->redis->delete($this->key_stateofcountry);
        }
        //add to list all
        $this->redis->set($this->key_statelistall, json_encode($list_state_info));
        
         //add to listall country
        $arr_all_country = array();
        foreach ($list_state_info AS $value) {
            $arr_all_country[$value['country_id']][$value['id']]    =   $value;
        }
        $this->redis->set($this->key_stateofcountry, json_encode($arr_all_country));
        
    }
    private function addState($state_info){
       $state_in_country = json_decode($this->redis->get($this->key_stateofcountry), true);
         if (!$state_in_country) {
            $state_in_country  =   array();
        }      
        $state_in_country[$state_info['country_id']][$state_info['id']] = $state_info;  
        //add to list country
        $this->redis->set($this->key_stateofcountry, json_encode($state_in_country));
        
       $state_list_all = json_decode($this->redis->get($this->key_statelistall), true);
         if (!$state_list_all) {
            $state_list_all  =   array();
        }      
        $state_list_all[$state_info['id']] = $state_info;
        return $this->redis->set($this->key_statelistall, json_encode($state_list_all));
   }
   
    public function delState($state_id){
        //delete in listall
        $state_listall = json_decode($this->redis->get($this->key_statelistall), true);
        if(isset($state_listall[$state_id])){
            unset($state_listall[$state_id]);
            
            $this->redis->set($this->key_statelistall, json_encode($state_listall));
        }
        //delete all list country
        $state_listincountry = json_decode($this->redis->get($this->key_stateofcountry), true);
        
        foreach($state_listincountry AS $country_id => $states){
            if(is_array($states)){
                foreach ($states AS $k => $v){
                    if($k == $state_id){
                        unset($state_listincountry[$country_id][$k]);
                    }
                }
            }
        }
        $this->redis->set($this->key_stateofcountry, json_encode($state_listincountry));
    }
    /**
     * get info a state
     * @param type $state_id
     * @return boolean or array
     */
    public function getStateInfo($state_id) {
        $state_list = json_decode($this->redis->get($this->key_statelistall), true);
        if (isset($state_list[$state_id])) {
            return $state_list[$state_id];
        } else {
            $LocationState = new LocationState();
            $stateInfo    =   $LocationState->getStateInfo($state_id);
            if($stateInfo){
                $this->addState($stateInfo);
                return $stateInfo;
            }else{
                return false;
            }            
        }
    }
    /**
     * get list state by country id
     * @param type $country_id
     * @return boolean or array (list state in a country)
     */
    public function getlistStateinCountry($country_id) {
    	//get from database directly   	
    	$LocationState = new LocationState();
        $state_in_country    =   $LocationState->getStateByCountry($country_id);
       	if($state_in_country){
        	return $state_in_country;
        }else{
        	return false;
        }    	
    	/*
        $state_list = json_decode($this->redis->get($this->key_stateofcountry), true);
        if (isset($state_list[$country_id])) {
            return $state_list[$country_id];
        } else {
            
            //get from database 
            $LocationState = new LocationState();
            $list_state_info = $LocationState->getStateList();
            if ($list_state_info) {
                $this->addStateList($list_state_info);
                $state_in_country    =   $LocationState->getStateByCountry($country_id);
                if($state_in_country){
                    return $state_in_country;
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
     *  get list all state
     * @return boolean or array (list state)
     */
    public function getListState(){
        $state_list = json_decode($this->redis->get($this->key_statelistall), true);        
        if (isset($state_list)) {
            return $state_list;
        } else {
            //get from database 
            $LocationState = new LocationState();
            $list_state_info = $LocationState->getStateList();
            if ($list_state_info) {
                $this->addStateList($list_state_info);
                return $list_state_info;
            } else {
                return false;
            }            
        }        
    }

}

?>
