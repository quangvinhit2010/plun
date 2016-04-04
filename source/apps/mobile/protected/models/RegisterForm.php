<?php

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user Register form data. It is used by the 'Register' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $username;
	public $lastname;
	public $firstname;
	public $day;
	public $month;
	public $year;
	public $email;
	public $password;
	public $confirm_password ;
	public $verifyCode;
	
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that email and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
	    $usernameRequirements = Yum::module()->usernameRequirements;
		return array(
			// email and password are required
			array('username, email, lastname, firstname, password, confirm_password', 'required',
		        'message' => Lang::t('yii', '{attribute} cannot be blank.')
		    ),
			// email has to be a valid email address
			array('email', 'email'),
			array('email', 'validation'),
			array('username', 'validation'),
	        array('username', 'length',
	                'max' => $usernameRequirements['maxLen'],
	                'min' => $usernameRequirements['minLen'],
	                'message' => Lang::t('yii',
	                        'Username length needs to be between {minLen} and {maxlen} characters', array(
	                                '{minLen}' => $usernameRequirements['minLen'],
	                                '{maxLen}' => $usernameRequirements['maxLen']))),
	        array(
	                'username',
	                'match',
	                'pattern' => $usernameRequirements['match'],
	                'message' => Lang::t('yii', "Incorrect username. (a-z0-9)"),
            ),
	        array('confirm_password', 'compare',
	                'compareAttribute'=>'password',
	                'message' => Lang::t('yii', "Confirm password is incorrect.")),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
	        array('username, email, lastname, firstname, day, month, year, password, confirm_password', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>Lang::t('register', 'Username'),
			'email'=>Lang::t('register', 'Email'),
			'lastname'=>Lang::t('register', 'Last Name'),
			'firstname'=>Lang::t('register', 'First Name'),
			'password'=>Lang::t('register', 'Password'),
			'confirm_password'=>Lang::t('register', 'Confirm Password'),
			'verifyCode'=>Lang::t('register', 'Captcha'),
		);
	}
	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validation($value){
		$age = $this->get_age($this->day, $this->month, $this->year);
		if($age < 16){
			$chk = true;
			$value = 'day';
			$msg = "Must than 16 year old.";
		}
		switch ($value){
			case 'username':
				$chk = Member::model()->findByAttributes(array('username'=>$this->username));
				$msg = Lang::t('register', "Someone already has that username. Please try another!");
				break;
			case 'email':
				$chk = YumProfile::model()->findByAttributes(array('email'=>$this->email));
				$msg = Lang::t('register', "Someone already use that email. Please try another!");
				break;
		}
		if(!empty($chk)){
			$this->addError($value, $msg);
		}
	}
	
	/**
	 * @param $dob_day
	 * @param $dob_month
	 * @param $dob_year
	 * @return number
	 */
	private function get_age($dob_day,$dob_month,$dob_year){
		$year   = gmdate('Y');
		$month  = gmdate('m');
		$day    = gmdate('d');
		//seconds in a day = 86400
		$days_in_between = (mktime(0,0,0,$month,$day,$year) - mktime(0,0,0,$dob_month,$dob_day,$dob_year))/86400;
		$age_float = $days_in_between / 365.242199; // Account for leap year
		$age = (int)($age_float); // Remove decimal places without rounding up once number is + .5
		return $age;
	}
	/**
	 * @return boolean
	 */
	public function save()
	{
		$this->username = strtolower($this->username);
		Yii::app()->cache->flush();
		if(!empty($this->email)){
			$user = new Member();
			$user->username = $this->username;
// 			$user->alias_name = substr($this->username, 0, 3).uniqid();
			$user->alias_name = $this->username;
			$user->salt = YumEncrypt::generateSalt();
			$user->password = YumEncrypt::encrypt($this->password, $user->salt);
			$user->createtime = time();
			$user->activationKey = $user->generateActivationKey(false);
			$user->status = Member::STATUS_ACTIVE;
			$user->validate();
			
			
			if($user->save()) {
				if(Yum::hasModule('profile')) {
					$profile = new YumProfile();
					$profile->user_id = $user->id;
					$profile->timestamp = time();
					$profile->privacy = 'protected';
					$profile->email = $this->email;
					$profile->lastname = $this->lastname;
					$profile->firstname = $this->firstname;
					$profile->validate();
					if($profile->save()){
						
						//detect country
						$country_on_cache = new CountryonCache();
						$current_country = $country_on_cache->getCurrentCountry();
						$country_id	=	isset($current_country['id'])	?	$current_country['id']	:	0;
						
						//create setting
					    $profileSettings = new UsrProfileSettings();
					    $profileSettings->user_id = $user->id;
					    $profileSettings->country_id	=	$country_id;
					    $profileSettings->birthday = mktime(0, 0, 0, $this->month, $this->day, $this->year);
					    $profileSettings->birthday_year = $this->year;
					    $profileSettings->save();
						
						//create profile current location

						$model_location     =   new UsrProfileLocation();
						$model_location->user_id       =   $user->id;
						$model_location->current_country_id       =   $country_id;
						$model_location->save();
						
						//register on index
						$Elasticsearch	=	new Elasticsearch();
						$Elasticsearch->registerSearchIndex($user->id);

						
					    InvitationForm::useGiftcode($user->id);
					    $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/register-welcome',array('user'=>$user, 'activation_url'=>$user->getActivationUrl()), true);
				        $subject = strtr(Lang::t('yii','Please activate your account for {username}'), array(
							'{username}' => $user->username));
					    $sent = Mailer::send ( $this->email,  $subject, $body);
						return true;
					}										
				}
			}
		}
		return false;		
	}

}
