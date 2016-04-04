<?php

class AuthenticationController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionLogin(){
		$model=new LoginForm();
		
		if(Yii::app()->request->isPostRequest){
			$deviceinfo = Yii::app()->request->getPost('deviceinfo', false);
			$username = Yii::app()->request->getPost('username', false);
			
			$longitude = Yii::app()->request->getPost('longitude', 0);
			$latitude = Yii::app()->request->getPost('latitude', 0);
			
			$password = Yii::app()->request->getPost('password', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
					
			if($deviceinfo && $username && $password && $macaddress){
				$model->attributes	=	array('username' => $username, 'password' => $password);
				$model->validate();
				if(!$model->hasErrors() && $model->login()){
					
					$token	=	md5(rand()) . md5($macaddress  . rand());
					
					//general token
					$tokenModel	=	new ApiMobileTokens();
					$tokenModel->user_id		=	$model->user->id;
					$tokenModel->token			=	$token;
					$tokenModel->date_created	=	time();
					$tokenModel->date_updated	=	time();
					$tokenModel->macaddress		=	$macaddress;
					$tokenModel->device_info	=	$deviceinfo;
					$tokenModel->ip				=	$_SERVER['REMOTE_ADDR'];
					$tokenModel->save();
					
					//update location for longitude & latitude
					$setting	=	UsrProfileSettings::model()->findByAttributes(array('user_id' => $model->user->id));
					$setting->latitude	=	$latitude;
					$setting->longitude	=	$longitude;
					$setting->save();
					//update index search
					$elasticsearch	=	new Elasticsearch();
					$elasticsearch->updatePosition($model->user->id, $latitude, $longitude);
					
					//get current country		
					$location = UsrProfileLocation::model()->findByAttributes(array('user_id' => $model->user->id));
					$arr	=	array(
						'result'	=>	true,
						'response' => array(
							'token'			=>	$token,
							'alias'			=>	$model->user->alias_name,
							'avatar'		=>	$model->user->getAvatar(),
							'country_id'	=>	isset($location->current_country_id)	?	$location->current_country_id: 0
						)
					);
				}else{
					$arr	=	array(
							'result'	=>	false,
							'response' => $model->errors
					);					
				}
			}else{
				$msg	=	array();
				if(empty($deviceinfo)){
					$msg[]	=	'deviceinfo';
				}
				if(empty($username)){
					$msg[]	=	'username';
				}	
				if(empty($password)){
					$msg[]	=	'password';
				}
				if(empty($macaddress)){
					$msg[]	=	'macaddress';
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
	public function actionChecktoken(){
		if(Yii::app()->request->isPostRequest){
			$token = Yii::app()->request->getPost('token', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
			
			$longitude = Yii::app()->request->getPost('longitude', 0);
			$latitude = Yii::app()->request->getPost('latitude', 0);
			
			
			if($token && $macaddress){
				$modelToken = ApiMobileTokens::model()->findByAttributes(array('token' => $token, 'macaddress' => $macaddress));
				if($modelToken){
					$token	=	md5(rand()) . md5($macaddress  . rand());
					$modelToken->token = $token;
					$modelToken->date_updated	=	time();
					$modelToken->ip				=	$_SERVER['REMOTE_ADDR'];
					$modelToken->save();
					
					$arr	=	array(
							'result'	=>	true,
							'response' => array(
									'token'		=>	$token,
									'alias'		=>	$modelToken->user->alias_name,
									'avatar'	=>	$modelToken->user->getAvatar(),
							)
					);					
					
					//update location for longitude & latitude
					$setting	=	UsrProfileSettings::model()->findByAttributes(array('user_id' => $modelToken->user_id));
					$setting->latitude	=	$latitude;
					$setting->longitude	=	$longitude;
					$setting->save();
					//update index search
					$elasticsearch	=	new Elasticsearch();
					$elasticsearch->updatePosition($modelToken->user_id, $latitude, $longitude);
					
					
				}else{
					//invalid token
					$arr	=	array(
							'result'	=>	false,
							'response' => array('msg' => 'invalid token')
					);					
				}
			}else{
				//don't input full information
				$arr	=	array(
						'result'	=>	false,
						'response' => array('msg' => 'please input full information.')
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
	public function actionLogout(){
		if(Yii::app()->request->isPostRequest){
			$token = Yii::app()->request->getPost('token', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
	
			if($token && $macaddress){
				$modelToken = ApiMobileTokens::model()->findByAttributes(array('token' => $token, 'macaddress' => $macaddress));
				if($modelToken){
					if($modelToken->delete()){
						$elasticsearch	=	new Elasticsearch();
						$elasticsearch->updateLastActivity(Yii::app()->user->id, false);
						$arr	=	array(
							'result'	=>	true,
						);
					}else{
						$arr	=	array(
								'result'	=>	false,
								'response' => array('msg' => 'invalid action method.')
						);						
					}
				}else{
					//invalid token
					$arr	=	array(
							'result'	=>	false,
							'response' => array('msg' => 'invalid token')
					);
				}				
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
	public function actionRegister(){	
		if(Yii::app()->request->isPostRequest){
			$deviceinfo = Yii::app()->request->getPost('deviceinfo', false);
			$registerContent	=	Yii::app()->request->getPost('registercontent', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
			$arr	=	array();
			
			if($registerContent && $macaddress && $deviceinfo){
				$data	=	json_decode($registerContent, true);
				$data['confirm_password']	=	$data['password'];
				
				$model = new ApiRegisterForm();
				$model->attributes = $data;
				$model->validate();
				if(!$model->hasErrors()){

					if($userObj = $model->save()){
						
						//create token
						$token	=	md5(rand()) . md5($macaddress  . rand());
							
						//general token
						$tokenModel	=	new ApiMobileTokens();
						$tokenModel->user_id		=	$userObj->id;
						$tokenModel->token			=	$token;
						$tokenModel->date_created	=	time();
						$tokenModel->date_updated	=	time();
						$tokenModel->macaddress		=	$macaddress;
						$tokenModel->device_info	=	$deviceinfo;
						$tokenModel->ip				=	$_SERVER['REMOTE_ADDR'];
						$tokenModel->save();
						
						//get current country
						$arr	=	array(
								'result'	=>	true,
								'response' => array(
										'token'		=>	$token,
										'alias'		=>	$userObj->alias_name
								)
						);
						
					}
				}else{
					$arr	=	array(
							'result'	=>	false,
							'response' => $model->errors
					);					
				}
			}else{
				$msg	=	array();
				if(empty($deviceinfo)){
					$msg[]	=	'deviceinfo';
				}
				if(empty($macaddress)){
					$msg[]	=	'macaddress';
				}
				if(empty($registerContent)){
					$msg[]	=	'register Content';
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