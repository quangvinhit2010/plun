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
			CController::forward('/newsFeed/explode/alias/'.Yii::app()->user->data()->getAliasName(), false);
	        Yii::app()->end();
		}else{
			if(!empty(Yii::app()->params->Invitation['enable']) && Yii::app()->params->Invitation['enable'] == true){
				$this->redirect(array('/register/invitation'));
			}
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
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
	}

	public function actionLang()
	{
	    $_lang = Yii::app()->request->getParam('_lang');
	    $_type = Yii::app()->request->getParam('_type');
	    if(!empty($_lang) && !empty(Yii::app()->user->returnUrl)){
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
	        if(!empty($_type) && $_type == 'home'){
	            $this->redirect('login');
	        }else{
    	        $this->redirect(Yii::app()->user->returnUrl);
	        }
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
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$subject = $model->subject;
				$body = Lang::t('contact', 'Name').": {$model->name}"."<br/>";
				$body .= Lang::t('contact', 'Email').": {$model->email}"."<br/>";
				$body .= Lang::t('contact', 'Content').": "."<br/>";
				$body .= $model->body;
				$sent = Mailer::send ( Yii::app()->params['supportEmail'],  $subject, $body);
				Yii::app()->user->setFlash('contact',Lang::t('contact', 'Thank you for contacting us. We will respond to you as soon as possible.'));
				$this->refresh();
			}
		}
		
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
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
		$this->render('login/index',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		if(isset(Yii::app()->user->id)){
			$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
			$elasticsearch	=	new Elasticsearch();
			$elasticsearch->updateLastActivity(Yii::app()->user->id, false);
		
			//clear session check-in
			Yii::app()->session->clear();
			Yii::app()->session->destroy();
							
			Yii::app()->user->logout();
			Yii::app()->xmpp->logout();
		}
		$this->redirect(array('login'));
	}
	
	/**
	 * Forgotpass
	 */
	public function actionForgotpass($email = null, $key = null)
	{
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
			$elasticsearch->updateLastActivity(Yii::app()->user->id, time(), Elasticsearch::DEVICE_MOBILE);
		}
		echo 1;
		exit;
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