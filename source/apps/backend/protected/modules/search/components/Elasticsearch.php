<?php
require 'Elasticsearch/autoload.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Elasticsearch {
	public $search_engine	=	null;
	public $prefix_key	=	null;
	public $sort_rule_script	=	false;
	public $sort_rule_type		=	'desc';
	public $fields_search	=	'username';
	public $search_keyword_type	=	'regex';
	
	const DEVICE_PC	=	1;
	const DEVICE_MOBILE	=	2;

	const HIDE_ON_SEARCH_STATUS	=	0;
	const SHOW_ON_SEARCH_STATUS	=	1;
	
	public function __construct(){
		$this->prefix_key	=	Yii::app()->params->Elastic['prefix_key'];
		$params['hosts'] = array (
		Yii::app()->params->Elastic['host_http']
		);
		$this->search_engine	=	new Elasticsearch\Client($params);
	}
	
	public function removeUser($user_id){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("s.current_country_id");
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");
		$dataUser = $cmd->queryRow();
		if($dataUser){
			$CDbCriteria = new CDbCriteria();
			$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
			$rows = SysSearchIndex::model()->findAll($CDbCriteria);
			foreach($rows AS $row){
				$params = array();
				$params['index']     = $row->index_folder;
				$params['type']     = $row->index_folder;
				$params['id']    = $user_id;
				$params['ignore']	=	array('404');
				$this->search_engine->delete($params);
			}		
		}
	}
	public function updateCounter($user_id, $fieldName, $counterType = '+', $num = 1){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");
		$dataUser = $cmd->queryRow();
		
		if($dataUser){
			//get index informations
			$CDbCriteria = new CDbCriteria();
			$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
			$rows = SysSearchIndex::model()->findAll($CDbCriteria);
			
			foreach($rows AS $row){
				$params = array();
				$params['body']['script']  = "ctx._source.{$fieldName} = max((int)ctx._source.{$fieldName} {$counterType} 1, 0)";
				$params['body']['params']  = array('count' => $num);
				$params['index']     = $row->index_folder;
				$params['type']     = $row->index_folder;
				if($user_id){
					$params['id']    = $user_id;
				}
				//refesh data sau 1s
				$params['refresh']	=	1;
				$params['retry_on_conflict']	=	10;
				$params['ignore']	=	array('404', '409');
				$this->search_engine->update($params);
			}		
		}
	}
	/**
	 * update into index search
	 * @param unknown $update_data
	 * @param string $user_id
	 */
	public function update($update_data, $country_id = 0, $user_id = false){
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$country_id}' OR country_id = 0");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);

		foreach($rows AS $row){
			$params = array();
			$params['body']['doc']  = $update_data;
			$params['index']     = $row->index_folder;
			$params['type']     = $row->index_folder;
			if($user_id){
				$params['id']    = $user_id;
			}
			$params['refresh']	=	1;
			$params['ignore']	=	array('404');
			try {
				$this->search_engine->update($params);
			}catch (Exception $e) {
// 			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}		
	}
	public function updateDisplayStatus($user_id, $status = Elasticsearch::HIDE_ON_SEARCH_STATUS){
		$body	=	array(
				'display_search'	=>	$status
		);
		
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$country_id}' OR country_id = 0");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);
		
		foreach($rows AS $row){
			$params = array();
			$params['body']['doc']  = $body;
			$params['index']     = $row->index_folder;
			$params['type']     = $row->index_folder;
			$params['id']    = $user_id;
			$params['ignore']	=	array('404');
			$this->search_engine->update($params);
		}		
	}
	public function updateIndexAllUser(){
		
		//update mapping
		$index_rows = SysSearchIndex::model()->findAll();		
		foreach($index_rows AS $row){
			$myTypeMapping = 				array(
					'properties'	=>	array(
							'username' => array(
									'type' => 'string',
									"index" => "not_analyzed"
							),
							'avatar' => array(
									'type' => 'string',
							),
							'looking_for' => array(
									'type' => 'string',
							),
							'user_id' => array(
									'type' => 'double',
									"index" => "not_analyzed"
							),
							'friendlist' => array(
									'type' => 'string',
							),
							'total_candy_alert' => array(
									'type' => 'integer',
									"index" => "not_analyzed"
							),
							'total_photo_request' => array(
									'type' => 'integer',
									"index" => "not_analyzed"
							),
							'total_addfriend_request' => array(
									'type' => 'integer',
									"index" => "not_analyzed"
							),
							'total_alert' => array(
									'type' => 'integer',
							),
							'total_visitor' => array(
									'type' => 'integer',
							),
							'total_message' => array(
									'type' => 'integer',
							),
							'current_country_id' => array(
									'type' => 'integer',
							),
							'current_state_id' => array(
									'type' => 'integer',
							),
							'current_city_id' => array(
									'type' => 'integer',
							),
							'current_district_id' => array(
									'type' => 'integer',
							),
							'ordering' => array(
									'type' => 'integer',
							),
							'status' => array(
									'type' => 'integer',
							),
							'is_vip' => array(
									'type' => 'integer',
							),
							'display_search' => array(
									'type' => 'integer',
							),
							'current_device' => array(
									'type' => 'integer',
							),
							'age' => array(
									'type' => 'integer'
							),
							'birthday_year' => array(
									'type' => 'integer'
							),
							'birthday' => array(
									'type' => 'integer'
							),
							'birthday_date' => array(
									'type' => 'string',
									"index" => "not_analyzed"
							),
							'sex_role' =>  array(
							'type' => 'integer'
									),
									'user_sort' =>  array(
									'type' => 'nested',
									'properties'	=>	array(
									'user_id'=>	array('type' => 'integer'),
									'sex_role'	=>	array('type' => 'integer'),
									'current_country_id'	=>	array('type' => 'integer'),
									'current_state_id'	=>	array('type' => 'integer'),
									'current_city_id'	=>	array('type' => 'integer'),
									'current_district_id'	=>	array('type' => 'integer'),
									'ordering'	=>	array('type' => 'integer'),
									'sort_type'	=>	array('type' => 'string'),
									'have_avatar'	=>	array('type' => 'integer'),
									)
									),
									'top_ordering' => array(
									'type' => 'integer',
									),
									'smoke' => array(
									'type' => 'integer',
									),
									'have_avatar' => array(
									'type' => 'integer',
									),
									'weight' => array(
									'type' => 'float',
									),
									'last_activity' => array(
									'type' => 'double',
									),
									'last_login' => array(
									'type' => 'double',
									),
									'latitude' => array(
									'type' => 'double',
									),
									'longitude' => array(
									'type' => 'double',
									),
									'location'	=>	array(
									'type' => 'geo_point'
											),
											'sexuality'	=>	array(
											'type' => 'integer'
													),
													'height' => array(
													'type' => 'float',
													),
													'email' => array(
													'type' => 'string',
													),
					)
			);
			$indexParams	=	array();
			$indexParams['index'] = $row->index_folder;
			$indexParams['type']  = $row->index_folder;
			$indexParams['body'][$row->index_folder] = $myTypeMapping;
			$this->search_engine->indices()->putMapping($indexParams);		
		}	
		
		/*
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("s.*");
		$cmd->from('user_data_search s');
		
		$dataUser = $cmd->queryAll();
		
		foreach ($dataUser AS $user_row) {
			//update in all index
			foreach ($index_rows AS $row){
				$body	=	array(
						'is_vip'	=>	0
				);
				$params = array();
				$params['body']['doc']  = $body;
				$params['index']     = $row->index_folder;
				$params['type']     = $row->index_folder;
				$params['id']    = $user_row['user_id'];
				$params['ignore']	=	array('404');
				$this->search_engine->update($params);								
			}
		}
		*/
		
	}
	public function updatePositionAllUser($limit = 2490){
		set_time_limit(0);
		$cmd = Yii::app()->db->createCommand();
		$cmd->select("ps.user_id, pl.current_country_id,c.name AS country_name, s.name AS state_name, ct.name AS city_name, d.name AS district_name");
		$cmd->from('usr_profile_settings ps');
		$cmd->leftJoin('usr_profile_location pl', 'ps.user_id = pl.user_id');
		$cmd->leftJoin('sys_country c', 'c.id= pl.current_country_id');
		$cmd->leftJoin('sys_state s', 's.id= pl.current_state_id');
		$cmd->leftJoin('sys_city ct', 'ct.id= pl.current_city_id');
		$cmd->leftJoin('sys_district d', 'd.id= pl.current_district_id');
		$cmd->andWhere("(ps.latitude IS NULL AND ps.longitude IS NULL) OR ps.longitude = 0");
		$cmd->setGroup('ps.user_id');
		$cmd->limit($limit);
		$dataUser = $cmd->queryAll();
		
		foreach ($dataUser AS $dataUser) {
			$address	=	'';
			if(!empty($dataUser['country_name'])){
				$address	=	$dataUser['country_name'];
			}
			if(!empty($dataUser['state_name'])){
				$address	=	"{$dataUser['state_name']} {$address}";
			}
			if(!empty($dataUser['city_name'])){
				$address	=	"{$dataUser['city_name']} {$address}";
			}	
			if(!empty($dataUser['district_name'])){
				$address	=	"{$dataUser['district_name']} {$address}";
			}		
			

			
			$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $dataUser['user_id']));
			if($model){
					$location	=	Location::getLongitude_Latitude_v2($address);
					$this->updatePosition($dataUser['user_id'], $location['latitude'], $location['longitude']);
					
					$model->latitude	=	$location['latitude'];
					$model->longitude	=	$location['longitude'];
					$model->save();	

			}
			echo "update location #user_id {$dataUser['user_id']}\n";
			sleep(2);
		}
	}
	
	/**
	 *
	 * search friend by a email list
	 * @param a email or array email $email_list
	 * @param int $offset
	 * @param int $limit
	 */
	public function findFriendByEmail($email_list, $user_id_except = false, $offset = 0, $limit = 100){
		$email_friend	=	array();
		$list_email		=	array();
		$this->fields_search	=	'email';
		$this->search_keyword_type	=	'query';
		
		foreach($email_list AS $email){
			if(!empty($email['email'])){
				$search_conditions = array(
		            'keyword' => $email['email'],
		            'country_id'	=>	0
		        ); 
		
				$data_search	=	$this->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
				if($data_search['total'] > 0){
					$email_friend[]	=	current($data_search['data']);
					$list_email[]	=	$email['email'];
				}
			}
		}		
		return array('email_list' => $list_email,'email_friends' => $email_friend, 'total' => sizeof($email_friend));
	}	
	public function setSortRules($script = null, $type = 'desc'){
		$this->sort_rule_script	=	$script;
		$this->sort_rule_type	=	$type;
	}
	public function checkOnlineStatus($array_user_id){
		//get index informations
		$row = SysSearchIndex::model()->find("country_id = 0");
		$array = array();
		$params = CParams::load ();
		$img_webroot_url	=	$params->params->img_webroot_url;
						
		if($row){
			$filter	=	array();
			//start search with index
			$search_params	=	array();
			$search_params['index']	=	$row['index_folder'];
			$search_params['from']	=	0;
			$search_params['size']	=	1000000;
				
			$search_params['body']['query']['filtered']['filter']	=	array(
				'ids'	=>	array(
					'type'	=>	$row['index_folder'],
					'values' => $array_user_id
				)
			);
			
			$index_data	=	$this->search_engine->search($search_params);
			if($index_data['hits']['total'] > 0){
				foreach($index_data['hits']['hits'] AS $item){
					$item	=	$item['_source'];
					if($item['have_avatar']){
						$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
					}else{
						$avatar	=	$item['avatar'];
					}
					$check_online	=	(int)((time() - $item['last_activity']) <= Yii::app()->params->Elastic['update_activity_time']);
					if($check_online){
						$array['online'][$item['user_id']]	=	array('alias_name' => $item['alias_name'], 'avatar' => $avatar);
					}else{
						$array['offline'][$item['user_id']]	=	array('alias_name' => $item['alias_name'], 'avatar' => $avatar);;
					}
				}
			}
		}
		return $array;
		
	}
	public function updatePositionByAddress($user_id){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("s.country_name, s.state_name, s.city_name, s.district_name, s.current_country_id, s.user_id");
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");
		$dataUser = $cmd->queryRow();

		$address	=	'';
		if(!empty($dataUser['country_name'])){
			$address	=	$dataUser['country_name'];
		}
		if(!empty($dataUser['state_name'])){
			$address	=	"{$dataUser['state_name']} {$address}";
		}
		if(!empty($dataUser['city_name'])){
			$address	=	"{$dataUser['city_name']} {$address}";
		}	
		if(!empty($dataUser['district_name'])){
			$address	=	"{$dataUser['district_name']} {$address}";
		}		
		
		$location	=	Location::getLongitude_Latitude_v2($address);			
		$this->updatePosition($user_id, $location["latitude"], $location["longitude"]);
		
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $dataUser['user_id']));
		$model->latitude	=	$location["latitude"];
		$model->longitude	=	$location["longitude"];
		$model->save();
	}
	public function updatePosition($user_id, $latitude, $longitude){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");
		
		$dataUser = $cmd->queryRow();
		
		if($dataUser){
			$body	=	array(
					'latitude'	=>	$latitude,
					'longitude'	=>	$longitude,
					'location'	=>	array('lat' => $latitude, 'lon' => $longitude)
			);
	
			//get index informations
			$CDbCriteria = new CDbCriteria();
			$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
			$rows = SysSearchIndex::model()->findAll($CDbCriteria);
			
			foreach($rows AS $row){
				$params = array();
				$params['body']['doc']  = $body;
				$params['index']     = $row->index_folder;
				$params['type']     = $row->index_folder;
				$params['id']    	= $user_id;
				$params['retry_on_conflict']	=	10;
				$params['ignore']	=	array('404', '409');
				$this->search_engine->update($params);
			}	
		}	
	}
	public function updateLastActivity($user_id, $time = false, $device = Elasticsearch::DEVICE_PC){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");
		$dataUser = $cmd->queryRow();
		
		//process with avatar

		if(!$time){
			$time	=	time() - Yii::app()->params->Elastic['update_activity_time'];
		}
		if($dataUser){
			$body	=	array(
					'last_activity'	=>	$time,
					'last_login'	=>	$dataUser['last_login'],
					'current_device'	=>	$device
			);
			if($dataUser['last_login'] == 0){
				$body['last_login']	=	time();
			}
			
			//get index informations
			$CDbCriteria = new CDbCriteria();
			$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
			$rows = SysSearchIndex::model()->findAll($CDbCriteria);
			try{
				foreach($rows AS $row){
					$params = array();
					$params['body']['doc']  = $body;
					$params['index']     = $row->index_folder;
					$params['type']     = $row->index_folder;
					$params['id']    = $user_id;
					$params['retry_on_conflict']	=	10;
					$params['ignore']	=	array('404', '409');
					$this->search_engine->update($params);
				}	
			}catch (Exception $e){
				
			}
		}	
	}
	public function checkIndexExist($index){
		$params	=	array();
		$params['index'] = isset($index['index'])	?	$index['index']	:	false;
		$params['type'] = isset($index['type'])	?	$index['type']	:	false;
		$params['id'] = isset($index['id'])	?	$index['id']	:	false;
		$check_index_exist	=	$this->search_engine->exists($params);
		return $check_index_exist;
	}
	public function registerSearchIndex($user_identification){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");		
		$cmd->from('user_data_search s');
		
		if(preg_match('@^[0-9]*$@i', $user_identification)){
			$cmd->andWhere("user_id = {$user_identification}");
		}else{
			$cmd->andWhere("username = '{$user_identification}'");
		}
		
		$dataUser = $cmd->queryRow();
		if(!$dataUser){
			return false;
		}
		
		//process with avatar
		$return = $this->getAvatar($dataUser);
		$have_avatar	=	$return['have_avatar'];
		$avatar = $return['avatar'];
		
		if($have_avatar){
			$last_activity	=	time() - 4000;
		}else{
			$last_activity	=	0;
		}		
		 
		$sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
		$sex_role = isset($sex_roles_title[$dataUser['sex_role']]) ? $sex_roles_title[$dataUser['sex_role']] : '';
		$body	=	array(
				'user_id' => $dataUser['user_id'],
				'friendlist'	=>	'',
				'total_photo_request'	=>	0,
				'total_addfriend_request'	=>	0,
				'total_alert'	=>	0,
				'total_alert'	=>	0,
				'total_message'	=>	0,
				'total_visitor'	=>	0,
				'current_country_id'	=>	$dataUser['current_country_id'],
				'current_state_id'	=>	$dataUser['current_state_id'],
				'birthday_year'	=>	$dataUser['birthday_year'],
				'current_city_id'	=>	$dataUser['current_city_id'],
				'country_name'	=>		$dataUser['country_name'],
				'state_name'	=>		$dataUser['state_name'],
				'total_friends'	=>		$dataUser['total_friends'],
				'current_district_id'	=>	$dataUser['current_district_id'],
				//'height'	=>		$dataUser['height'],
				//'weight'	=>		$dataUser['weight'],
				'looking_for'	=>		$dataUser['looking_for'],
				'smoke'	=>		$dataUser['smoke'],
				'status'	=>		intval($dataUser['status']),
				'is_vip'	=>		intval($dataUser['is_vip']),
				'display_search'	=>		intval($dataUser['display_search']),
				'current_device'	=>	Elasticsearch::DEVICE_PC,
				'have_avatar'	=>	$have_avatar,
				'body'	=>		$dataUser['body'],
				'relationship'	=>		$dataUser['relationship'],
				'ethnic_id'	=>		$dataUser['ethnic_id'],
				'safer_sex'	=>		$dataUser['safer_sex'],
				'online_lookingfor'	=>	$dataUser['online_lookingfor'],
				'sex_role'	=>	$dataUser['sex_role'],
				'last_activity'	=>	$last_activity,
				'latitude'	=>	'0',
				'longitude'	=>	'0',
				'location'	=> array('lat' => '0', 'lon' => '0'),
				'last_login'	=>	$dataUser['last_login'],
				'sex_role_name'	=>	$sex_role,
				'username'	=>	$dataUser['username'],
				'sexuality'	=>	$dataUser['sexuality'],
        		'ordering'	=>	'30',
        		'top_ordering'	=>	'30',
				'avatar'		=>		$avatar,
				'alias_name'		=>		$dataUser['alias_name'],
				'age'	=>	$dataUser['age'],
				'birthday'	=>	$dataUser['birthday'],
				'birthday_date'	=>	date('m-d', $dataUser['birthday']),
				'email'	=>	$dataUser['email'],
				'user_sort'	=>	array(
						'sex_role' => $dataUser['sex_role'],
						'have_avatar' => $have_avatar,
						'current_district_id' => $dataUser['current_district_id'],
						'current_city_id' => $dataUser['current_city_id'],
						'current_country_id' => $dataUser['current_country_id'],
						'current_state_id' => $dataUser['current_state_id'],
						'sort_type' => $dataUser['sex_role'] . $dataUser['current_country_id'] . $dataUser['current_state_id'],
						'user_id' => $dataUser['user_id']
				)				
		);
		//convert unit
		if($dataUser['measurement'] == '1'){
			$body['height']	=	$dataUser['height'];
			$body['weight']	=	$dataUser['weight'];
		}else{
			//convert to cm / kg
			$body['height']	=	$dataUser['height'] * Yii::app()->params['feet_to_cm'];
			$body['weight']	=	$dataUser['weight'] * Yii::app()->params['pound_to_kg'];
		}
					 
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);

		foreach($rows AS $row){
			//create index
			$check_create_index	=	$this->createIndex($row->index_folder, $row->index_folder);
			
				$params = array();
				$params['body']  = $body;
				$params['index']     = $row->index_folder;
				$params['type']     = $row->index_folder;
				$params['id']    = $dataUser['user_id'];
				$params['ignore']	=	array('404');
				$params['refresh']	=	false;
					
				$check	=	$this->search_engine->index($params);
		}
		//$this->updatePositionByAddress($dataUser['user_id']);
	}
	public function changeCountrySearchIndexUser($user_id, $new_country_id, $old_country_id){

		//remove in old country  index
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$old_country_id}'");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);
		foreach($rows AS $row){
			$params = array();
			$params['index']     = $row->index_folder;
			$params['type']     = $row->index_folder;
			$params['id']    = $user_id;
			$params['ignore']	=	array('404');
			$this->search_engine->delete($params);
		}

		//add to new country index
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");		
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");

		$dataUser = $cmd->queryRow();
	  
		$sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
		$sex_role = isset($sex_roles_title[$dataUser['sex_role']]) ? $sex_roles_title[$dataUser['sex_role']] : '';
	  
		//process with avatar
		$return = $this->getAvatar($dataUser);
		$have_avatar	=	$return['have_avatar'];
		$avatar = $return['avatar'];
		
		if($have_avatar){
			$last_activity	=	time();
		}else{
			$last_activity	=	0;
		}
		
		$body	=	array(
				'user_id' => $dataUser['user_id'],
				'current_country_id'	=>	$dataUser['current_country_id'],
				'current_state_id'	=>	$dataUser['current_state_id'],
				'birthday_year'	=>	$dataUser['birthday_year'],
				'birthday'	=>	$dataUser['birthday'],
				'birthday_date'	=>	date('m-d', $dataUser['birthday']),
				'current_city_id'	=>	$dataUser['current_city_id'],
				'country_name'	=>		$dataUser['country_name'],
				'state_name'	=>		$dataUser['state_name'],
				'total_friends'	=>		$dataUser['total_friends'],
				'current_district_id'	=>	$dataUser['current_district_id'],
				//'height'	=>		$dataUser['height'],
				//'weight'	=>		$dataUser['weight'],
				'looking_for'	=>		$dataUser['looking_for'],
				'smoke'	=>		$dataUser['smoke'],
				'status'	=>		'1',
				'is_vip'	=>		$dataUser['is_vip'],
				'location'	=> array('lat' => '0', 'lon' => '0'),
				'latitude'	=>	'0', 
				'longitude'	=>	'0',
				'sexuality'	=>	$dataUser['sexuality'],
				'have_avatar'	=>	$have_avatar,
				'last_activity'	=>	$last_activity,
				'last_login'	=>	$dataUser['last_login'],
				'body'	=>		$dataUser['body'],
				'relationship'	=>		$dataUser['relationship'],
				'ethnic_id'	=>		$dataUser['ethnic_id'],
				'safer_sex'	=>		$dataUser['safer_sex'],
				'online_lookingfor'	=>	$dataUser['online_lookingfor'],
				'sex_role'	=>	$dataUser['sex_role'],
				'sex_role_name'	=>	$sex_role,
				'username'	=>	$dataUser['username'],
        		'ordering'	=>	30,
        		'top_ordering'	=>	30,
				'avatar'		=>		$avatar,
				'alias_name'		=>		$dataUser['alias_name'],
				'age'	=>	$dataUser['age'],
				'email'	=>	$dataUser['email'],
				'user_sort'	=>	array(
						'sex_role' => $dataUser['sex_role'],
						'have_avatar' => $have_avatar,
						'current_district_id' => $dataUser['current_district_id'],
						'current_city_id' => $dataUser['current_city_id'],
						'current_country_id' => $dataUser['current_country_id'],
						'current_state_id' => $dataUser['current_state_id'],
						'sort_type' => $dataUser['sex_role'] . $dataUser['current_country_id'] . $dataUser['current_state_id'],
						'user_id' => $dataUser['user_id']
				)				
		);
		
		if($dataUser['measurement'] == '1'){
			$body['height']	=	$dataUser['height'];
			$body['weight']	=	$dataUser['weight'];
		}else{
			//convert to cm / kg
			$body['height']	=	$dataUser['height'] * Yii::app()->params['feet_to_cm'];
			$body['weight']	=	$dataUser['weight'] * Yii::app()->params['pound_to_kg'];
		}
		
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$new_country_id}'");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);

		foreach($rows AS $row){			
			$params = array();
			$params['body']  = $body;
			$params['index']     = $row->index_folder;
			$params['type']     = $row->index_folder;
			$params['id']    = $dataUser['user_id'];
			$params['ignore']	=	array('404');
			$this->search_engine->index($params);
		}
	}
	public function updateSearchIndexUser($user_id){
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");		
		$cmd->from('user_data_search s');
		$cmd->andWhere("user_id = {$user_id}");

		$dataUser = $cmd->queryRow();
		if(empty($dataUser)){
		    return false;
		}

		$sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
		$sex_role = isset($sex_roles_title[$dataUser['sex_role']]) ? $sex_roles_title[$dataUser['sex_role']] : '';

		//process with avatar
		$return = $this->getAvatar($dataUser);
		$have_avatar	=	$return['have_avatar'];
		$avatar = $return['avatar'];
		if($have_avatar){
			$last_activity	=	time();
		}else{
			$last_activity	=	0;
		}
		$body	=	array(
				'user_id' => $dataUser['user_id'],
				'current_country_id'	=>	$dataUser['current_country_id'],
				'current_state_id'	=>	$dataUser['current_state_id'],
				'birthday_year'	=>	$dataUser['birthday_year'],
				'birthday'	=>	$dataUser['birthday'],
				'birthday_date'	=>	date('m-d', $dataUser['birthday']),
				'current_city_id'	=>	$dataUser['current_city_id'],
				'country_name'	=>		$dataUser['country_name'],
				'state_name'	=>		$dataUser['state_name'],
				'total_friends'	=>		$dataUser['total_friends'],
				'current_district_id'	=>	$dataUser['current_district_id'],
				//'height'	=>		$dataUser['height'],
				'have_avatar'	=>	$have_avatar,
				//'weight'	=>		$dataUser['weight'],
				'looking_for'	=>		$dataUser['looking_for'],
				'smoke'	=>		$dataUser['smoke'],
				'status'	=>		intval($dataUser['status']),
				'is_vip'	=>		intval($dataUser['is_vip']),
				'display_search'	=>		intval($dataUser['display_search']),
				'body'	=>		$dataUser['body'],
				'relationship'	=>		$dataUser['relationship'],
				'ethnic_id'	=>		$dataUser['ethnic_id'],
				'safer_sex'	=>		$dataUser['safer_sex'],
				'online_lookingfor'	=>	$dataUser['online_lookingfor'],
				'last_activity'	=>	$last_activity,
				'sex_role'	=>	$dataUser['sex_role'],
				'sex_role_name'	=>	$sex_role,
				'sexuality'	=>	$dataUser['sexuality'],
				'username'	=>	$dataUser['username'],
				'avatar'		=>		$avatar,
				'alias_name'		=>		$dataUser['alias_name'],
				'age'	=>	$dataUser['age'],
				'email'	=>	$dataUser['email'],
				'user_sort'	=>	array(
						'sex_role' => $dataUser['sex_role'], 
						'have_avatar' => $have_avatar, 
						'current_district_id' => $dataUser['current_district_id'], 
						'current_city_id' => $dataUser['current_city_id'],  
						'current_country_id' => $dataUser['current_country_id'],
						'current_state_id' => $dataUser['current_state_id'], 
						'sort_type' => $dataUser['sex_role'] . $dataUser['current_country_id'] . $dataUser['current_state_id'], 
						'user_id' => $dataUser['user_id']
				)
				
		);
		if($dataUser['measurement'] == '1'){
			$body['height']	=	$dataUser['height'];
			$body['weight']	=	$dataUser['weight'];
		}else{
			//convert to cm / kg
			$body['height']	=	$dataUser['height'] * Yii::app()->params['feet_to_cm'];
			$body['weight']	=	$dataUser['weight'] * Yii::app()->params['pound_to_kg'];			
		}
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id='{$dataUser['current_country_id']}' OR country_id = 0");
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);

		foreach($rows AS $row){
			$params = array();
			$params['body']['doc']  = $body;
			$params['index']     = $row->index_folder;
			$params['type']     = $row->index_folder;
			$params['id']    = $dataUser['user_id'];
			$params['retry_on_conflict']	=	10;
			$params['ignore']	=	array('404');
			$this->search_engine->update($params);
		}
	}
	public function querySearchIndex($conditions = array(), $user_id_except = false, $offset = 0, $limit = 20) {
		 
		$index_name = $this->buildSearchIndexName($conditions);
		//check index exist
		$check_index	=	array(
				'index'	=>	$index_name
		);		
		if(!$this->checkIndexExist($check_index)){
			$index_data	=	$this->buildSearchIndex($conditions);
			sleep(2);
		}
		 
		$filter	=	array();
		//start search with index
		$search_params	=	array();
		$search_params['index']	=	$index_name;
		$search_params['type']	=	$index_name;

		//remove any users
		if($user_id_except){
			$must_not['bool']['must_not']	=	array('ids'=>array('type'	=>	$index_name, 'values' => $user_id_except));
			$filter['and']['filters']['and'][]	=	$must_not;
		}
		//$filter['and']['filters']['and'][]	=	array('term' => array('status' => '1'));
		$filter['and']['filters']['and'][]	=	array('term' => array('display_search' => 1));

		//set offset and limit for paging
		$search_params['from']	=	$offset;
		$search_params['size']	=	$limit;

		
		//order search result
		$sort_by = array();
		if(!$this->sort_rule_script){
			$time	=	time() - Yii::app()->params->Elastic['update_activity_time'];
			$time_is_newregister	=	time() - Yii::app()->params->Elastic['time_is_newregister'];
			$this->sort_rule_script	=	
			"(doc['last_activity'].value > {$time} and doc['is_vip'].value == 1 and doc['have_avatar'].value == 1)	?	(doc['last_activity'].value + 9000000)	:	(
				(doc['last_activity'].value > {$time} and doc['have_avatar'].value == 1 and doc['last_login'].value > $time_is_newregister) ?
				(doc['user_id'].value + 7500000):((doc['last_activity'].value > {$time} and doc['have_avatar'].value == 1) ? min(doc['user_id'].value + 6000000, 7500000)	: 
				((doc['have_avatar'].value == 1)? doc['user_id'].value	:	0)))";
		}
		$sort_by[]	=	array(
				'_script'	=>	array(
				'script'	=>	$this->sort_rule_script,
				'type'	=>	'number',
				"order" => $this->sort_rule_type
			)
		);	
		
		$search_params['body']['sort']	=	$sort_by;
		
        //search by keyword
        $query	=	array();
        if (!empty($conditions['keyword'])) {
        	switch ($this->search_keyword_type){
        		case 'regex': 
        			$query['regexp'][$this->fields_search] = ".*{$conditions['keyword']}.*";
        		break;
        		case 'query':
        			$query['match_phrase'][$this->fields_search] = $conditions['keyword'];
        		break;        				
        	}
        }
        
        //filter	 
        if (isset($conditions['filter'])) {
        		foreach ($conditions['filter'] AS $fieldname => $field_value) {
        			foreach ($field_value AS $k => $v) {
        				switch ($k) {
        					case 'in':
        						$filter['and']['filters']['and'][]	=	array('terms' => array($fieldname => $v, 'execution' => 'or'));
        						break;
        					case 'in_set':
        						$in_set	=	array();
        						foreach($v AS $lv){
        							$in_set['or']['filters'][]	=	array('regexp' => array($fieldname => ".*($lv).*"));
        						}
        						$filter['and']['filters']['and'][]	=	$in_set;
        						break;
        					case '=':
        						$filter['and']['filters']['and'][]	=	array('term' => array($fieldname => $v));
        						break;
        					case '<':
        						$filter['and']['filters']['and'][]	=	array('range' => array($fieldname => array('lt' => $v)));
        						break;
        					case '<=':
        						$filter['and']['filters']['and'][]	=	array('range' => array($fieldname => array('lte' => $v)));
        						break;
        					case '>':
        						$filter['and']['filters']['and'][]	=	array('range' => array($fieldname => array('gt' => $v)));
        						break;
        					case '>=':
        						$filter['and']['filters']['and'][]	=	array('range' => array($fieldname => array('gte' => $v)));
        						break;
        					default:
        						$filter['and']['filters']['and'][]	=	array('terms' =>
        						array($fieldname => $v
        						), 'execution' => 'or');
        						break;
        				}
        			}
        		}
        }
        
        if (isset($conditions['filter_cal'])) {
        		$filter_cal = array();
        		foreach ($conditions['filter_cal'] AS $fieldname => $field_value) {
        			foreach ($field_value AS $k => $v) {
        				$type	=	key($v);
        				$value	=	current($v);
        				switch ($type) {
        					case '<':
        						$filter_cal[] = array('range' => array($fieldname => array('lt' => $value)));
        						break;
        					case '<=':
        						$filter_cal[] = array('range' => array($fieldname => array('lte' => $value)));
        						break;
        					case '>':
        						$filter_cal[] = array('range' => array($fieldname => array('gt' => $value)));
        						break;
        					case '>=':
        						$filter_cal[] = array('range' => array($fieldname => array('gte' => $value)));
        						break;
        					case 'range':
        						$filter_cal[] = array('range' => array($fieldname => array(key($value[0]) => current($value[0]), key($value[1]) => current($value[1]))));
        						break;
      						
        				}
        			}
        		}
        		if(sizeof($filter_cal)){
        			foreach($filter_cal AS $row){
        				$filter['and']['filters']['and'][]	=	$row;
        			}
        		}
        	}
			
        	if(sizeof($query)){
        		$search_params['body']['query']['filtered']['query']	=	$query;
        	}
        	if(sizeof($filter)){
        		$search_params['body']['query']['filtered']['filter']	=	$filter;
        	}
        	
        //process filter
        $index_data	=	$this->search_engine->search($search_params);
        
        if($index_data['hits']['total'] > 0){
        	if($index_data['hits']['total'] <= $limit + $offset){
        		$show_more  =   false;
        	}else{
        		$show_more  =   true;
        	}
        }else{
        	$show_more	=	false;
        }
        return array('data' => $index_data['hits']['hits'], 'total' => $index_data['hits']['total'], 'show_more' => $show_more, 'fulldata' => $index_data['hits']['hits']);
	}
	public function reOrderUsers($limit = 200){

		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("(UNIX_TIMESTAMP() - date_last_updated) >= 3600");
		$CDbCriteria->limit	=	$limit;
		$rows = SysSearchIndex::model()->findAll($CDbCriteria);
		if ($rows) {
			foreach($rows AS $row_index){
				$model = SysSearchIndex::model()->findByAttributes(array('id' => $row_index->id));
				$my_attributes['date_last_updated']	=	time();
				$model->attributes = $my_attributes;
				$model->save();

				//parse json to php array
				$conditions	=	json_decode($model->search_conditions, true);
								
				if(isset($conditions['country_id'])){
					$conditions['filter']	=	array('current_country_id' => array('=' => $conditions['country_id']));
				}
				$cmd = Yii::app()->db_search->createCommand();
				$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");		
				$cmd->from('user_data_search s');
				 
				$list_users_notshow	=	isset(Yii::app()->params->list_users_notshow)	?	Yii::app()->params->list_users_notshow	:	'';
				
				if(!empty($list_users_notshow)){
					$cmd->andWhere("user_id NOT IN($list_users_notshow)");
				}		
				/*
				if(!empty($list_users_notshow)){
					$cmd->andWhere("status=1 AND user_id NOT IN($list_users_notshow)");
				}else{
					$cmd->andWhere("status=1");
				}
				*/
		
				if (sizeof($conditions) > 0) {
					if (isset($conditions['filter'])) {
						foreach ($conditions['filter'] AS $fieldname => $field_value) {
							foreach ($field_value AS $k => $v) {
								switch ($k) {
									case 'in':
										$cmd->andWhere("{$fieldname} IN (" . implode(',', array_map('add_str_queryin', $v)) . ")");
										break;
									case 'in_set':
										$in_set	=	array();
										foreach($v AS $lv){
											$in_set[]	=	"FIND_IN_SET( {$lv}, `{$fieldname}` )";
										}
										$cmd->andWhere(implode(' OR ', $in_set));
										break;
									case '=':
										$cmd->andWhere("{$fieldname} = $v");
										break;
									case '<':
										$cmd->andWhere("{$fieldname} < $v");
										break;
									case '<=':
										$cmd->andWhere("{$fieldname} <= $v");
										break;
									case '>':
										$cmd->andWhere("{$fieldname} > $v");
										break;
									case '>=':
										$cmd->andWhere("{$fieldname} >= $v");
										break;
									default:
										$cmd->andWhere("{$fieldname} IN (" . implode(',', $v) . ")");
										break;
								}
							}
						}
					}

					//sort
					if (isset($conditions['sort_by'])) {
						$sort_by = array();
						foreach ($conditions['sort_by'] AS $key => $value) {
							$sort_by[] = "FIELD({$key}, {$value} ) DESC";
						}
						$cmd->order(implode(',', $sort_by));
					}
				}else{
					$cmd->order('user_id DESC');
				}
				$dataUser = $cmd->queryAll();
				$i = 1;
				foreach ($dataUser AS $row) {
					
					$body	=	array(
						'ordering'	=>	$i,
						'top_ordering'	=>	$i						
					);
					$params = array();
					$params['body']['doc']  = $body;
					$params['index']     = $row_index->index_folder;
					$params['type']     = $row_index->index_folder;
					$params['id']    = $row['user_id'];
					$params['ignore']	=	array('404');
					$this->search_engine->update($params);			
					$i++;		
				}
			}
		}
	}
	/**
	 * build index search data
	 * @param array $conditions
	 * @return \Zend_Search_Lucene
	 */
	public function buildSearchIndex($conditions = array(), $set_indexname = false, $limit = false, $offset = 0) {
		set_time_limit(0);
		
		//only filter by country
		$conditions = is_array($conditions) ? $conditions : array();
		
		if($set_indexname){
			$index_name	=	$set_indexname;
		}else{
			$index_name	=	$this->buildSearchIndexName($conditions);
		}
		
		if($conditions['country_id'] == '0'){
			unset($conditions['filter']);
		}else{
			$conditions['filter']	=	array('current_country_id' => array('=' => $conditions['country_id']));
		}

		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");		
		$cmd->from('user_data_search s');
		if($limit){
			$cmd->limit($limit, $offset);
		}

		$list_users_notshow	=	isset(Yii::app()->params->list_users_notshow)	?	Yii::app()->params->list_users_notshow	:	'';
		if(!empty($list_users_notshow)){
			$cmd->andWhere("user_id NOT IN($list_users_notshow)");
		}		
			
		if (sizeof($conditions) > 0) {
			if (isset($conditions['filter'])) {
				foreach ($conditions['filter'] AS $fieldname => $field_value) {
					foreach ($field_value AS $k => $v) {
						switch ($k) {
							case 'in':
								$cmd->andWhere("{$fieldname} IN (" . implode(',', array_map('add_str_queryin', $v)) . ")");
								break;
							case 'in_set':
								$in_set	=	array();
								foreach($v AS $lv){
									$in_set[]	=	"FIND_IN_SET( {$lv}, `{$fieldname}` )";
								}
								$cmd->andWhere(implode(' OR ', $in_set));
								break;
							case '=':
								$cmd->andWhere("{$fieldname} = $v");
								break;
							case '<':
								$cmd->andWhere("{$fieldname} < $v");
								break;
							case '<=':
								$cmd->andWhere("{$fieldname} <= $v");
								break;
							case '>':
								$cmd->andWhere("{$fieldname} > $v");
								break;
							case '>=':
								$cmd->andWhere("{$fieldname} >= $v");
								break;
							default:
								$cmd->andWhere("{$fieldname} IN (" . implode(',', $v) . ")");
								break;
						}
					}
				}
			}
			if (isset($conditions['sort_by'])) {
				$sort_by = array();
				foreach ($conditions['sort_by'] AS $key => $value) {
					$sort_by[] = "FIELD({$key}, {$value} ) DESC";
				}
				$cmd->order(implode(',', $sort_by));
			}

		}else{
			$cmd->order('user_id DESC');
		}
		$dataUser = $cmd->queryAll();
		
		//create index
		$this->createIndex($index_name, $index_name);

		$i = 1;
		$sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
		$dbrows	=	array();
		
		//default index
		$params = array();
		$params['body']  = array(
			'smoke' => '0',
			'have_avatar' => '0',
			'avatar'	=>	'0',
			'ordering' => '0', 
			'top_ordering' => '0', 
			'status' => '0', 
			'is_vip' => 0,
			'display_search'	=>	'0',
			'current_device'	=>	'0',
			'friendlist'	=>	'',
			'total_photo_request'	=>	0,
			'total_addfriend_request'	=>	0,
			'total_alert'	=>	0,
			'total_message'	=>	0,
			'total_visitor'	=>	0,
			'total_candy_alert'	=>	0,
			'current_country_id' => '0',
			'current_state_id' => '0',
			'current_city_id' => '0',
			'current_district_id' => '0',
			'height'	=>	'0',
			'weight'	=>	'0',
			'looking_for'	=>	'',
			'body'	=>	'0',
			'relationship'	=>	'0',
			'ethnic_id'	=>	'0',
			'safer_sex'	=>	'0',
			'online_lookingfor'	=>	'0',
			'sex_role'	=>	'0',
			'last_activity'	=>	'0',
			'last_login'	=>	0,
			'latitude'	=>	'0',
			'longitude'	=>	'0',
			'location'	=> array('lat' => '0', 'lon' => '0'),
			'sexuality'	=>	'0',
			'user_sort'	=>	array('sex_role' => 0, 'have_avatar' => 0, 'current_district_id' => 0, 'current_city_id' => 0,  'current_country_id' => 0,'current_state_id' => 0, 'ordering' => 0, 'sort_type' => '0', 'user_id' => '0')
		);
		$params['index']     = $index_name;
		$params['type']     = $index_name;
		$params['id']    = 0;		
		$this->search_engine->index($params);
		
		
		date_default_timezone_set('Asia/Saigon');
		foreach ($dataUser AS $row) {
			$sex_role = isset($sex_roles_title[$row['sex_role']]) ? $sex_roles_title[$row['sex_role']] : '';
			 
			//process with avatar
			$return = $this->getAvatar($row);
    		$have_avatar	=	$return['have_avatar'];
    		$avatar = $return['avatar'];
		
			if($have_avatar){
				$last_activity	=	time() - 3600;
			}else{
				$last_activity	=	0;
			}	
			//$my_friendlist	=	Friendship::model()->getAllFriendID($row['user_id']);
			$body	=	array(
				'user_id' => $row['user_id'],
				'current_country_id'	=>	$row['current_country_id'],
				'current_state_id'	=>	$row['current_state_id'],
				'birthday_year'	=>	$row['birthday_year'],
				'birthday'	=>	$row['birthday'],
				'birthday_date'	=>	date('m-d', $row['birthday']),
				'current_city_id'	=>	$row['current_city_id'],
				'country_name'	=>		$row['country_name'],
				'state_name'	=>		$row['state_name'],
				'total_friends'	=>		$row['total_friends'],
				'current_district_id'	=>	$row['current_district_id'],
				//'height'	=>		$row['height'],
				//'weight'	=>		$row['weight'],
				//'friendlist'	=>	implode(',', $my_friendlist),
				'friendlist'	=>	'',
				'total_photo_request'	=>	0,
				'total_addfriend_request'	=>	0,
				'total_alert'	=>	0,
				'total_visitor'	=>	0,
				'total_message'	=>	0,
				'looking_for'	=>		$row['looking_for'],
				'smoke'	=>		$row['smoke'],
				'status'	=>		'1',
				'is_vip'	=>		$row['is_vip'],
				'display_search'	=>		intval($row['display_search']),
				'current_device'	=>	Elasticsearch::DEVICE_PC,
				'have_avatar'	=>	$have_avatar,
				'body'	=>		$row['body'],
				'relationship'	=>		$row['relationship'],
				'ethnic_id'	=>		$row['ethnic_id'],
				'safer_sex'	=>		$row['safer_sex'],
				'online_lookingfor'	=>	$row['online_lookingfor'],
				'sex_role'	=>	$row['sex_role'],
				'user_sort'	=>	array('have_avatar' => $have_avatar,'user_id' => $row['user_id'],'current_district_id' => $row['current_district_id'],'current_city_id' => $row['current_city_id'],  'sex_role' => $row['sex_role'],'current_country_id' => $row['current_country_id'] ,'current_state_id' => $row['current_state_id'],'ordering'	=>	$i, 'sort_type' => $row['sex_role'] . $row['current_country_id'] . $row['current_state_id']),
				'sex_role_name'	=>	$sex_role,
				'username'	=>	$row['username'],
				'last_activity'	=>	$last_activity,
				'last_login'	=>	0,
				'latitude'		=>	'0',
				'longitude'		=>	'0',
				'location'	=> array('lat' => '0', 'lon' => '0'),
				'sexuality'	=>	$row['sexuality'],
				'avatar'		=>		$avatar,
				'alias_name'		=>		$row['alias_name'],
				'age'	=>	$row['age'],
				'email'	=>	$row['email'],
				'ordering'	=>	$i,
				'top_ordering'	=>	$i
			);
			if($row['measurement'] == '1'){
				$body['height']	=	$row['height'];
				$body['weight']	=	$row['weight'];
			}else{
				//convert to cm / kg
				$body['height']	=	$row['height'] * Yii::app()->params['feet_to_cm'];
				$body['weight']	=	$row['weight'] * Yii::app()->params['pound_to_kg'];
			}			
			
			$params = array();
			$params['body']  = $body;
			$params['index']     = $index_name;
			$params['type']     = $index_name;
			$params['id']    = $row['user_id'];
				
			$dbrows['hits']['hits'][]	=	array('_source' => $body);
			$this->search_engine->index($params);
			$i++;
			unset($row);
		}
		$dbrows['hits']['total']	=	$i - 1;
		return $dbrows;
	}
	/**
	 * general index search filename
	 * @param array $search_conditions
	 * @return string filename
	 */
	public function buildSearchIndexName($search_conditions = array(), $save = true) {
		$search_conditions = is_array($search_conditions) ? $search_conditions : array();

		$country_id	=	isset($search_conditions['country_id'])	?	$search_conditions['country_id']	:	0;
		
		$search_conditions_json = '';
		if (sizeof($search_conditions) > 1) {
			$search_conditions_json = json_encode($search_conditions);
		} 
		$search_index_name = 'index_' . $country_id;
		$search_index_name	=	$this->prefix_key . md5("index_{$country_id}");
		if ($save) {
			$CDbCriteria = new CDbCriteria();
			$CDbCriteria->addCondition("index_folder='{$search_index_name}'");
			$row = SysSearchIndex::model()->find($CDbCriteria);
			if (!$row) {
				$search_index = new SysSearchIndex();
				$search_index->index_folder = $search_index_name;
				$search_index->search_conditions = $search_conditions_json;
				$search_index->date_last_updated = time();
				$search_index->country_id = $country_id;
				$search_index->date_created = time();
				$search_index->save();
			}
		}

		return $search_index_name;
	}
	public function getFullUserInfo($ids, $limit){
		$index_name	=	'search_data_all';
		//start search
		$search_params	=	array();
		$search_params['index']	=	$index_name;
		$search_params['size']	=	$limit;
		$search_params['type']	=	$index_name;
		$search_params['body']['filter']['ids'] = array(
        		"type"		=>	$index_name,
        		'values'	=>	$ids
		);
		$index_data	=	$this->search_engine->search($search_params);
		$dbrows	=	false;
		if($index_data['hits']['total'] > 0){
			$dbrows	=	array();
			foreach($index_data['hits']['hits'] AS $row){
				$dbrows[$row['_id']]	=	$row['_source'];
			}
		}
		return $dbrows;
	}
	
	public function getAvatar($dataUser){
		$params = CParams::load ();
		$p150x0 = $params->params->uploads->avatar->p150x0;
		
        $return = array('avatar' => "/public/images/no-user.jpg", 'have_avatar'=>0);
	    if(!empty($dataUser['avatar'])){
	        if(is_numeric($dataUser['avatar'])){
	        	//$avatar = "http://{$params->params->img_webroot_url}/{$dataUser['photo_avatar_path']}/thumb160x160/{$dataUser['photo_avatar_name']}";
	        	$avatar = "/{$dataUser['photo_avatar_path']}/thumb160x160/{$dataUser['photo_avatar_name']}";
	        }else{
	            //$avatar = VAvatar::model()->urlAvatar($dataUser['avatar']);
	        	$avatar	=	 "/{$p150x0->p}/{$dataUser['avatar']}";
	        }
	        $return = array('avatar'=>$avatar, 'have_avatar'=>1);
	    }
	    return $return;
	}
	public function load($user_identification){
		
		//get index informations
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->addCondition("country_id = 0");
		$row = SysSearchIndex::model()->find($CDbCriteria);
		if($row){
		
			$search_params	=	array();
			$search_params['index']	=	$row->index_folder;
			$search_params['type']	=	$row->index_folder;
		
			$filter	=	array();
			
		
			if(!is_array($user_identification)){
				if(preg_match('@^[0-9]*$@i', $user_identification)){
					$filter['ids']	=	array(
							'type' => $row->index_folder,
							'values'	=>	is_array($user_identification)	?	$user_identification	:	array($user_identification)
					);
					$search_params['body']['query']['filtered']['filter']	=	$filter;
				}else{
					$query	=	array();
					$query['match_phrase']['username'] = $user_identification;
					$search_params['body']['query']['filtered']['query']	=	$query;
	
				}
			}else{
				if(preg_match('@^[0-9]*$@i', $user_identification[0])){
					$filter['ids']	=	array(
							'type' 		=> $row->index_folder,
							'values'	=>	$user_identification
					);
					$search_params['body']['query']['filtered']['filter']	=	$filter;
				}else{
					$query	=	array();
					//$query_string	=	implode(' OR ', $user_identification);
					$query['terms']['username'] = $user_identification;
					$search_params['body']['query']['filtered']['query']	=	$query;
				
				}				
			}	
				
			$index_data	=	$this->search_engine->search($search_params);			
			
			if(sizeof($index_data['hits']['hits'])){
				if(!is_array($user_identification)){
					return $index_data['hits']['hits'][0]['_source'];
				}else{
					$dbrow	=	array();
					foreach ($index_data['hits']['hits'] AS $row){
						$dbrow[]	=	$row['_source'];
					}
					return $dbrow;
				}
			}else{
				if(!is_array($user_identification)){
					$this->registerSearchIndex($user_identification);
				}else{
					foreach($user_identification AS $row){
						$this->registerSearchIndex($row);
					}
				}
				return false;
			}
		}else{
			return false;
		}
		
	}
	public function createIndex($index, $type){
		$check_index	=	array(
				'index'	=>	$index,
				'type'	=>	$type
		);
		//create index if don't exist
		if(!$this->checkIndexExist($check_index)){
			$indexParams['index']  = $index;
			$indexParams['type']     = $type;
			// Index Settings
			$indexParams['body']['settings']['number_of_shards']   = 5;
			$indexParams['body']['settings']['number_of_replicas'] = 2;
			$indexParams['body']['settings']['refresh_interval'] = -1;
		
		
			// Create the index
			$this->search_engine->create($indexParams);
			//set mappings
			$myTypeMapping = 				array(
					'properties'	=>	array(
							'username' => array(
									'type' => 'string',
									"index" => "not_analyzed"
							),
							'avatar' => array(
									'type' => 'string',
							),
							'looking_for' => array(
									'type' => 'string',
							),
							'user_id' => array(
									'type' => 'double',
							),
							'friendlist' => array(
									'type' => 'string',
							),
							'total_candy_alert' => array(
									'type' => 'integer',
							),
							'total_photo_request' => array(
									'type' => 'integer',
							),
							'total_addfriend_request' => array(
									'type' => 'integer',
							),
							'total_alert' => array(
									'type' => 'integer',
							),
							'total_message' => array(
									'type' => 'integer',
							),
							'total_visitor' => array(
									'type' => 'integer',
							),							
							'current_country_id' => array(
									'type' => 'integer',
							),
							'current_state_id' => array(
									'type' => 'integer',
							),
							'current_city_id' => array(
									'type' => 'integer',
							),
							'current_district_id' => array(
									'type' => 'integer',
							),
							'ordering' => array(
							'type' => 'integer',
							),
							'status' => array(
							'type' => 'integer',
							),
							'is_vip' => array(
							'type' => 'integer',
							),
							'display_search' => array(
							'type' => 'integer',
							),
							'current_device' => array(
							'type' => 'integer',
							),
							'age' => array(
							'type' => 'integer'
									),
									'birthday_year' => array(
									'type' => 'integer'
											),
											'birthday' => array(
											'type' => 'integer'
													),
													'birthday_date' => array(
													'type' => 'string',
													'index'=>	'not_analyzed'
															),
															'sex_role' =>  array(
															'type' => 'integer'
																	),
																	'user_sort' =>  array(
																	'type' => 'nested',
																	'properties'	=>	array(
																	'user_id'=>	array('type' => 'integer'),
																	'sex_role'	=>	array('type' => 'integer'),
																	'current_country_id'	=>	array('type' => 'integer'),
																	'current_state_id'	=>	array('type' => 'integer'),
																	'current_city_id'	=>	array('type' => 'integer'),
																	'current_district_id'	=>	array('type' => 'integer'),
																	'ordering'	=>	array('type' => 'integer'),
																	'sort_type'	=>	array('type' => 'string'),
																	'have_avatar'	=>	array('type' => 'integer'),
																	)
																	),
																	'top_ordering' => array(
																	'type' => 'integer',
																	),
																	'smoke' => array(
																	'type' => 'integer',
																	),
																	'have_avatar' => array(
																	'type' => 'integer',
																	),
																	'weight' => array(
																	'type' => 'float',
																	),
																	'last_activity' => array(
																	'type' => 'double',
																	),
																	'last_login' => array(
																	'type' => 'double',
																	),
																	'latitude' => array(
																	'type' => 'double',
																	),
																	'longitude' => array(
																	'type' => 'double',
																	),
																	'location'	=>	array(
																	'type' => 'geo_point'
																			),
																			'sexuality'	=>	array(
																			'type' => 'integer'
																					),
																					'height' => array(
																					'type' => 'float',
																					),
																					'email' => array(
																					'type' => 'string',
																					),
					)
			);
			$indexParams	=	array();
			$indexParams['index'] = $index;
			$indexParams['type']  = $type;
			$indexParams['body'][$index] = $myTypeMapping;
			return $this->search_engine->indices()->putMapping($indexParams);
		}		
	}
}
if(!function_exists('add_str_queryin')){
	function add_str_queryin($str) {
		if($str != ''){
			return "'{$str}'";
		}
	}
}

?>