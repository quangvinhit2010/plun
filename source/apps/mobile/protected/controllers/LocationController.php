<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class LocationController extends MemberController {
	public function actionCheckin(){
		$country_in_cache = new CountryonCache();
		$current_country = $country_in_cache->getCurrentCountry();
		$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if(!$model_location){
			//create settings
			
			$country_id	=	$current_country['id'];
			$model_location     =   new UsrProfileLocation();
			$model_location->user_id       =   $this->usercurrent->id;
			$model_location->current_country_id	=	$country_id;
			$model_location->save();
		}		
		$country_id		=	$model_location->current_country_id;
		$state_id		=	$model_location->current_state_id;
		$city_id		=	$model_location->current_city_id;
		$district_id	=	$model_location->current_district_id;
				
		
		$state_in_cache	=	new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache = new DistrictonCache();
				
		$list_country = $country_in_cache->getListCountry();
		$list_state	=	array();
		$list_city	=	array();
		$list_district	=	array();
		
		/*
		if ($list_country) {
			if (!$country_id) {
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
		*/
		if ($list_country) {
			if (!$model_location->current_country_id) {
				$current_country = $country_on_cache->getCurrentCountry();
				$country_id	=	$current_country['id'];
			}else{
				$country_id	=	$model_location->current_country_id;
			}
			//get state list
			$list_state	=	$state_in_cache->getlistStateinCountry($country_id);
		
			if($list_state){
				//get city list
				if($model_location->current_state_id){
					$list_city	=	$city_in_cache->getlistCityinState($model_location->current_state_id);
					if($list_city){
						//get district list
						if($model_location->current_city_id){
							$list_district	=	$district_in_cache->getlistDistrictinCity($model_location->current_city_id);
						}
		
					}
				}
			}
		
		}		
		$this->render('page/checkin', array(
				'profile_location'	=>	$model_location,
				'list_country'	=>	$list_country,
				'list_city'	=>	($list_city ? $list_city : array()),
				'list_state'	=>	($list_state ? $list_state : array()),
				'list_district'	=>	($list_district ? $list_district : array()),
				'country_in_cache'	=>	$country_in_cache,
				'country_id'		=>	$country_id,
				'state_id'		=>	$state_id,
				'city_id'		=>	$city_id,
				'district_id'		=>	$district_id,
				'city_in_cache'	=>	$city_in_cache,
				'district_in_cache'	=>	$district_in_cache,
				'state_in_cache'	=>	$state_in_cache,
		));		
	}
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
    public function actionCity(){
    	$model	=	SysCity::model();
    					
		
		$this->render('page/city',array(
			'model'=>$model,
		));	
		
    }
    /*
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
    	
    	$country_id = Yii::app()->request->getParam('country_id', 0);
    	$state_id = Yii::app()->request->getParam('state_id', 0);
    	$looking_for	=	Yii::app()->request->getParam('looking_for', 0);
    	$district_id = Yii::app()->request->getParam('district_id', 0);
    	$city_id = Yii::app()->request->getParam('city_id', 0);
    	$post	=	array(
	    	'current_country_id'	=>	$country_id,
	    	'current_state_id'	=>	$state_id,
	    	'current_city_id'	=>	$city_id,
	    	'current_district_id'	=>	$district_id,
    		'published'	=>	'1',
    		'updated'	=>	time()
	    );
    	
   		$model->attributes = $post;
   		$model->user_id = Yii::app()->user->id;
		$model->validate();
		$model->save(); 	
		
		
		//save looking for when online
		$user->profile->online_lookingfor	=	$looking_for;
		$user->profile->validate();
		$user->profile->save(); 
        	            
		$country_in_cache = new CountryonCache();
		$state_in_cache	=	new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache = new DistrictonCache();

		/*Begin set notify
		 * 
		 * */
		$criteria=new CDbCriteria;
		$criteria->addCondition('country_id = :country_id');
		$criteria->addCondition('city_id = :city_id');
		$criteria->addCondition('district_id = :district_id');
		$criteria->addCondition('date < :date');
		$criteria->addCondition('end_date > :date');
		$criteria->params = array(
				':country_id' => $country_id,
				':city_id' => $state_id,
				':district_id' => $city_id,
				'date' => $model->updated
					
		);
		
		
		$number_isu = Notes::model()->count($criteria);
		$country_info = $country_in_cache->getListCountry();
		$state_info = $state_in_cache->getListState();
		$city_info = $city_in_cache->getListCity();
			
		$location_display	=	'';
		$country_name   =   !empty($country_info[$model->current_country_id]['name'])   ?   $country_info[$model->current_country_id]['name']    :   '';
		$state_name   =   !empty($state_info[$model->current_state_id]['name'])  ?  $state_info[$model->current_state_id]['name']    :   '' ;
		$district_name = !empty($city_info[$model->current_city_id]['name'])   ?   $city_info[$model->current_city_id]['name']    :   '';
			
		if(!empty($district_name)){
			$location_display	=	"$country_name, $state_name, $district_name";
		} else {
			$location_display	=	"$state_name, $district_name";
		}
		
		if($district_id){
			//store sesssion for suggest user
			Yii::app()->session['checkin_district_id']	=	$district_id;
		}else{
			unset(Yii::app()->session['checkin_district_id']);
		}
		if($city_id){
			//store sesssion for suggest user
			Yii::app()->session['checkin_city_id']	=	$city_id;
		}else{
			unset(Yii::app()->session['checkin_city_id']);
		}
		if($state_id){
			//store sesssion for suggest user
			Yii::app()->session['checkin_state_id']	=	$state_id;
		}else{
			unset(Yii::app()->session['checkin_state_id']);
		}
		if($country_id){
			//store sesssion for suggest user
			Yii::app()->session['checkin_country_id']	=	$country_id;
		}else{
			unset(Yii::app()->session['checkin_country_id']);
		}
		
		if($number_isu > 0) {
			$notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_ISU_MATCH_LOCATION));
			$notification_data = array(
					'params' => array('{number}'=>$number_isu, '{location}' => $location_display),
					'message' => 'You checked in at {location}? There\'ve been {number} new ISU that might be relevant to you. Check them now to see if you\'re the person they\'re looking for!'
			
			);
			$data = array(
					'userid'=> 1,
					'ownerid'=>Yii::app()->user->id,
					'owner_type'=>'user',
					'notification_type'=>$notifiType->id,
					'notification_data'=>addslashes(json_encode($notification_data)),
					'timestamp'=>time(),
					'last_read'=>0,
			);
			XNotifications::model()->saveNotifications($data);
		}
		/*End set notify
		 *
		 */
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
            'list_state' => $list_state
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
