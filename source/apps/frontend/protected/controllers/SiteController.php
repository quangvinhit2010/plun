<?php

class SiteController extends Controller
{
    public $basePath;
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
                'fontSize'=>'20',
                'angle'=>false,
		    ),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
				'basePath'=>'pages/'.Yii::app()->language,
				'layout'=>'layout',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if(!Yii::app()->user->isGuest){
			CController::forward('/newsFeed/feed/alias/'.Yii::app()->user->data()->getAliasName(), false);
	        Yii::app()->end();
		}else{
			$this->redirect('/home');
			Yii::app()->end();						
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
	}

	public function actionHome()
	{
		if(!Yii::app()->user->isGuest){
			$this->redirect('/');
			Yii::app()->end();
		}else{			
			$model=new LoginForm();
			if(isset($_POST['LoginForm']) && Yii::app()->request->isPostRequest)
			{
				$model->attributes=$_POST['LoginForm'];
				$model->validate();
				if(!$model->hasErrors() && $model->login()){
					$redirect = $model->user->getUserFeedUrl();
					if($model->user->register_step){
						switch ($model->user->register_step){
							case YumUser::REGISTER_STEP_UPDATE_PROFILE:
								$this->redirect(Yii::app()->createUrl('//register/stepUpdateProfile'));
								break;
							case YumUser::REGISTER_STEP_UPDATE_AVATAR:
								$this->redirect(Yii::app()->createUrl('//register/stepAvatar'));
								break;
						}
					}
					$this->redirect($redirect);
				}
			}
			$this->render('index',array('model'=>$model));
		}
	}
	
	public function actionLang()
	{
	    $_lang = Yii::app()->request->getParam('_lang');
	    if(!empty($_lang) && !empty(Yii::app()->user->returnUrl)){
	    	$_urlReferrer = Yii::app()->request->getUrlReferrer();
	    	if(!empty($_urlReferrer)){
	    		Yii::app()->user->returnUrl = $_urlReferrer;
	    	}
	        if(!Yii::app()->user->isGuest){
	            $langID = VLang::model()->getIdLangDefaultByCodeLang($_lang);
	            $cri = new CDbCriteria();
	            $cri->addCondition("user_id = :user_id");
	            $cri->params = array(':user_id'=>Yii::app()->user->id);
	            $profile_settings = UsrProfileSettings::model()->find($cri);
	            if(!empty($profile_settings) && $profile_settings->default_language != $langID){
    	            $profile_settings->default_language = $langID;
    	            $profile_settings->save();
	            }
	        }
            VLang::model()->setLangDefault();
	        $this->redirect(Yii::app()->user->returnUrl);
	    }
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    $this->layout = 'error';
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm();
		
		$subjectOptions = $model->getSubjectOptions();
		
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$subject = $model->subject;
				
				if($subject=='1')
					$mail_to = Yii::app()->params['advertisingEmail'];
				elseif($subject=='2')
					$mail_to = Yii::app()->params['technicalEmail'];
				elseif($subject=='3')
					$mail_to = Yii::app()->params['supportEmail'];
				
				$model->subject = $subjectOptions[$model->subject];
				
				$mail_subject  = "Contact info from PLUN";
				$mail_body  = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/contact',array(
					'name'=>$model->name,
					'email'=>$model->email,
					'phone_number'=>$model->phone_number,
					'subject'=>$model->subject,
					'body'=>$model->body,
				), true);
				
				$sent = Mailer::send ( $mail_to,  $mail_subject, $mail_body);
				$sent = Mailer::send ( Yii::app()->params['adminEmail'],  $mail_subject, $mail_body);
				
				$model->save(false);
				/** send ticket **/
				$data = array(
					'name'      =>      $model->name,
					'topicId'  	=>      '2',
					'email'     =>      $model->email,
					'phone'     =>      $model->phone_number,
					'subject'   =>      $model->subject,
					'message'   =>      $model->body,
					'ip'        =>      $_SERVER['REMOTE_ADDR'],
					'attachments' => array(),
				);
				$return = Yii::app()->osTicket->createTicket($data);
				/** end send ticket **/
				$msg = Yii::t('contact', 'Contact Success Message');
				$status = true;
			}else{
				$msg = Yii::t('contact', 'Contact Error Message');
				$status = false;
			}
			if(Yii::app()->request->isAjaxRequest){
				echo CJSON::encode(array('status'=>$status, 'msg'=>$msg));
				exit();
			}else{					
				Yii::app()->user->setFlash('contact',$msg);
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model, 'subjectOptions'=>$subjectOptions));
	}
	
	public function actionPopContact()
	{
		$model=new ContactForm();		
		$subjectOptions = $model->getSubjectOptions();
		$this->renderPartial('contact-pop',array('model'=>$model, 'subjectOptions'=>$subjectOptions));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = 'main2';
		//add flash error: Please login to use this feature  
		if(Yii::app()->request->getParam('msgLogin') == 'true' ){
			Yii::app()->user->setFlash('msgLogin', Lang::t('login', 'Please sign-in to use this feature!'));
		}
		if(!Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->user->data()->getUserFeedUrl());
		}
		if(Yii::app()->user->returnUrl == '/' && !empty(Yii::app()->user->returnUrl)){
			Yii::app()->user->returnUrl = Yii::app()->request->getUrlReferrer();
		}
		if($redirect_url = Yii::app()->request->getParam('redirect_url')){
		    Yii::app()->user->returnUrl = $redirect_url;
		}
		$model=new LoginForm();
		if(isset($_POST['LoginForm']) && Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['LoginForm'];
			$model->validate();
			if(!$model->hasErrors() && $model->login()){
			    $redirect = (!empty(Yii::app()->user->returnUrl)) ? Yii::app()->user->returnUrl : $model->user->getUserFeedUrl();
		        if($model->user->register_step){
		            switch ($model->user->register_step){
		                case YumUser::REGISTER_STEP_UPDATE_PROFILE:
		                    $this->redirect(Yii::app()->createUrl('//register/stepUpdateProfile'));
		                    break;
		                case YumUser::REGISTER_STEP_UPDATE_AVATAR:
		                    $this->redirect(Yii::app()->createUrl('//register/stepAvatar'));
		                    break;
		            }
		        }
		        $this->redirect($redirect);
			}
		}		
		$this->render('login/index',array('model'=>$model));
	}
	public function actionUpdateProfileSocial(){
		$this->layout = 'main2';
		
		if(!isset(Yii::app()->session['register_social_id'])){
			$this->redirect(Yii::app()->createUrl('//home'));
		}
		$check_facebook_register = Member::model()->findByAttributes(
				array(
						'openid_identification' =>  Yii::app()->session['register_social_id'],
						'openid_type'			=>	Yii::app()->session['register_social_type']
				)
		);
		$result	=	array();	
		if(!$check_facebook_register){
			$this->redirect(Yii::app()->homeUrl);
		}
		if(Yii::app()->request->isPostRequest && $check_facebook_register)
		{
			
			if($check_google_register->openid_register_complete){
				$this->redirect(Yii::app()->homeUrl);
			}
			
			$random_password	=	Util::randomDigits(8);
			
			
			$salt = YumEncrypt::generateSalt();
			$password = YumEncrypt::encrypt($random_password, $salt);
			
			$post = Yii::app()->request->getPost('UpdateSocial');
			
			//check username
			$criteria = new CDbCriteria();
			$criteria->condition = "username <> '{$check_facebook_register->username}' AND username = '{$post['username']}'";
			$check_username = Member::model()->count($criteria);

			if($check_username == 0){
				
				//update profile
				$check_facebook_register->username	=	$post['username'];
				$check_facebook_register->openid_register_complete	=	1;
				$check_facebook_register->salt	=	$salt;
				$check_facebook_register->password	=	$password;
				
				$check_facebook_register->validate();
				if($check_facebook_register->hasErrors()){
					$error				=	$check_facebook_register->errors;
					$result['result']	=	false;
					$result['msg']		=	$error['username'][0];
				}else{
					$check_facebook_register->save();	
					
					//update profile settings
					$model_profilesetting 					= UsrProfileSettings::model()->findByAttributes(array('user_id' => $check_facebook_register->id));
					if($model_profilesetting){
						$model_profilesetting->user_id			=	$check_facebook_register->id;
						$model_profilesetting->sex_role			=	$post['sex_role'];
						$model_profilesetting->sexuality		=	$post['sexuality'];
						$model_profilesetting->birthday			=	mktime(0,0,0, $post['birthday_month'], $post['birthday_day'], $post['birthday_year'] );
						$model_profilesetting->birthday_year	=	$post['birthday_year'];
						$model_profilesetting->country_id		=	$post['country_id'];
							
						$model_profilesetting->save();
							
					}
					//update location
					$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => $check_facebook_register->id));
					if($model_location){
						$model_location->current_country_id	=	$post['country_id'];
						$model_location->save();
					}
					$Elasticsearch	=	new Elasticsearch();
					$Elasticsearch->updateSearchIndexUser($check_facebook_register->id);
					
					$result['result']	=	true;
					
					$uerIdentity = new UserIdentity($check_facebook_register->username, '');
					$uerIdentity->authenticate(TRUE);
					if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
						Yii::app()->user->login($uerIdentity, 3600*24*30);
					
					if(!empty(CParams::load()->params->phpmailer->func_send->register)){
						
						$check_email_exist = YumProfile::model()->findByAttributes(
								array(
										'user_id'	=>	$check_facebook_register->id
								)
						);
						
						$socials	=	array(
							'1' => 'Facebook',
							'2'	=>	'Google+'	
						);
			
						
						$body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/register-social-welcome', 
								array 
						(
							'username'	=>	$post['username'], 
							'social'	=>	$socials[Yii::app()->session['register_social_type']],
							'email'		=>	$check_email_exist->email,
							'password' => $random_password,
							'changepass_url' => $check_facebook_register->createAbsoluteUrl('//settings/changepass')
						), 
						true);
						
						$subject = 'Welcome to Plun!';
						$sent = Mailer::send ( $check_email_exist->email,  $subject, $body);
					}
				}
			}else{
				$result['result']	=	false;
				$result['msg']		=	Lang::t('register', 'Someone already has that username. Please try another!');
			}
			echo json_encode($result);
			exit;
		}
		
		

		$this->render('UpdateProfileSocial',array(
				'username'	=>	$check_facebook_register->username
		));
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		if(isset(Yii::app()->user->id)){
			
			// remove this code when migrate completed
			if(!empty(CParams::load()->params->vtn->migrate->logout))
				$userid = Yii::app()->user->id;
			// remove this code when migrate completed
			
			$elasticsearch	=	new Elasticsearch();
			$elasticsearch->updateLastActivity(Yii::app()->user->id, false);
		
			//clear session check-in
			Yii::app()->session->clear();
			Yii::app()->session->destroy();
				
			Yii::app()->user->logout();
			Yii::app()->xmpp->logout();
			
			// remove this code when migrate completed
			if(!empty(CParams::load()->params->vtn->migrate->logout)) {
				$query = "SELECT * FROM vbb_migrate WHERE plun_user_id = ".$userid;
				$command = Yii::app()->db_vtn->createCommand($query);
				$row = $command->queryRow();
					
				if($row && isset($_COOKIE['vtn_sessionhash'])) {
					$vtnUserId = $row['vtn_user_id'];
						
					$now = time();
					$query = "UPDATE vbb_user SET lastactivity = $now, lastvisit = $now WHERE userid = $vtnUserId";
					$command = Yii::app()->db_vtn->createCommand($query);
					$command->execute();
						
					$query = "DELETE FROM vbb_session WHERE userid = $vtnUserId";
					$command = Yii::app()->db_vtn->createCommand($query);
					$command->execute();
						
					$sessionHash = $_COOKIE['vtn_sessionhash'];
					$query = "DELETE FROM vbb_session WHERE sessionhash = '$sessionHash'";
					$command = Yii::app()->db_vtn->createCommand($query);
					$command->execute();
						
					setcookie("vtn_sessionhash", "", time()-3600);
				}
			}
			// remove this code when migrate completed
		}
		
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Forgotpass
	 */
	public function actionForgotpass($email = null, $key = null)
	{
		$this->layout = 'main2';
		$model = new ForgotPassForm();
		if ($email != null && $key != null) {
		    if($profile = YumProfile::model()->find('email = :email', array('email' =>  $email))) {
	            $user = $profile->user;
	            if($user->status <= 0)
	                throw new CHttpException(403, 'User is not active');
	            else if($user->activationKey == $key) {
	                $passwordform = new UserChangePassword;
	                if (isset($_POST['UserChangePassword'])) {
	                    $passwordform->attributes = $_POST['UserChangePassword'];
	                    if ($passwordform->validate()) {
	                        $user->password = YumEncrypt::encrypt($passwordform->password, $user->salt);
	                        $user->activationKey = YumEncrypt::encrypt(microtime() . $passwordform->password, $user->salt);
	                        $user->save();
	                        Yii::app()->user->setFlash('forgotChangPasss', true);
	                        if(Yum::module('registration')->loginAfterSuccessfulRecovery) {
	                            $login = new YumUserIdentity($user->username, false);
	                            $login->authenticate(true);
	                            Yii::app()->user->login($login);
	                            /** send mail change pass **/
	                            $subject = strtr(Lang::t('forgot','Your password has been reset successfully'), array('{username}'=> $user->username));
	                            $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/password-changed',array('user'=>$user), true);
	                            $sent = Mailer::send ( $user->profile->email,  $subject, $body);
	                            /**update VTN**/
	                            if(!empty(CParams::load()->params->vtn->migrate->updateuser)){
	                            	$return = VTN::model()->updateUser(array(
	                            			'email'=>$user->profile->email,
	                            			'password'=>$passwordform->password,
	                            			'username'=>$user->username,
	                            	));
	                            }
	                            /**end**/
	                            $this->redirect(Yii::app()->homeUrl);
	                        }else {
	                            $this->redirect('//site/forgotpass');
	                        }
	                    }
	                }
	                $this->render('forgot/change-password', array('passform' => $passwordform));
	                Yii::app()->end();
	            } else {
	                $model->addError('email', Lang::t('forgot', 'Invalid recovery key'));
	                Yum::log(Yum::t(
	                'Someone tried to recover a password, but entered a wrong recovery key. Email is {email}, associated user is {username} (id: {uid})', array(
	                '{email}' => $email,
	                '{uid}' => $user->id,
	                '{username}' => $user->username)));
	            }
    	    }
		}else{
    		if(Yii::app()->request->isPostRequest){
    			$model->attributes = Yii::app()->request->getPost('ForgotPassForm');
    			$model->validate();
    			if(!$model->hasErrors()){
			        if($model->user instanceof YumUser) {
			            if($model->user->status <= 0)
			                throw new CHttpException(403, 'User is not active');
			            $model->user->generateActivationKey();
			            $recovery_url = $this->createAbsoluteUrl('//site/forgotpass' , array( 'key' => $model->user->activationKey, 'email' => $model->user->profile->email));
			            Yum::log(Yum::t(
			            '{username} successfully requested a new password in the password recovery form. A email with the password recovery url {recovery_url} has been sent to {email}', array(
			            '{email}' => $model->user->profile->email,
			            '{recovery_url}' => $recovery_url,
			            '{username}' => $model->user->username)));
			    
			            $subject = Lang::t('forgot','Password reset your account for {username}', array('{username}'=>$model->user->username));
			            $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/password-recovery',array('user'=>$model->user, 'recovery_url'=>$recovery_url), true);
			            $sent = Mailer::send ( $model->user->profile->email,  $subject, $body);
			        } else
			            Yum::log(Yum::t(
			                    'A password has been requested, but no associated user was found in the database. Requested user/email is: {username}', array(
			                            '{username}' => $model->email)));
			        $this->render('forgot/request-sent');
			        Yii::app()->end();
    			}
    		}
		}
		$this->render('forgot/index',array('model'=>$model));
	}
		
	public function actionTestAPI(){
		$xmpp = new XMPP();
		$xmpp->username = 'enterid';
		$xmpp->password = '123456';
		$xmpp->name = 'Nam Le';
		$xmpp->email = 'enterid1@gmail.com';
		$a = $xmpp->AddUser();
		var_dump($a);
		
	}
	
	public function actionComingsoon()
	{
	    $this->layout = 'empty';
	    $this->render('coming-soon');
	}
	
	public function actionDetectDevice()
	{
	    $this->render('device-not-support');
	}
	
	public function beforeAction($action) {
		if( parent::beforeAction($action) ) {
			if($action->id != 'hotbox'){
				$cs = Yii::app()->clientScript;
				$cs->registerMetaTag('PLUN Asia', null, null, array('property' => 'og:title'));
				$cs->registerMetaTag('PLUN Asia - Dating system for gay!', null, null, array('property' => 'og:site_name'));
				$cs->registerMetaTag(Yii::app()->request->getHostInfo().'/public/images/fb_logo_plun.jpg', null, null, array('property' => 'og:image'));
				
			}
			return true;
			
		}
		return false;
	}
	
	public function actionHotbox($id, $slug){
		$hotbox = Hotbox::model()->findByPk($id);
		if(preg_match('/^FacebookExternalHit\/.*?/i',$_SERVER['HTTP_USER_AGENT'])){
			// process here for Facebook
			$cs = Yii::app()->clientScript;
			$cs->registerMetaTag($hotbox->title, null, null, array('property' => 'og:title'));
			$cs->registerMetaTag(strip_tags($hotbox->body), null, null, array('property' => 'og:description'));
			if(isset($hotbox->images) && count($hotbox->images) >= 1){
				$image = Yii::app()->createAbsoluteUrl($hotbox->images[0]->getImageThumb(null, true));
				$cs->registerMetaTag($image, null, null, array('property' => 'og:image'));
			}
			$this->render('hotbox');
		} else {
			$url = Yii::app()->createUrl('//hotbox/load', array('id' => $id, 'slug' => $hotbox->slug));
			$this->redirect($url);
		}
		
	}
	public function actionUpdateLastActivity(){
		if(!Yii::app()->user->isGuest){
			$elasticsearch	=	new Elasticsearch();
			$elasticsearch->updateLastActivity(Yii::app()->user->id, time());
		}
		echo 1;
		exit;
	}		
	
	public function actionUsertop(){
	    $this->renderPartial('partial/user-top', array());
	}
	
	public function actionDownload($path){
	    if(!empty($path)){
	        $path = VHelper::model()->path (Util::decrypt($path));
	        if(file_exists($path)){
    	        EDownloadHelper::download($path);
	        }	    
	    }else{
	        $this->refresh();
	    }
	}
	
	public function actionPlayer($type, $vid){
	    if(!empty($type)){
    	    $id = Util::decryptRandCode($vid);
	        switch ($type){
	            case 'vimeo':
            	    header('Location: http://player.vimeo.com/video/'.$id.'?autoplay=1');
            	    exit;
	                break;
	            case 'youtube':
                    header('Location: https://www.youtube.com/embed/'.$id.'');
	                exit;
	                break;
	        }
	    }
	    return false;
	}
	
	public function actionUserSuggest() {
	
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
	
		$elasticsearch	=	new Elasticsearch();
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, false, $offset, $limit);
		
		$members	=	array();
		$params = CParams::load ();
		$img_webroot_url	=	$params->params->img_webroot_url;
		foreach ($data_search['fulldata'] as $key => $item) {
			$item	=	$item['_source'];
			
			$url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
			if($item['have_avatar']){
				$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
			}else{
				$avatar	=	$item['avatar'];
			}
			
			$members[$item['user_id']]	=	array(
					'id'	=>	$item['user_id'],
					'url'	=>	$url,
					'avatar' 		=>	$avatar,
					'username' 		=>	$item['username'],
					'online'	=>	(bool)(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time'])
			);
		}
		$count = Member::model()->cache(500)->countByAttributes(array('status'=>1));
		$return = array('members'=>$members, 'total'=>$count);
		echo json_encode($return);
		exit;
	}
	public function actionVtnCallback($param) {
		$param = json_decode(urldecode($param), TRUE);
		$ch = curl_init();
		$secret = 'sUxjwif4z6sKUAq7X4H6';
		$checkTokenUrl = $param['vtnDomain'].'/forum/OAuth.php?checkToken=1&token='.$param['token'].'&secret='.md5($secret.$param['token']);
		curl_setopt($ch, CURLOPT_URL, $checkTokenUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		if($output == '1') {
			$result = Yii::app()->db->createCommand('SELECT u.username FROM usr_profile p, usr_user u WHERE p.user_id = u.id AND p.email = :email')->bindParam(":email",$param['email'],PDO::PARAM_STR)->queryRow();
			if($result) {
				$username = $result['username'];
			} else {
				$result = Yii::app()->db->createCommand('SELECT * FROM usr_user WHERE username = :username')->bindParam(":username", $param['username'],PDO::PARAM_STR)->queryRow();
				if($result) {
					$bindParam = $param['username'].'%';
					$result = Yii::app()->db->createCommand("SELECT username FROM usr_user WHERE username LIKE :username ORDER BY username")->bindParam(":username", $bindParam,PDO::PARAM_STR)->queryAll();
					$suffix = 0;
					foreach ($result as $r) {
						if($r['username'] == $param['username'].$suffix)
							$suffix++;
					}
					$username = $param['username'].$suffix;
				} else
					$username = $param['username'];
	
				$registerForm = new RegisterForm();
				$registerForm->username = $username;
				$registerForm->email = $param['email'];
				$password = uniqid(mt_rand(), true);
				$registerForm->password = $password;
				$registerForm->lastname = $username;
				$registerForm->firstname = $username;
				$registerForm->save();
			}
	
			$uerIdentity = new UserIdentity($username, '');
			$uerIdentity->authenticate(TRUE);
			if($uerIdentity->errorCode === UserIdentity::ERROR_NONE)
				Yii::app()->user->login($uerIdentity, 3600*24*30);
	
			echo '<script>window.close(); window.opener.location.reload();</script>';
		}
	}
	
	public function actionAvatar($uid){
		$Elasticsearch	=	new Elasticsearch();
		$e_user			=	$Elasticsearch->load($uid);
		if(!empty($e_user)){
			$params = CParams::load ();
			$urlAvatar = "http://{$params->params->img_webroot_url}{$e_user['avatar']}";
			$this->redirect($urlAvatar);
		}
	}
}