<?php

class ActionController extends Controller
{
	protected $hashcode;
	protected $loginForm;
	
	public function actionIndex()
	{
		$this->redirect('/');
	}
	
	public function actionRegister()
	{
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$access_token = $_POST['access_token'];
			$username = strtolower($_POST['username']);
			$password = $_POST['password'];
			$email = $_POST['email'];
			$from = $_POST['from'];
			//begin PDOOAuth
			$oauth = new PDOOAuth2();
			if(isset($access_token) && isset($username) && isset($password) && isset($email)){
				$verify = $oauth->verifyAccessToken($access_token);
				if($verify == true){
					//find client_id
					$check_client = Tokens::model()->findByAttributes(array('oauth_token' => $access_token));
					//begin check username
					$check_username = YumUser::model()->findByAttributes(array('username' => $username));
					//begin check email
					Yii::import('backend.modules.profile.models.*');
					$check_email = YumProfile::model()->findByAttributes(array('email' => $email));
					
					if(isset($check_username) && isset($check_username->username)){
						echo CJSON::encode(array('error' => 'username aready exists'));	
					} elseif(isset($check_email) && isset($check_email->email)){
						echo CJSON::encode(array('error' => 'email aready exists'));	
					} else {
						$validator_email = new CEmailValidator;
						if($validator_email->validateValue($email) && $this->validateUsername($username) == true){
							$user = new YumUser();
							if($user->register($username, $password, $email)){
								$api_activity = new UserApiActivity();					
								$api_activity->username 	= $username;
								$api_activity->client_id 	= $check_client->client_id;
								$api_activity->action 		= UserApiActivity::ACTION_REGISTER;
								$api_activity->timestamp	= time();
								$api_activity->save();
								//mongo activity
								/**Save Log**/
								YumActivityController::logActivity ( $user, YumActivityLog::LOG_REGISTER, 'User {username} registered with email: {email} from {from}', json_encode ( array (
									'{client_id}' => $check_client->client_id, '{username}' => $username, '{email}' => $email, '{from}' => $from) ), 0, $from);		
								
								echo CJSON::encode($user);
							}
						} else {
							echo CJSON::encode(array('error' => 'username or email invalid'));	
						}
					}
				} 
			} else {
				$oauth->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_REQUEST);
			}
		} else {
			echo CJSON::encode(array('error' => 'Only POST menthod allow'));	
		}
		
	}
	
	public function actionCheck($access_token = null, $type = null, $value = null)
	{
		$oauth = new PDOOAuth2();
		if(isset($access_token) && isset($type) && isset($value)){
			$verify = $oauth->verifyAccessToken($access_token);
			if($verify == true){
				//Begin Check
				if($type == 'email'){
					Yii::import('backend.modules.profile.models.*');
					$check = YumProfile::model()->findByAttributes(array('email' => $value));
					if(isset($check) && $check->email == $value){
						echo CJSON::encode(array('result' => 'exists'));
					} else {
						echo CJSON::encode(array('result' => 'not_found'));
					}
				} elseif($type == 'username') {
					$check = YumUser::model()->find("username LIKE :username",array('username' => $value));
					if(isset($check)){
						echo CJSON::encode(array('result' => 'exists'));
					} else {
						echo CJSON::encode(array('result' => 'not_found'));
					}
				}
			} 
		} else {
			$oauth->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_REQUEST);
		}
	}
	
	public function actionGetProfile($access_token = null, $username = null) {
		$oauth = new PDOOAuth2();
		if($access_token){
			$verify = $oauth->verifyAccessToken($access_token);
			if($verify == true){
				//Begin getProfile
				Yii::import('backend.modules.profile.models.*');
				$profile = new YumProfile();
				$profile = $profile->getProfilebyUsername($username);
				$data = array();
				$data = $profile->attributes;
				$data['username'] = $profile->user->username;
				$data['activationKey'] = $profile->user->activationKey;
				$data['createtime'] = $profile->user->createtime;
				$data['lastvisit'] = $profile->user->lastvisit;
				$data['lastaction'] = $profile->user->lastaction;
				$data['lastpasswordchange'] = $profile->user->lastpasswordchange;
				$data['superuser'] = $profile->user->superuser;
				$data['status'] = $profile->user->status;
				$data['avatar'] = $profile->user->avatar;
				if(isset($data)) {
					echo  CJSON::encode($data);
				} else {
					$error = array('error' => 'not_found');
					echo  CJSON::encode($error);
				}
			} 
		} else {
			$oauth->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_REQUEST);
		}
	}
	
	public function actionChkToken($access_token = null)
	{
		$oauth = new PDOOAuth2();
		if($access_token){
			$verify = $oauth->verifyAccessToken($access_token);
			if($verify == true){
				echo  CJSON::encode(array('status'=>true));
			}else{
				echo  CJSON::encode(array('status'=>false));
			}
		}
	}
		
	public function actionLogin() {
		if(Yii::app()->request->isPostRequest){			
			$access_token = $_POST['access_token'];
			$username 	= Util::decryptRandCode(Yii::app()->request->getPost('username'));
			$password	= Util::decryptRandCode(Yii::app()->request->getPost('password'));
			$from 		= $_POST['from'];
		
			$oauth = new PDOOAuth2();
			if($access_token){
				$verify = $oauth->verifyAccessToken($access_token);
				if($verify == true){
					//Begin API Login
					$result = array();
					$member = Member::model()->findByAttributes(array('username' => $username));					
					if(isset($username) && isset($password) && md5($password) == md5($member->id.$member->username)) {						
						$_identity=new UserIdentity($username,null);
						$_identity->authenticate(true);
						
						if($_identity->errorCode===UserIdentity::ERROR_NONE)
						{
							$duration=1 ? 3600*24*30 : 0; // 30 days
							Yii::app()->user->login($_identity,$duration);
								
							/***process after login****/
							$user	=	Yii::app()->user->data();
							$sid = Yii::app()->getSession()->getSessionId();							
							echo CJSON::encode(array('sid'=>$sid, 'user'=>array('username'=>$user->username)));
							Yii::app()->end();
						}
					}
					echo $result;
					Yii::app()->end();
				} 
			} else {
				$oauth->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_REQUEST);
			}
		} else {
			echo CJSON::encode(array('error' => 'Only POST menthod allow'));	
			Yii::app()->end();
		}	
	}
	
	public function actionLogReferer(){
		$oauth = new PDOOAuth2();
		if(isset($_POST) && isset($_POST['access_token'])){
			$verify = $oauth->verifyAccessToken($_POST['access_token']);
			if($verify == true){
				if(isset($_POST['user_id']) && isset($_POST['referer']) && isset($_POST['raw_url'])){
				
					Yii::import('backend.modules.referer.models.*');
					Yii::import('frontend.models.*');
					$user = YumUser::model()->findByPk($_POST['user_id']);
					$ref = RefererDefine::model()->getRefererId($_POST['referer']);
					$mdlUserReferer = new MdlUserReferer();		
					if ($ref){
						$mdlUserReferer->insert($_POST['user_id'], $_POST['raw_url'], $mdlUserReferer->action[0], $ref->id, $_POST['referer'], 'http://play.saga.like.vn');
						YumActivityController::logActivity($user, YumActivityLog::LOG_REFERER, 'User {username} referer from url : {url_referer} => {url_redirect}', 
						json_encode( array (
								'{username}' => $user->username, 
								'{url_referer}' => $_POST['referer'], 
								'{url_redirect}' => $_POST['raw_url'],
								'{host}' => $_POST['host'],
								'{action}' =>  'register',
								'{inviter_id}' => 0
							)));
						
						$result = array('result' => 'Saved');
						echo  CJSON::encode($result);
					} else{
						$error = array('error' => 'Can not save');
						echo  CJSON::encode($error);
					}
				}
				
			}
		} else {
			$oauth->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_REQUEST);
		}
	}
	
	public function validateUsername($username = null){
		if(preg_match("/^[0-9a-zA-Z_]{6,}$/", $username) == 0){
			return false;
		} elseif(strlen($username) < 6 && strlen($username) > 32) {
			return false;
		} else {
			return true;
		}
	}
}