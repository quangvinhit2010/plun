<?php
class RegisterController extends Controller
{
    public $usercurrent;
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
		$model = new RegisterForm();
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
				if($model->save()){
					$frlogin=new LoginForm();
					$frlogin->loginByUsernamePass($model->username, $model->password, false);					
					$this->redirect(array('stepUpdateProfile'));
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
        			$Elasticsearch	=	new Elasticsearch();
        			$Elasticsearch->registerSearchIndex($user->id);
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
		$model = new RegisterForm();
		/*
		 * get infomation from facebook
		 */
		$user = Yii::app()->facebook->getUser();
		if (!empty($user)){
			$accessToken = Yii::app()->facebook->getAccessToken();
			Yii::app()->facebook->setAccessToken($accessToken);
			$facebookUser = Yii::app()->facebook->getUser();
			$fuser = Yii::app()->facebook->api('/me');
			$model->email = $fuser['email'];
		} else {
			$loginUrl = Yii::app()->facebook->getLoginUrl(array('scope' => 'email,read_stream'));
			$this->redirect($loginUrl);
		}
		/*
		 * register from form
		 */
		if(Yii::app()->request->isPostRequest){
			$model->attributes = Yii::app()->request->getPost('RegisterForm');
			$model->validate();
			if(!$model->hasErrors()){
				if($model->save()){
					$frlogin=new LoginForm();
					$frlogin->loginByUsernamePass($model->username, $model->password, false);
					$this->redirect('/');
				}
			}
		}

		$this->render('partial/facebook',array('model'=>$model));
	}
	/**
	 * Google OpenID
	 */
	public function actionGoogle()
	{
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
		$client->setClientId('248132742114.apps.googleusercontent.com');
		$client->setClientSecret('ZnI8a2kicgOabWpU8rA3Ycar');
		$client->setRedirectUri('http://localhost.plun.asia/site/google');
		$client->setDeveloperKey('AIzaSyCVsMPTAY6W0R3uGwNrqspnTvNnquS0bHo');
		$plus = new Google_PlusService($client);
		$oauth2 = new Google_Oauth2Service($client);

		if (isset($_GET['code'])) {
			$client->authenticate();
			$_SESSION['token'] = $client->getAccessToken();
			$redirect = 'http://localhost.plun.asia/site/google';
			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token'])) {
			$client->setAccessToken($_SESSION['token']);
		}

		if ($client->getAccessToken()) {
			$guser = $oauth2->userinfo->get();
			$model->email = $guser['email'];
			//$activities = $plus->activities->listActivities('me', 'public');
			// We're not done yet. Remember to update the cached access token.
			// Remember to replace $_SESSION with a real database or memcached.
			$_SESSION['token'] = $client->getAccessToken();
		} else {
			$authUrl = $client->createAuthUrl();
			$this->redirect($authUrl);
		}
		/*
		 * register from form
		 */
		if(Yii::app()->request->isPostRequest){
			$model->attributes = Yii::app()->request->getPost('RegisterForm');
			$model->validate();
			if(!$model->hasErrors()){
				if($model->save()){
					$frlogin=new LoginForm();
					$frlogin->loginByUsernamePass($model->username, $model->password, false);
					$this->redirect('/');
				}
			}
		}
		$this->render('partial/google',array('model'=>$model));
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
		$this->layout = 'user-page';;
		$this->render('page/step-find-friends',array('limit' => $limit));
	}


