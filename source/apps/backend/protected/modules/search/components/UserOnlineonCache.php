<?php

class UserOnlineonCache {
    public $key = '';
    public $redis   =   null;
    
    public function __construct($key = 'user_online') {
        $this->redis = Yii::app()->cache;
    }    
    public function search($search_conditions = array()){
        $user_online_list   =   $this->redis->get($this->key);
        if(!$user_online_list){
            $user_online_list=  $this->setUserOnlineOnCache();
        }
        
        //search
        $result =   false;
        $online_id  =   false;
        foreach ($user_online_list as $user_id => $user_info) {
            $check  =   true;
            //sex role
            if(isset($search_conditions['sex_role'])){
                if(array_search($user_info['sex_role'], $search_conditions['sex_role']) === false){
                    $check  =   false;
                }
            }
            //country id
            if(isset($search_conditions['country_id'])){
                if($search_conditions['country_id'] != $user_info['country_id']){
                    $check  =   false;
                }
            } 
            //city_id
            if(isset($search_conditions['city_id'])){
                if($search_conditions['city_id'] != $user_info['city_id']){
                    $check  =   false;
                }
            } 
            //district_id
            if(isset($search_conditions['district_id'])){
                if($search_conditions['district_id'] != $user_info['district_id']){
                    $check  =   false;
                }
            }
            //smoke
            if(isset($search_conditions['smoke'])){
                if(array_search($user_info['smoke'], $search_conditions['smoke']) === false){
                    $check  =   false;
                }
            }
            //body
            if(isset($search_conditions['body'])){
                if($search_conditions['body'] == $user_info['body']){
                    $check  =   false;
                }
            }
            //safer_sex
            if(isset($search_conditions['safer_sex'])){
                if($search_conditions['safer_sex'] == $user_info['safer_sex']){
                    $check  =   false;
                }
            }
            //ethnic_id
            if(isset($search_conditions['ethnic_id'])){
                if($search_conditions['ethnic_id'] == $user_info['ethnic_id']){
                    $check  =   false;
                }
            }
            //dick_size
            if(isset($search_conditions['dick_size'])){
                if(array_search($user_info['dick_size'], $search_conditions['dick_size']) === false){
                    $check  =   false;
                }
            }
            if($check){
                $result[$user_id]   =   $user_info;
                $online_id[]    =   $user_info['id'];
            }
        }
        return array('id_list'  => $online_id, 'info_list'  =>   $result);
    }
    public function setUserOnlineOnCache(){
        $UsrProfileSettings =   new UsrProfileSettings();
        $user_online    =   $UsrProfileSettings->getOnlineUserList();
        $this->redis->set($this->key, $user_online);
        return $user_online;
    }
}