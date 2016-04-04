<?php

class SearchController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionExplore(){			
		if(Yii::app()->request->isPostRequest){
			
			$token = Yii::app()->request->getPost('token', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
				
			$offset = Yii::app()->request->getPost('offset', 0);
			$limit = Yii::app()->request->getPost('limit', 0);
			
			if($token && $offset && $limit && $macaddress){
				$modelToken = ApiMobileTokens::model()->findByAttributes(array('token' => $token, 'macaddress' => $macaddress));
				if($modelToken){
					
					$location_model = UsrProfileLocation::model()->findByAttributes(array('user_id' => $modelToken->user_id));
					
					$filter = array();
					if($location_model){
						$country_id					  = $location_model->current_country_id;
						$filter['current_country_id'] = array('=' => $location_model->current_country_id);
					}else{
						$filter['current_country_id'] = 0;
						$country_id					  = 0;
					}
					
					$search_conditions = array(
							'filter' => $filter,
							'keyword' => false,
							'country_id'	=>	$country_id
					);
									
					$params = CParams::load ();
					$img_webroot_url	=	$params->params->img_webroot_url;
									
					//remove myself
					$user_id_except = false;
					
					$elasticsearch	=	new Elasticsearch();
					$sort_script	=	"(doc['have_avatar'].value == 1)	?	doc['last_activity'].value + 7500000 : doc['last_activity'].value";
					$elasticsearch->setSortRules($sort_script);
					$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
					
					$search_data	=	array();
					if($data_search['total'] > 0){
						foreach($data_search['fulldata'] AS $row){
							$row	=	$row['_source'];
							if($row['have_avatar']){
								$avatar	=	"http://{$img_webroot_url}{$row['avatar']}";
							}else{
								$avatar	=	$row['avatar'];
							}
							if(time() - $row['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){
								$online	=	1;
							}else{
								$online = 	0;
							}
							$search_data[]	=	array('username' => $row['alias_name'], 'avatar' => $avatar, 'online' => $online);
						}
					}	
					$arr	=	array(
							'result'	=>	true,
							'response'	=>	$search_data
					);
				}else{
					//invalid token
					$arr	=	array(
							'result'	=>	false,
							'response' => array('msg' => 'invalid token')
					);					
				}	
			}else{
				$msg	=	array();
				if(!$limit){
					$msg[]	=	'limit';
				}	
				if(!$offset){
					$msg[]	=	'offset';
				}
		
				$arr	=	array(
						'result'	=>	false,
						'response' => array('msg' => 'please input full fields(' . implode(', ', $msg) . ')')
				);				
			}
		}else{
			$arr	=	array(
					'result'	=>	false,
					'response' => array('msg' => 'invalid action method.')
			);			
		}
		echo json_encode($arr);
		Yii::app()->end();
	}
}