	public function actionTwitterCallBack() {
		$this->layout = 'user-page';;
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
		$this->layout = 'user-page';;
		
		//update step register
		$user =  Yii::app()->user->data();
		$this->usercurrent = $user;
		
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
		$unit = json_decode($model->persional_unit, true);
		/*
		//build unit weight
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
		if (!empty($model->birthday)) {
			//build month
			$model->birthday_month = date('n', $model->birthday);
			//build day
			$model->birthday_day = date('j', $model->birthday);
		}				
		if(Yii::app()->request->isPostRequest){
			$model->user_id       =   Yii::app()->user->id;

			$post = Yii::app()->request->getPost('UsrProfileSettings');
			
			/*
			if(!empty($post['hobbies'])){
    			$post['hobbies']	=	json_encode(explode(',', $post['hobbies']));
			}else{
    			$post['hobbies']	= '';
			}
			*/
			
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
				'list_district' => $list_district,
                'list_safer_sex' => yii::app()->params['constants']['safe_sex'],
                'list_ethinics' => yii::app()->params['constants']['ethinics']
		));
	}

	public function actionStepAvatar(){
		$this->checkLogin();
		
		//update step register
		$user =  Yii::app()->user->data();
		$this->usercurrent = $user;
		$user->register_step = YumUser::REGISTER_STEP_UPDATE_AVATAR;
		//save
		$user->save();
		
		$this->layout = 'user-page';;
		$this->render('page/step-avatar',array());
	}
	
	public function actionStepSkip(){
		//update step register
		$user =  Yii::app()->user->data();
		$this->usercurrent = $user;
		$user->register_step = YumUser::REGISTER_STEP_COMPLETE;
		//save
		$user->save();
	}

	public function actionStepSuggest(){
		$this->checkLogin();


		$limit = Yii::app()->params->register_suggest_box['limit_display'];
		$this->layout = 'user-page';;
		
		//update step register
		$usercurrent = Yii::app()->user->data();
		$usercurrent->register_step = YumUser::REGISTER_STEP_COMPLETE;
		//save
		$usercurrent->save();
		
		$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
		
		$search_online_conditions = array();
		$UserOnlineonCache = new UserOnlineonCache();
		$search_conditions = array();
		$filter = array();
		$sort_by = array();

		if (!empty($usercurrent->search_conditions)) {
			$mysearch_conditions = json_decode($usercurrent->search_conditions, true);

            //order by state
            if ($profile_location->current_country_id) {
            	$filter['current_country_id'] = array('=' => $profile_location->current_country_id);
            } 
			//sort by

			if (isset($usercurrent->profile_settings)) {
				//order by sex roles
				if ($usercurrent->profile_settings->sex_role) {
					switch ($usercurrent->profile_settings->sex_role){
						case ProfileSettingsConst::SEXROLE_TOP:
							$sort_by['sex_role'] = ProfileSettingsConst::SEXROLE_BOTTOM;
							break;
						case ProfileSettingsConst::SEXROLE_BOTTOM:
							$sort_by['sex_role'] = ProfileSettingsConst::SEXROLE_TOP;
							break;
						case ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE:
                        	$sort_by['sex_role'] = ProfileSettingsConst::SEXROLE_TOP;
	                   		break;  
	                    case ProfileSettingsConst::SEXROLE_TOP_VERSATILE:
	                        $sort_by['sex_role'] = ProfileSettingsConst::SEXROLE_BOTTOM;
	                    	break;
						default:
							$sort_by['sex_role'] = ProfileSettingsConst::SEXROLE_TOP;
							break;
					}
				}
				//order by smoke
				if ($usercurrent->profile_settings->smoke) {
					$sort_by['smoke'] = $usercurrent->profile_settings->smoke;
				}
				//order by safer_sex
				if ($usercurrent->profile_settings->safer_sex) {
					$sort_by['safer_sex'] = $usercurrent->profile_settings->safer_sex;
				}
				if ($usercurrent->profile_settings->ethnic_id) {
					$sort_by['ethnic_id'] = $usercurrent->profile_settings->ethnic_id;
				}
			}
		}

		//$search = new LucenseSearch();
		/*
		$list_user_online = $UserOnlineonCache->search(array(
            'country_id' => (isset($filter['country_id']) ? $filter['country_id'] : false),
            'city_id' => (isset($filter['city_id']) ? $filter['city_id'] : false),
		));
		unset($list_user_online['info_list'][$usercurrent->id]);

		if ($list_user_online['id_list']) {
			$user_id_except = array_merge(array($usercurrent->id), $list_user_online['id_list']);
		} else {
			$user_id_except = array($usercurrent->id);
		}
		*/
		$search_conditions = array(
            'filter' => $filter,
            'sort_by' => $sort_by,
            'keyword' => false,
			'country_id'	=>	$profile_location->current_country_id
		);
		
	    $my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
        //remove myself
        $user_id_except = array(Yii::app()->user->id);
		//$results = $search->querySearchIndex($search_conditions, $user_id_except, false, 0, $limit);
        
		$elasticsearch	=	new Elasticsearch();
        $data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, 0, $limit);
        
		$this->render('page/step-suggest',array(
             'total' => $data_search['total'],
             'data' => $data_search['fulldata']
		));
	}
}