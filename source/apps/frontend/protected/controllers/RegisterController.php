<?php
//require_once 'Facebook' . DS . 'autoload.php';

//use Facebook\FacebookRequest;
//use Facebook\GraphUser;
//use Facebook\FacebookRequestException;

class RegisterController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
    public function actions()
	{
		return array(
		// captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'backend.extensions.captchaExtended.CaptchaExtendedAction',
                'mode'=> CaptchaExtendedAction::MODE_NUM,
                'offset'=>'4',
                'density'=>'0',
                'lines'=>'0',
                'fillSections'=>'0',
                'foreColor'=>'0x000000',
                'minLength'=>'6',
                'maxLength'=>'6',
                'fontSize'=>'24',
                'angle'=>false,
		    ),
		);
	}
	/**
	 * @see CController::beforeAction()
	 */
	public function beforeAction($action){
		return parent::beforeAction($action);
	}
	/**
	 * Invitation
	 */
	public function actionInvitation()
	{
		if(!Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->user->data()->getUserFeedUrl());
		}
		$model = new InvitationForm();
		$receiveModel = new ReceiveInvitationForm();
		if(Yii::app()->request->isPostRequest){
			$model->attributes = Yii::app()->request->getPost('InvitationForm');
			$model->validate();
			if(!$model->hasErrors()){
				$this->redirect(array('index'));
			}
		}
		$this->render('page/invitation',array('model'=>$model, 'receiveModel'=>$receiveModel));
	}
	/**
	 * Register
	 */
	public function actionIndex()
	{
		$this->layout = 'main2';
		$model = new RegisterForm();
		if($redirect_url = Yii::app()->request->getParam('redirect_url')){
		    Yii::app()->session->add ( 'redirect_url', $redirect_url );
		}
	    /**
	     * validate form
	     */
	    if(isset($_POST['ajax']) && $_POST['ajax']==='register-form'){
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    /***/
		if(!Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->user->data()->getUserFeedUrl());
		}
		if(InvitationForm::enableInvitation()){
			$this->redirect(array('invitation'));
		}
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('RegisterForm');
			$model->attributes = $data;
			$model->lastname = $data['username'];
			$model->firstname = $data['username'];
			$model->validate();
			if(!$model->hasErrors() && InvitationForm::validateInvitation()){
			    $redirect_url = Yii::app()->session->get ( 'redirect_url' );
				if($model->save()){
					$frlogin=new LoginForm();
					$frlogin->loginByUsernamePass($model->username, $model->password, false);
					/**vtn create user**/
					if(!empty(CParams::load()->params->vtn->migrate->createuser)){
						$user = Yii::app()->user->data();
						$return = VTN::model()->createUser(array(
								'username' => $data['username'],
								'password' => $data['password'],
								'email' => $data['email'],
								'birthday' => mktime(0, 0, 0, $data['month'], $data['day'], $data['year']),
								'pid' => $user->id,
						));
					}
					/**vtn create user**/
					if(!empty($redirect_url)){
					    Yii::app()->session->remove ( 'redirect_url' );
					    $this->redirect($redirect_url);
					}else{
    					$this->redirect(array('stepUpdateProfile'));
					}					
				}
			}
		}
		$this->render('page/index',array('model'=>$model));
	}
	
	public function actionAjaxvalidate($type = null)
	{
	    $model = new RegisterForm();
	    if (Yii::app()->request->isAjaxRequest && $type){
            $rq_registerform = Yii::app()->request->getPost('RegisterForm');
            $cri = new CDbCriteria();
            switch ($type){
                case 'username':
                    $cri->addCondition("username RLIKE :rlike AND username LIKE :like");
                    $cri->params = array(':rlike'=>"[[:<:]]{$rq_registerform['username']}[[:>:]]", ':like'=>$rq_registerform['username']);
                    $chk = Member::model()->find($cri);
                    $msg = Lang::t('register', "Someone already has that username. Please try another!");
                    break;
                case 'email':
                    $chk = YumProfile::model()->findByAttributes(array('email'=>$rq_registerform['email']));
                    $msg = Lang::t('register', "Someone already use that email. Please try another!");
                    break;
            }
	        if($chk){
    	        echo json_encode(array('error'=>$msg));
    	        Yii::app()->end();
	        }
	        
	    }
	}
	/**
	 * Activation account
	 * @param $email
	 * @param $key
	 */
	public function actionActivation($key, $tk) {	
		$user = Member::model()->findByAttributes(array('activationKey'=>$key, 'alias_name'=>Util::decryptRandCode($tk)));
		if(!empty($user->id) && $user instanceof Member) {
			if(!$user->isActive()) {
				$user->status = Member::STATUS_ACTIVE;
				if($user->save()){

					Yii::app()->user->setFlash('msgLogin', Lang::t('register', 'Account actived.'));
        			$this->render('page/activation');
        
        			Yii::app()->end();
				}
			}
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}
	
	public function actionResendActivation() {
	        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
	            $new_email = Yii::app()->request->getPost('email');
	            $user = Yii::app()->user->data();
	            if(!empty($new_email) && !empty($user) && !$user->isActive()){
	                $msg = '';
	                $old_email = $user->profile->email;
	                $user->profile->email = $new_email;
	                $user->profile->validate();
	                if(!$user->profile->hasErrors()){
	                    $user->profile->save();
	                    $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/register-welcome',array('user'=>$user, 'activation_url'=>$user->getActivationUrl()), true);
	                    $subject = strtr(Lang::t('yii','Please activate your account for {username}'), array(
	                            '{username}' => $user->username));
	                    $sent = Mailer::send ( $new_email,  $subject, $body);
	                    echo CJSON::encode(array('stt'=>true, 'msg'=>$msg));
	                }else{
	                    if(!empty($user->profile->errors['email'][0])){
	                        $msg = $user->profile->errors['email'][0];
	                    }
	                    echo CJSON::encode(array('stt'=>false, 'msg'=>$msg));
	                }
	            }
    	        Yii::app()->end();
	        }
	}
	/**
	 * Facebook
	 */
	
	public function actionFacebook()
	{
		$profile	=	false;
		try {
			$profile = Yii::app()->facebook->api('/me');
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
		} catch(\Exception $ex) {
			// When validation fails or other local issues
		}
		if(!$profile) {
			$params = array(
					'scope' => 'email',
					'redirect_uri' => Yii::app()->createAbsoluteUrl('//register/Facebook')
			);
			
			$loginUrl = Yii::app()->facebook->getLoginUrl($params);
			
			$this->redirect($loginUrl);
		}else{
			
			$avatar	=	Yii::app()->facebook->api('/me/picture', array(
					'redirect'	=>	false,
					'type'	=>	'large'
			));
			

			
			
			$facebookid	=	$profile['id'] ;			
			$email	=	$profile['email'] ;
			$gender	=	isset($profile['gender'])	?	$profile['gender']	:	false;
			
			
			
			$check_facebook_register = Member::model()->findByAttributes(
				array(
					'openid_identification' => $facebookid,
					'openid_type'	=>	Member::OPENID_TYPE_FACEBOOK
				)
			);
			if($check_facebook_register){
				if($check_facebook_register->openid_register_complete){
					
					$uerIdentity = new UserIdentity($check_facebook_register->username, '');
					$uerIdentity->authenticate(TRUE);
					if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
						Yii::app()->user->login($uerIdentity, 3600*24*30);
					echo '<script>window.close(); window.opener.location.reload();</script>';
					
				}
				else{
					//update profile	
					Yii::app()->session['register_social_id']	=	$facebookid;
					Yii::app()->session['register_social_type']	=	Member::OPENID_TYPE_FACEBOOK;
					echo '<script>window.close(); window.opener.location  = "' . Yii::app()->createAbsoluteUrl('//site/UpdateProfileSocial') . '";</script>';
				}
				
			}else{
				//check email exist
				$check_email_exist = YumProfile::model()->findByAttributes(
					array(
						'email'	=>	$email
					)
				);
				//mapping to email
				if($check_email_exist){
					$user	=	Member::model()->findByPk($check_email_exist->user_id);		
					
					$user->openid	=	1;
					$user->openid_type	=	Member::OPENID_TYPE_FACEBOOK;
					$user->openid_identification	=	$facebookid;
					$user->openid_register_complete	=	1;
					$user->save();
					$uerIdentity = new UserIdentity($user->username, '');
					$uerIdentity->authenticate(TRUE);
					if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
						Yii::app()->user->login($uerIdentity, 3600*24*30);
					echo '<script>window.close(); window.opener.location.reload();</script>';
				}else{
					//create random username
					$uniqueID	=	SocialRegister::createRandomUser($profile['first_name']);
					$random_password	=	Util::randomDigits(8);
					
					
					$register_data	=	array(
						'username'	=>	$uniqueID,
						'openid'	=>	1,
						'openid_type'	=>	YumUser::OPENID_TYPE_FACEBOOK,
						'openid_identification'	=>	$facebookid,
						'openid_register_complete'	=>	$random_password,
						'lastname'	=>	$profile['last_name'],
						'firstname'	=>	$profile['first_name'],
						'day'		=>	1,
						'password'	=>	1,
						'month'		=>	1,
						'year'		=>	date('Y') - 17,
						'email'		=>	$email,
					);
					
					//register new user
					$model = new RegisterSocialForm();
					$model->attributes = $register_data;
					$model->validate();

					if($user = $model->save()){
						//update avatar
						$avatar	=	Yii::app()->facebook->api('/me/picture', array(
								'redirect'	=>	false,
								'type'	=>	'large'
						));
						if(!$avatar['data']['is_silhouette']){
							if(isset($avatar['data']['url'])){
								$chk	=	preg_match('@([^/]*)\?.*$@', $avatar['data']['url'], $filename_avatar_matchs);
								$params = CParams::load ();
								//download file
								$temp_file	=	time() . $filename_avatar_matchs[1];
								$tmp_file	=	$params->params->upload_path . DS . $temp_file;
															
								SocialRegister::downloadFromUrl($avatar['data']['url'], $tmp_file);
								
								
								Yii::import ( "backend.extensions.plunUploader.upload");
								
								$uploader = new upload($params->params->uploads->upload_method);
								$uploader->allowedExtensions = array (
										'jpg',
										'jpeg',
										'png'
								);
								$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
								$thumb160x160 = $params->params->uploads->photo->thumb160x160;
								$origin = $params->params->uploads->photo->origin;
								$thumb275x275 = $params->params->uploads->photo->thumb275x275;
								$detail1600x900 = $params->params->uploads->photo->detail1600x900;
								
								
								
								//load images resource
								$origin_folder = $uploader->setPath ( $origin->p, false );
								$thumb160x160_folder = $uploader->setPath ( $thumb160x160->p , false );
								$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
								$thumb275x275_folder = $uploader->setPath ( $thumb275x275->p , false );
								$detail1600x900_folder = $uploader->setPath ( $detail1600x900->p, false  );
								
								$uploader->loadImageResource('/' . $temp_file);
								$uploader->saveImg($detail1600x900_folder . '/' . $temp_file);
								$uploader->saveImg($origin_folder . '/' . $temp_file);
								
								$uploader->sharpen(20);
								$uploader->checkDir($thumb160x160_folder);
								$uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
								$uploader->crop($thumb160x160->w, $thumb160x160->h, 'top');
								$uploader->saveImg($thumb160x160_folder . '/' . $temp_file);
								
								$uploader->resizeImage($thumb275x275->w, $thumb275x275->h);
								$uploader->crop($thumb275x275->w, $thumb275x275->h, 'top');
								$uploader->saveImg($thumb275x275_folder . DS . $temp_file);
								
								$uploader->detroyResource();
								
								// save to database
								$photo_model = new Photo ();
								$photo_model->album_id = 0;
								$photo_model->user_id = $user->id;
								$photo_model->title = $temp_file;
								$photo_model->name = $temp_file;
								$photo_model->path = ltrim($path_folder,'\/');
								$photo_model->status = 1;
								$photo_model->type = Photo::PUBLIC_PHOTO;
								$photo_model->created = time ();
								$photo_model->save ();
								
								$user->avatar	=	$photo_model->id;
								$user->save();
								unlink($tmp_file);
								$Elasticsearch	=	new Elasticsearch();
								$Elasticsearch->updateSearchIndexUser($user->id);

							}
						}
						Yii::app()->facebook->destroySession();
						//update profile
						Yii::app()->session['register_social_id']	=	$facebookid;
						Yii::app()->session['register_social_password']	=	$random_password;
						Yii::app()->session['register_social_type']	=	Member::OPENID_TYPE_FACEBOOK;
						echo '<script>window.close(); window.opener.location  = "' . Yii::app()->createAbsoluteUrl('//site/UpdateProfileSocial') . '";</script>';
					}
				}
			}
			
			
			exit;
						
		}		
	}
	
	/**
	 * Google OpenID
	 */
	public function actionGoogle()
	{
		$params = CParams::load ();
		Yii::import('backend.extensions.google-api-php-client.src.Google_Client');
		Yii::import('backend.extensions.google-api-php-client.src.contrib.Google_PlusService');
		Yii::import('backend.extensions.google-api-php-client.src.contrib.Google_Oauth2Service');

		$model = new RegisterForm();
		// Set your cached access token. Remember to replace $_SESSION with a
		// real database or memcached.
		$client = new Google_Client();
		$client->setApplicationName('Plun.Asia');
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setClientId($params->params->googleapi->client_id);
		$client->setClientSecret($params->params->googleapi->client_secret);
		$client->setRedirectUri(Yii::app()->createAbsoluteUrl('//register/Google'));
		$plus = new Google_PlusService($client);
		$oauth2 = new Google_Oauth2Service($client);
		
		if (isset($_GET['code'])) {
			$client->authenticate();
			Yii::app()->session['gmail_openid_token'] = $client->getAccessToken();
		}
		
		if (isset(Yii::app()->session['gmail_openid_token'])) {
			$client->setAccessToken(Yii::app()->session['gmail_openid_token']);
		}
		
		$profile =	false;
		try {
			$profile = $plus->people->get('me');
			
		} catch(Google_ServiceException $ex) {
		} 
		if($profile){
			
			$googleid	=	$profile['id'] ;			
			$email	=	isset($profile['emails'][0]['value']) ?	$profile['emails'][0]['value'] : false;
			
			$check_google_register = Member::model()->findByAttributes(
				array(
					'openid_identification' => $googleid,
					'openid_type'	=>	Member::OPENID_TYPE_GMAIL
				)
			);
			if($check_google_register){
				if($check_google_register->openid_register_complete){
					
					$uerIdentity = new UserIdentity($check_google_register->username, '');
					$uerIdentity->authenticate(TRUE);
					if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
						Yii::app()->user->login($uerIdentity, 3600*24*30);
					echo '<script>window.close(); window.opener.location.reload();</script>';
					
				}
				else{
					//update profile	
					Yii::app()->session['register_social_id']	=	$googleid;
					Yii::app()->session['register_social_type']	=	Member::OPENID_TYPE_GMAIL;
					echo '<script>window.close(); window.opener.location  = "' . Yii::app()->createAbsoluteUrl('//site/UpdateProfileSocial') . '";</script>';
				}
				
			}else{
				
				//check email exist
				$check_email_exist = YumProfile::model()->findByAttributes(
					array(
						'email'	=>	$email
					)
				);
				//mapping to email
				if($check_email_exist){
					$user	=	Member::model()->findByPk($check_email_exist->user_id);		
					
					$user->openid	=	1;
					$user->openid_type	=	Member::OPENID_TYPE_GMAIL;
					$user->openid_identification	=	$googleid;
					$user->openid_register_complete	=	1;
					$user->save();
					$uerIdentity = new UserIdentity($user->username, '');
					$uerIdentity->authenticate(TRUE);
					if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
						Yii::app()->user->login($uerIdentity, 3600*24*30);
					echo '<script>window.close(); window.opener.location.reload();</script>';
				}else{
					//create random username
					$uniqueID	=	SocialRegister::createRandomUser($profile['name']['givenName']);
					$random_password	=	Util::randomDigits(8);
					$register_data	=	array(
						'username'	=>	$uniqueID,
						'openid'	=>	1,
						'openid_type'	=>	YumUser::OPENID_TYPE_GMAIL,
						'openid_identification'	=>	$googleid,
						'openid_register_complete'	=>	0,
						'lastname'	=>	$profile['name']['familyName'],
						'firstname'	=>	$profile['name']['givenName'],
						'day'		=>	1,
						'password'	=>	$random_password,
						'month'		=>	1,
						'year'		=>	date('Y') - 17,
						'email'		=>	$email,
					);
					
					//register new user
					$model = new RegisterSocialForm();
					$model->attributes = $register_data;
					$model->validate();

					if($user = $model->save()){
						//update avatar
						
						$avatar	=	$profile['image'];
						
					
						
						if(!$avatar['isDefault']){
							if(isset($avatar['url'])){
								//$chk	=	preg_match('@([^/]*)\?.*$@', $avatar['url'], $filename_avatar_matchs);
								$chk	=	preg_match('@^((?:.*)/([^/]*))\?.*$@', $profile['image']['url'], $filename_avatar_matchs);
								//download file
								$temp_file	=	time() . $filename_avatar_matchs[2];
								$tmp_file	=	$params->params->upload_path . DS . $temp_file;
															
								SocialRegister::downloadFromUrl($filename_avatar_matchs[1], $tmp_file);
								
								
								Yii::import ( "backend.extensions.plunUploader.upload");
								
								$uploader = new upload($params->params->uploads->upload_method);
								$uploader->allowedExtensions = array (
										'jpg',
										'jpeg',
										'png'
								);
								$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
								$thumb160x160 = $params->params->uploads->photo->thumb160x160;
								$origin = $params->params->uploads->photo->origin;
								$thumb275x275 = $params->params->uploads->photo->thumb275x275;
								$detail1600x900 = $params->params->uploads->photo->detail1600x900;
								
								
								
								//load images resource
								$origin_folder = $uploader->setPath ( $origin->p, false );
								$thumb160x160_folder = $uploader->setPath ( $thumb160x160->p , false );
								$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
								$thumb275x275_folder = $uploader->setPath ( $thumb275x275->p , false );
								$detail1600x900_folder = $uploader->setPath ( $detail1600x900->p, false  );
								
								$uploader->loadImageResource('/' . $temp_file);
								$uploader->saveImg($origin_folder . '/' . $temp_file);
								$logoPng = YiiBase::getPathOfAlias('pathroot').'/themes/plun2/resources/html/css/images/logo_in_photo.png';
								$uploader->logo($logoPng);
								$uploader->saveImg($detail1600x900_folder . '/' . $temp_file);
								
								
								$uploader->sharpen(20);
								$uploader->checkDir($thumb160x160_folder);
								$uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
								$uploader->crop($thumb160x160->w, $thumb160x160->h, 'top');
								$uploader->saveImg($thumb160x160_folder . '/' . $temp_file);
								
								$uploader->resizeImage($thumb275x275->w, $thumb275x275->h);
								$uploader->crop($thumb275x275->w, $thumb275x275->h, 'top');
								$uploader->saveImg($thumb275x275_folder . DS . $temp_file);
								
								$uploader->detroyResource();
								
								// save to database
								$photo_model = new Photo ();
								$photo_model->album_id = 0;
								$photo_model->user_id = $user->id;
								$photo_model->title = $temp_file;
								$photo_model->name = $temp_file;
								$photo_model->path = ltrim($path_folder,'\/');
								$photo_model->status = 1;
								$photo_model->type = Photo::PUBLIC_PHOTO;
								$photo_model->created = time ();
								$photo_model->save ();
								
								$user->avatar	=	$photo_model->id;
								$user->save();
								unlink($tmp_file);
								$Elasticsearch	=	new Elasticsearch();
								$Elasticsearch->updateSearchIndexUser($user->id);

							}
						}
						//update profile
						Yii::app()->session['register_social_id']	=	$googleid;
						Yii::app()->session['register_social_password']	=	$random_password;
						Yii::app()->session['register_social_type']	=	Member::OPENID_TYPE_GMAIL;
						echo '<script>window.close(); window.opener.location  = "' . Yii::app()->createAbsoluteUrl('//site/UpdateProfileSocial') . '";</script>';
					}
				}
			}
			
			
			exit;
		}else{
			$authUrl = $client->createAuthUrl();
			$this->redirect($authUrl);
		}

	}

	public function actionStepFindFriends($type=null){
		
		//update step register
		$user =  Yii::app()->user->data();
		$user->register_step = YumUser::REGISTER_STEP_COMPLETE;
		//save
		$user->save();
				
		$limit	=	Yii::app()->params['googleapi']['limit'];
		
		if(!empty($type) && $type=='twitter'){
			/*************************************/
			$twitter = Yii::app()->twitter->getTwitter();
			$request_token = $twitter->getRequestToken();

			//set some session info
			Yii::app()->session['oauth_token'] = $token =           $request_token['oauth_token'];
			Yii::app()->session['oauth_token_secret'] = $request_token['oauth_token_secret'];

			if($twitter->http_code == 200){
				//get twitter connect url
				$url = $twitter->getAuthorizeURL($token);
				//send them
				$this->redirect($url);
			}else{
				//error here
				$this->redirect(array('stepFindFriends'));
			}
		}


		/**********************************/
		$this->checkLogin();
		$this->render('page/step-find-friends',array('limit' => $limit));
	}


	public function actionTwitterCallBack() {
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && Yii::app()->session['oauth_token'] !== $_REQUEST['oauth_token']) {
			Yii::app()->session['oauth_status'] = 'oldtoken';
		}

		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$twitter = Yii::app()->twitter->getTwitterTokened(Yii::app()->session['oauth_token'], Yii::app()->session['oauth_token_secret']);

		/* Request access tokens from twitter */
		try {
			$access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
		} catch (Exception $e) {
		}

		/* Save the access tokens. Normally these would be saved in a database for future use. */
		Yii::app()->session['access_token'] = $access_token;

		/* Remove no longer needed request tokens */
		unset(Yii::app()->session['oauth_token']);
		unset(Yii::app()->session['oauth_token_secret']);

		if (200 == $twitter->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			Yii::app()->session['status'] = 'verified';

			//get an access twitter object
			$twitter = Yii::app()->twitter->getTwitterTokened($access_token['oauth_token'],$access_token['oauth_token_secret']);

			//get user details
			$twuser= $twitter->get("account/verify_credentials");
			//get friends ids
			$friends= $twitter->get("friends/ids");
			/*
			 $show = $twitter->get("users/show/quangvinhit2007");
			 $body = "Hi, \n";
			 $body .= "Please connect to social PlunAsia at here".urlencode('http://www.plun.asia');
			 $show = $twitter->post("direct_messages/new", array('text'=>$body, 'screen_name'=>'huydo_sutrix'));
			 echo '<pre>';
			 print_r($show);
			 echo '</pre>';
			 exit();
			 */
			//get followers ids
			$followers= $twitter->get("followers/ids");
			//tweet
			$result=$twitter->post('statuses/update', array('status' => "Tweet message"));
			$this->render('page/step-find-friends',array('friends'=>$friends, 'twitter'=>$twitter));
		} else {
			/* Save HTTP status for error dialog on connnect page.*/
			//header('Location: /clearsessions.php');
			$this->redirect(array('stepFindFriends'));
		}
	}

	public function actionStepUpdateProfile(){
		$this->checkLogin();
		
		//update step register
		$user =  Yii::app()->user->data();
		
		$user->register_step = YumUser::REGISTER_STEP_UPDATE_PROFILE;
		//save
		$user->save();
		$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   Yii::app()->user->id;
			$model->save();
		}
		$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		if(!$model_location){
			//create profile current location
			$model_location     =   new UsrProfileLocation();
			$model_location->user_id       =   Yii::app()->user->id;
			$model_location->save();
		}					
		$model_profile = YumProfile::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		//$unit = json_decode($model->persional_unit, true);
		if($model->measurement == 1){
			$model->unit_weight = 1;
			$model->unit_height = 1;
			$weight_unit_label	=	'kg';
			$height_unit_label	=	'cm';
		}else{
			$model->unit_weight = 2;
			$model->unit_height = 2;
			$weight_unit_label	=	'lbs';
			$height_unit_label	=	'ft';
		}		
		//build unit weight
		/*
		if (isset($unit['unit_weight'])) {
			$model->unit_weight = $unit['unit_weight'];
			if ($unit['unit_weight'] == '1') {
				$model->weight = round($model->weight * Yii::app()->params['kg_to_pound'], 2);
			}
		}else{
			$model->unit_weight	=	0;
		}

		//build unit height
		if (isset($unit['unit_height'])) {
			$model->unit_height = $unit['unit_height'];
			if ($unit['unit_height'] == '1') {
				$model->height = round($model->height * Yii::app()->params['cm_to_m'], 2);
			}
		}else{
			$model->unit_height = 0;
		}
		*/
		
		if (!empty($model->birthday)) {
			//build month
			$model->birthday_month = date('n', $model->birthday);
			//build day
			$model->birthday_day = date('j', $model->birthday);
		}				
		if(Yii::app()->request->isPostRequest){
			$model->user_id       =   Yii::app()->user->id;

			$post = Yii::app()->request->getPost('UsrProfileSettings');

			//for attributes
	        $post['my_attributes']	=	isset($post['my_attributes'])		?	$post['my_attributes']	:	'';
	                
	        //for languages
	        $post['languages']	=	isset($post['languages'])		?	$post['languages']	:	'';
	        
	        /*
			if (isset($post['unit_height'])) {
				$unit['unit_height'] = $post['unit_height'];
			}
			if (isset($post['unit_weight'])) {
				$unit['unit_weight'] = $post['unit_weight'];
			}
			//convert pound to kg
			if ($post['unit_weight'] == '1') {
				$post['weight'] = round($post['weight'] / Yii::app()->params['kg_to_pound'], 2);
			}
			//convert m to cm
			if ($post['unit_height'] == '1') {
				$post['height'] = round($post['height'] / Yii::app()->params['cm_to_m'], 2);
			}	         	
			*/		
			//set state, city, district value
			$post['country_id']	=	isset($post['country_id'])	?	$post['country_id']	:	0;
			$post['city_id']	=	isset($post['city_id'])	?	$post['city_id']	:	0;
			$post['district_id']	=	isset($post['district_id'])	?	$post['district_id']	:	0;
			$post['state_id']	=	isset($post['state_id'])	?	$post['state_id']	:	0;
			
			$model->attributes = $post;
			$model->validate();
			if(!$model->hasErrors()){
				if($model->save()){					
					//save current location
					if(!$model_location->published){
						$data	=	array(
							'current_country_id'	=>	$post['country_id'],
							'current_state_id'	=>	$post['state_id'],
							'current_city_id'	=>	$post['city_id'],
							'current_district_id'	=>	$post['district_id'],
						);
						$model_location->attributes = $data;
						$model_location->save();
					}
					$mysearch_conditions    =   array(
                            'location'  =>  array(
                                    'country_id'  =>  array('=' => $model->country_id)
							)
					);
					//set filter for search conditions
					if(!empty($model->sex_role)){
						$filter	=	array();
						switch ($model->sex_role){
							case ProfileSettingsConst::SEXROLE_TOP:
								$filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_BOTTOM, ProfileSettingsConst::SEXROLE_VERSATILE), 
								);
								break;
							case ProfileSettingsConst::SEXROLE_BOTTOM:
								$filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_VERSATILE), 
								);
								break;
							default:
								$filter['sex_role'] = array(
		                        	'in' => array(ProfileSettingsConst::SEXROLE_TOP, ProfileSettingsConst::SEXROLE_BOTTOM), 
								);
								break;
						}
						$mysearch_conditions['filter'] = $filter;
					}
					// end set filter for search conditions

					
					$user->search_conditions = json_encode($mysearch_conditions);
					$user->save();
					$Elasticsearch	=	new Elasticsearch();
					$Elasticsearch->updateSearchIndexUser(Yii::app()->user->id);
					echo json_encode(array('status'=>true));
					Yii::app()->end();
				}
			}

		}
		
		$country_id		=	0;
		$state_id		=	0;
		$city_id		=	0;
		$district_id	=	0;
		
		$list_city	=	array();
		$list_state	=	array();
		$list_district	=	array();
		
		$country_on_cache = new CountryonCache();
		$city_on_cache = new CityonCache();
		$state_on_cache = new StateonCache();
		$district_on_cache = new DistrictonCache();

		$list_country = $country_on_cache->getListCountry();
		
		if ($list_country) {
			if (!$model->country_id) {
				$current_country = $country_on_cache->getCurrentCountry();
				$country_id	=	$current_country['id'];
			}else{
				$country_id	=	$model->country_id;
			}
			//get state list
			$list_state	=	$state_on_cache->getlistStateinCountry($country_id);
			
			if($list_state){
				//get city list
				if(!$model->state_id){
					$first_state	=	current($list_state);
					$model->state_id	=	$first_state['id'];
				}
				$list_city	=	$city_on_cache->getlistCityinState($model->state_id);
				if($list_city){
					//get district list
					if(!$model->city_id){
						$first_city	=	current($list_city);
						$model->city_id	=	$first_city['id'];
					}
					$list_district	=	$district_on_cache->getlistDistrictinCity($model->city_id);
					if(!$list_district){
						$list_district	=	array();
					}
					if(!$model->district_id){
						$first_district	=	current($list_district);
						$model->district_id	=	$first_district['id'];
					}
				}else{
					$list_city	=	array();
				}
			}else{
				$list_state	=	array();
			}			

		}
		
		$this->render('page/step-update-profile',array(
                'model'=>$model,
				'model_profile' => $model_profile,
                'list_country' => $country_on_cache->getListCountry(),
                'list_city' => $list_city,
				'list_state'	=>	$list_state,
				'country_id'	=>	$country_id,
				'state_id'	=>	$model->state_id,
				'city_id'	=>	$model->city_id,
				'district_id'	=>	$model->district_id,
				'list_district' => $list_district,
				'weight_unit_label'	=>	$weight_unit_label,
				'height_unit_label'	=>	$height_unit_label,
                'list_safer_sex' => yii::app()->params['constants']['safe_sex'],
                'list_ethinics' => yii::app()->params['constants']['ethinics']
		));
	}

	public function actionStepAvatar(){
		$this->checkLogin();
		
		//update step register
		$user =  Yii::app()->user->data();
		$user->register_step = YumUser::REGISTER_STEP_UPDATE_AVATAR;
		//save
		$user->save();
		
		$this->render('page/step-avatar',array());
	}

	public function actionStepSuggest(){
		$this->checkLogin();


		$limit = Yii::app()->params->register_suggest_box['limit_display'];
		//update step register
		$usercurrent = Yii::app()->user->data();
		$usercurrent->register_step = YumUser::REGISTER_STEP_COMPLETE;
		//save
		$usercurrent->save();
		
		$country_in_cache   =   new CountryonCache();
		$search_conditions = array();
		
		$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		
		$state_info		=	array();
		$city_info		=	array();
		$district_info	=	array();
		$country_info	=	array();
		
		$filter = array();
		
		$limit = Yii::app()->params->search_result['limit_display'];
		$offset = Yii::app()->request->getParam('offset', 0);
		
		//set by real IP
		if(!isset(Yii::app()->session['checkin_country_id'])){
			$current_country = $country_in_cache->getCurrentCountry();
			if(isset($current_country['id'])){
				$country_id	=	$current_country['id'];
				$filter['current_country_id'] = array('=' => $country_id);
			}else{
				if (!empty($profile_location->current_country_id)) {
					$filter['current_country_id'] = array('=' => $profile_location->current_country_id);
				}
			}
		}else{
			$filter['current_country_id'] = array('=' => Yii::app()->session['checkin_country_id']);
		}
		
		//get country info
		$country_info	=	SysCountry::model()->getCountryInfo($filter['current_country_id']['=']);
		
		if(isset(Yii::app()->session['checkin_state_id'])){
			$filter['current_state_id'] = array('=' => Yii::app()->session['checkin_state_id']);
			$state_info	=	LocationState::model()->getStateInfo(Yii::app()->session['checkin_state_id']);
		}
		if(isset(Yii::app()->session['checkin_city_id'])){
			$filter['current_city_id'] = array('=' => Yii::app()->session['checkin_city_id']);
			$city_info	=	SysCity::model()->getCityInfo(Yii::app()->session['checkin_city_id']);
		}
		if(isset(Yii::app()->session['checkin_district_id'])){
			$filter['current_district_id'] = array('=' => Yii::app()->session['checkin_district_id']);
			$district_info	=	SysDistrict::model()->getDistrictInfo(Yii::app()->session['checkin_district_id']);
		}
		$search_conditions = array(
				'filter' => $filter,
				'keyword' => false,
				'country_id'	=>	$filter['current_country_id']['=']
		);
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		//remove myself
		$user_id_except = array(Yii::app()->user->id);
		
		$elasticsearch	=	new Elasticsearch();
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);
        
		$this->render('page/step-suggest',array(
             'total' => $data_search['total'],
             'data' => $data_search['fulldata']
		));
	}

}