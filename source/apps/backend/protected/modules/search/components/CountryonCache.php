<?php

class CountryonCache {

    public $redis = null;
    public $key_countrylistall = '';
    public $key_countrycodelistall	=	'';

    public function __construct($key_countrylistall = 'list_country_all', $key_countrycodelistall = 'list_countrycode_all') {
        $this->redis = Yii::app()->cache;
        $this->key_countrylistall = $key_countrylistall;
        $this->key_countrycodelistall	=	$key_countrycodelistall;
        
    }

    public function addCountryList($list_country_info) {
        $country_list_all = $this->redis->get($this->key_countrylistall);

        if ($country_list_all) {
            //delete old list
            $this->redis->delete($this->key_countrylistall);
        }
        $this->redis->set($this->key_countrylistall, json_encode($list_country_info));
    }
	public function addCountryCodeList($list_country_info){
		$countrycode_list_all = $this->redis->get($this->key_countrycodelistall);
        if ($countrycode_list_all) {
            //delete old list
            $this->redis->delete($this->key_countrycodelistall);
        }
        $this->redis->set($this->key_countrycodelistall, json_encode($list_country_info));		
	}
    public function addCountry($country_info) {
        $country_list = json_decode($this->redis->get($this->key_countrylistall), true);
        if (!$country_list) {
            $country_list = array();
        }
        $country_list[$country_info['id']] = $country_info;
        return $this->redis->set($this->key_countrylistall, json_encode($country_list));
    }

    public function delCountry($country_id) {
        //delete in listall
        $country_listall = json_decode($this->redis->get($this->key_countrylistall), true);
        if (isset($country_listall[$country_id])) {
            unset($country_listall[$country_id]);
            return $this->redis->set($this->key_countrylistall, json_encode($country_listall));
        } else {
            return false;
        }
    }

    /**
     * get info a country
     * @param type $country_id
     * @return boolean or array
     */
    public function getCountryInfo($country_id) {
        $country_listall = json_decode($this->redis->get($this->key_countrylistall), true);
        if (isset($country_listall[$country_id])) {
            return $country_listall[$country_id];
        } else {
            $SysCountry = new SysCountry();
            $countryInfo    =   $SysCountry->getCountryInfo($country_id);
            if($countryInfo){
                $this->addCountry($countryInfo);
                return $countryInfo;
            }else{
                return false;
            }
        }
    }

    /**
     *  get list all country
     * @return boolean or array (list country)
     */
    public function getListCountry() {
    	//get from database directly
    	$SysCountry = new SysCountry();
        $list_country_info = $SysCountry->getCountryList();
        return $list_country_info;
        /*    
        $country_listall = json_decode($this->redis->get($this->key_countrylistall), true);
        if ($country_listall) {
            return $country_listall;
        } else {
            //get from database 
            $SysCountry = new SysCountry();
            $list_country_info = $SysCountry->getCountryList();
            if ($list_country_info) {
                $this->addCountryList($list_country_info);
                return $list_country_info;
            } else {
                return false;
            }
        }
        */
    }
    
    
    /**
     *  get current country
     * @return country_id
     */
	public function getCurrentCountry(){
		$current_ip = Yii::app()->request->getUserHostAddress();
		if($current_ip == '127.0.0.1'){
			$current_country_code = 'VN';
		} else {
			$current_country_code = Yii::app()->geoip->lookupCountryCode($current_ip);	
		}
				
		$countrycode_listall = json_decode($this->redis->get($this->key_countrycodelistall), true);
		if(isset($countrycode_listall[$current_country_code])){
			return $countrycode_listall[$current_country_code];
		}else{
			//get from database
            $SysCountry = new SysCountry();
            $countrycode_listall = $SysCountry->getCountryCodeList();	
		    if (isset($countrycode_listall[$current_country_code])) {
                $this->addCountryCodeList($countrycode_listall);
                return $countrycode_listall[$current_country_code];
            } else {
                return 230;
            }            		
		}

		
		
	}
}

?>
