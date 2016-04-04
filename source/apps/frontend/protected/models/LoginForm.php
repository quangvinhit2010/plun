<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $online_lookingfor;
	public $rememberMe;
	public $user;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			// username and password are required
			array('username, password, online_lookingfor', 'safe'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
			'username'=>Lang::t('login', 'Username'),
			'password'=>ucfirst(Lang::t('login', 'password')),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$exist = Member::getUserByUsername($this->username);
			if(empty($exist->id)){
				$this->addError('username',Lang::t('login', 'Username not exist.'));
			}
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate()){
				$this->addError('password',Lang::t('login', 'Incorrect username or password.'));
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			
			/***process after login****/
			VLang::model()->setLangDefault();
			$this->user	=	Yii::app()->user->data();
			$this->user->lastvisit = time();
			$this->user->save();
			
			$elasticsearch	=	new Elasticsearch();
			$elasticsearch->updateLastActivity($this->user->id, time());
			$e_user			=	$elasticsearch->load(Yii::app()->user->id);
			if(!empty($e_user)){
				$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
				$update_data	=	array(
						'friendlist'	=>	implode(',', $my_friendlist)
				);
				$elasticsearch->update($update_data, 0, Yii::app()->user->id);
			}
			
			$cache_key = Yii::app()->session->sessionID;
			$cookie = array('name' => 'cache_key', 'value' => $cache_key, 'valueDefault' => null);
			Util::writeCookieNoEncrypt($cookie);
			return true;
		}
		else
			return false;
	}
	/**
	 * @return boolean
	 */
	public function loginByUsernamePass($username, $password, $rememberMe = false)
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($username, $password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
