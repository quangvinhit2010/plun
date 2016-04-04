<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class LocationController extends Controller {
    public function actionIndex(){
    	$model	=	SysDistrict::model();
    			
    	$country_on_cache = new CountryonCache();
		$city_on_cache = new CityonCache();
		
		if(isset($_GET['city_id'])){
			$model->city_id	=	$_GET['city_id'];
		}
		$this->render('page/index',array(
			'model'=>$model,
			'list_city'	=>	$city_on_cache->getlistCityinCountry(230),
			'list_country'	=>	$country_on_cache->getListCountry()
		));	
    }
    /*
    public function actionCity(){
    	$model	=	SysCity::model();
    					
		
		$this->render('page/city',array(
			'model'=>$model,
		));	
    }
    public function actionCitySave(){
    		$model	=	new SysCity();
    		$post = Yii::app()->request->getPost('SysCity');
  
			$model->attributes = $post;
			if($model->save()){

				$this->redirect('/location/city');
			}
			
    }       
    public function actionSave(){
    		$model	=	new SysDistrict();
    		$post = Yii::app()->request->getPost('SysDistrict');
  
			$model->attributes = $post;
			if($model->save()){

				$this->redirect('/location/index/city_id/' . $post['city_id']);
			}
			
    }
    */
    public function actionGetstatelist(){
        $list_state = array();
        try {
            $state_in_cache = new StateonCache();
            $country_id = Yii::app()->request->getParam('country_id', 0);
            $list_state = $state_in_cache->getlistStateinCountry($country_id);
            if (!$list_state) {
                $list_state = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_state', array(
            'list_state' => $list_state
        ));    	
    }
    public function actionsaveCheckin(){
    	$country_in_cache = new CountryonCache();
    	$state_in_cache	=	new StateonCache();
    	$city_in_cache = new CityonCache();
    	$district_in_cache = new DistrictonCache();
    	
    	$user =  Yii::app()->user->data();
    	$model = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	$current_country_id	=	0;
    	
		if(!$model){
			//create profile current location
			$model     =   new UsrProfileLocation();
			$model->user_id       =   Yii::app()->user->id;
			$model->save();
		}else{
			$current_country_id	=	$model->current_country_id;
		}   	
    	
		$text_venue = Yii::app()->request->getParam('text_venue', false);
		$venue_id = Yii::app()->request->getParam('id_venue', 0);
				
    	$country_id = Yii::app()->request->getParam('country_id', 0);
    	$state_id = Yii::app()->request->getParam('state_id', 0);
    	$looking_for	=	Yii::app()->request->getParam('looking_for', 0);
    	$district_id = Yii::app()->request->getParam('district_id', 0);
    	$city_id = Yii::app()->request->getParam('city_id', 0);
    	
    	//begin processing venues
    	$venue	=	new venues();
    	if($text_venue){
    		if(!$venue_id){
    			
    			$venues_searchdata	=	$venue->search($text_venue, array('country_id' => $country_id));
    			$parent_id	=	0;
    			if(isset($venues_searchdata['data'][0])){
    				$parent_id	=	$venues_searchdata['data'][0]['_source']['venue_id'];
    			}
    			//create venue
    			$current_location	=	array();
    			if($district_id){
    				$district =	$district_in_cache->getDistrictInfo($district_id);
    				$current_location[]	=	$district['name'];
    			}
    			if($city_id){
    				$city =	$city_in_cache->getCityInfo($city_id);
    				$current_location[]	=	$city['name'];
    			}
    			if($state_id){
    				$state =	$state_in_cache->getStateInfo($state_id);
    				$current_location[]	=	$state['name'];
    			}
    			if($country_id){
    				$country =	$country_in_cache->getCountryInfo($country_id);
    				$current_location[]	=	$country['name'];
    			}
    			
    			//general current location
    			if(!empty($current_location)){
    				$current_location	=	implode(', ', $current_location);
    			}else{
    				$current_location	=	'';
    			}
    			
    			$venue_model	=	new CmsVenues();
    			$venue_create	=	array(
    					'title'	=>	htmlspecialchars($text_venue),
    					'title_nosymbol' => venues::alias($text_venue),
    					'address' => $current_location,
    					'date_created' => time(),
    					'user_created' => Yii::app()->user->id,
    					'published'	=>	'0',
    					'country_id'	=>	$country_id,
    					'state_id'	=>	$state_id,
    					'city_id'	=>	$city_id,
    					'district_id'	=>	$district_id,
    					'parent_id'	=>	$parent_id,
    					'total_visit'	=>	1
    			);
    			$venue_model->attributes = $venue_create;
    			$venue_model->validate();
    			if($venue_model->save()){
    				$venue_id	=	$venue_model->id;
    				$venue->addVenues($venue_model->id);
    				

    				
    			}
    		}
    		//log history
    		if($venue_id && Yii::app()->user->id){
    			$venue_history	=	new CmsVenuesHistory();
    			$venue_history->venue_id	=	$venue_id;
    			$venue_history->type		=	CmsVenuesHistory::VISITOR_TYPE_CHECKIN;
    			$venue_history->date_created=	time();
    			$venue_history->user_id		=	Yii::app()->user->id;
    			$venue_history->ip			=	$_SERVER['REMOTE_ADDR'];
    			if($venue_history->save()){
    				$venues_row	=	CmsVenues::model()->findByPk($venue_id);
    				if($venues_row){
    					$venues_row->total_visit	=	CmsVenuesHistory::model()->getTotalVisit($venue_id);
    					$venues_row->save();
    					$venue->updateVenues($venue_id);
    				}
    			}
    		}
    		
    		$log = Activity::model()->log(
    				Activity::LOG_CHECK_IN, array(
    						'{userfrom}' => Yii::app()->user->data()->username,
    						'{venue}' => $text_venue,
    						'{venue_id}' => $venue_id,
    						'{message}'	=>	''
    				), Yii::app()->user->id, Yii::app()->user->data()->username, $venue_id
    		);
    	}
    	

    	    	
    	$post	=	array(
	    	'current_country_id'	=>	$country_id,
	    	'current_state_id'	=>	$state_id,
	    	'current_city_id'	=>	$city_id,
	    	'current_district_id'	=>	$district_id,
    		'published'	=>	'1',
    		'updated'	=>	time(),
    		'venue_id'	=>	$venue_id
	    );
    	
   		$model->attributes = $post;
   		$model->user_id = Yii::app()->user->id;
		$model->validate();
		$model->save(); 	
		

		
		//save looking for when online
		$user->profile->online_lookingfor	=	$looking_for;
		$user->profile->validate();
		$user->profile->save(); 
        
		//show status after data saved
		$looing_foronline	=	ProfileSettingsConst::getLookingForOnlineLabel();
		$looking_online_status	=	isset($looing_foronline[$looking_for])		?	$looing_foronline[$looking_for] . ' | '	:	'';
		            

		                	
		$current_location	=	array();
		if($district_id){
			$district =	$district_in_cache->getDistrictInfo($district_id);
			$current_location[]	=	$district['name'];
			//store sesssion for suggest user
			Yii::app()->session['checkin_district_id']	=	$district_id;
		}else{
			unset(Yii::app()->session['checkin_district_id']);
		}
		if($city_id){
			$city =	$city_in_cache->getCityInfo($city_id);
			$current_location[]	=	$city['name'];
			//store sesssion for suggest user
			Yii::app()->session['checkin_city_id']	=	$city_id;
		}else{
			unset(Yii::app()->session['checkin_city_id']);
		}
		if($state_id){
			$state =	$state_in_cache->getStateInfo($state_id);
			$current_location[]	=	$state['name'];
			//store sesssion for suggest user
			Yii::app()->session['checkin_state_id']	=	$state_id;
		}else{
			unset(Yii::app()->session['checkin_state_id']);
		}
		if($country_id){
			$country =	$country_in_cache->getCountryInfo($country_id);
			$current_location[]	=	$country['name'];
			//store sesssion for suggest user
			Yii::app()->session['checkin_country_id']	=	$country_id;
		}else{
			unset(Yii::app()->session['checkin_country_id']);
		}
		
		//general current location
		if(!empty($current_location)){
			$current_location	=	implode(', ', $current_location);
		}else{
			$current_location	=	'';
		}		

		

			
		$this->renderPartial('partial/current_location_status', array(
            'looking_online_status' => $looking_online_status,
			'current_location'	=>	$current_location
        ));
        $Elasticsearch	=	new Elasticsearch();
        
        if($current_country_id == $country_id){
			$Elasticsearch->updateSearchIndexUser(Yii::app()->user->id);    
        }else{
        	$Elasticsearch->changeCountrySearchIndexUser(Yii::app()->user->id, $country_id, $current_country_id);
        }    				
		exit;
    }
    public function actionGetstatelistRegister(){
        $list_state = array();
        try {
            $state_in_cache = new StateonCache();
            $country_id = Yii::app()->request->getParam('country_id', 0);
            $list_state = $state_in_cache->getlistStateinCountry($country_id);
            if (!$list_state) {
                $list_state = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_state_register', array(
            'list_state' => $list_state,
        	'country_id'	=>	$country_id
        ));    	
    } 
    public function actionGetcitylistRegister() {
        $list_city = array();
        try {
            $city_in_cache = new CityonCache();
            $state_id = Yii::app()->request->getParam('state_id', 0);
            $list_city = $city_in_cache->getlistCityinState($state_id);
            if (!$list_city) {
                $list_city = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_city_register', array(
            'list_city' => $list_city
        ));
    } 
    public function actionGetdistrictlistRegister() {
        $list_district = array();
        try {
            $district_in_cache = new DistrictonCache();

            $city_id = Yii::app()->request->getParam('city_id', 0);
            $list_district = $district_in_cache->getlistDistrictinCity($city_id);

            if (!$list_district) {
                $list_district = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_district_register', array(
            'list_district' => $list_district
        ));
    }          
    public function actionGetstatelistCheckin(){
        $list_state = array();
        try {
            $state_in_cache = new StateonCache();
            $country_id = Yii::app()->request->getParam('country_id', 0);
            $list_state = $state_in_cache->getlistStateinCountry($country_id);
            if (!$list_state) {
                $list_state = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_state_checkin', array(
            'list_state' => $list_state
        ));    	
    }    
    public function actionGetcitylistCheckin() {
        $list_city = array();
        try {
            $city_in_cache = new CityonCache();
            $state_id = Yii::app()->request->getParam('state_id', 0);
            $list_city = $city_in_cache->getlistCityinState($state_id);
            if (!$list_city) {
                $list_city = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_city_checkin', array(
            'list_city' => $list_city
        ));
    }    
    public function actionGetdistrictlistCheckin() {
        $list_district = array();
        try {
            $district_in_cache = new DistrictonCache();

            $city_id = Yii::app()->request->getParam('city_id', 0);
            $list_district = $district_in_cache->getlistDistrictinCity($city_id);

            if (!$list_district) {
                $list_district = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_district_checkin', array(
            'list_district' => $list_district
        ));
    }    
    public function actionGetstatelistSettings(){
        $list_state = array();
        try {
            $state_in_cache = new StateonCache();
            $country_id = Yii::app()->request->getParam('country_id', 0);
            $list_state = $state_in_cache->getlistStateinCountry($country_id);
            if (!$list_state) {
                $list_state = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_state_settings', array(
            'list_state' => $list_state,
        	'country_id'	=>	$country_id
        ));    	
    }    
    public function actionGetcitylist() {
        $list_city = array();
        try {
            $city_in_cache = new CityonCache();
            $state_id = Yii::app()->request->getParam('state_id', 0);
            $list_city = $city_in_cache->getlistCityinState($state_id);
            if (!$list_city) {
                $list_city = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_city', array(
            'list_city' => $list_city
        ));
    }
    public function actionGetcitylistSettings() {
        $list_city = array();
        try {
            $city_in_cache = new CityonCache();
            $state_id = Yii::app()->request->getParam('state_id', 0);
            $list_city = $city_in_cache->getlistCityinState($state_id);
            if (!$list_city) {
                $list_city = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_city_settings', array(
            'list_city' => $list_city
        ));
    }
    public function actionGetdistrictlist() {
        $list_district = array();
        try {
            $district_in_cache = new DistrictonCache();

            $city_id = Yii::app()->request->getParam('city_id', 0);
            $list_district = $district_in_cache->getlistDistrictinCity($city_id);

            if (!$list_district) {
                $list_district = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_district', array(
            'list_district' => $list_district
        ));
    }
    public function actionGetdistrictlistSettings() {
        $list_district = array();
        try {
            $district_in_cache = new DistrictonCache();

            $city_id = Yii::app()->request->getParam('city_id', 0);
            $list_district = $district_in_cache->getlistDistrictinCity($city_id);

            if (!$list_district) {
                $list_district = array();
            }
        } catch (Exception $e) {
            
        }

        $this->renderPartial('partial/list_district_settings', array(
            'list_district' => $list_district
        ));
    }        
}
?>
