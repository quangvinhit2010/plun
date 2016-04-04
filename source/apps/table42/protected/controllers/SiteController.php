<?php

class SiteController extends Controller
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
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'published' => 1));

		$nextround	=	Table42Round::model()->getNextRound();
			
		$profile_setting_model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
				
		//if you did signup before
		$signup	=	false;
		$signup_nextround = false;
		$resigup = false;
		if($checkprofile){
			$upload	=	($checkprofile->step	==	Table42Profile::STEP_SIGNUP)	?	true	:	false;
			if(($checkprofile->round_id == $this->currentround->id || $checkprofile->round_id == $nextround->id) && $checkprofile->step == Table42Profile::STEP_THUMBNAIL){
				$signup	=	true;
			}else{
				//repeat signup
				if($checkprofile->round_id != $this->currentround->id && $checkprofile->round_id != $nextround->id){
					$resigup = true;
				}
				$signup	=	false;
			}
		}else{
			$signup	=	false;
			$upload	=	false;
		}
		
		if($profile_setting_model){
			if($profile_setting_model->sex_role == ProfileSettingsConst::SEXROLE_TOP || $profile_setting_model->sex_role== ProfileSettingsConst::SEXROLE_BOTTOM){
				$sex_role	=	1;
			}else{
				if(is_null($profile_setting_model->sex_role)){
					$sex_role	=	0;
				}else{
					$sex_role	=	2;
				}
			}
		}else{
			$sex_role	=	0;
		}
				
		$model=new LoginForm();
		$register_model = new RegisterForm();
		$this->render('index', array(
				'model'	=>	$model,
				'register_model'	=>	$register_model,
				'upload'	=>	$upload,
				'currentround'	=>	$this->currentround,
				'signup'	=>	$signup,
				'sex_role'	=>	$sex_role,
				'resigup'	=>	$resigup,
				'signup_ok'	=>	$this->signup_ok,
				'signup_nextround'	=>	$signup_nextround,
				'profile'	=>	$checkprofile
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
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
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
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
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}