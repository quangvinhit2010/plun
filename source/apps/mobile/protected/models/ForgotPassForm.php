<?php
class ForgotPassForm extends CFormModel
{
	public $email;
	public $verifyCode;
	public $user;
	
	public function rules()
	{
		return array(
			array('email', 'required'),
	        array('email', 'email'),
	        array('email', 'checkexists'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
		);
	}
	
	public function checkexists($attribute, $params) {
	    $user = null;
	
	    // we only want to authenticate when there are no input errors so far
	    if(!$this->hasErrors()) {
	        if (strpos($this->email,"@")) {
	            $profile = YumProfile::model()->findByAttributes(array(
	                    'email'=>$this->email));
	            $this->user = $profile
	            && $profile->user
	            && $profile->user instanceof Member ? $profile->user : null;
	        } else{
	            $this->user = Member::model()->findByAttributes(array(
	                    'username'=>$this->email));
	        }
	
	    }
	    
	    if(empty($this->user->id)){
	        $this->addError('email', Lang::t('forgot', 'Email does not exist. Please make sure you\'ve entered correct email address.'));
	    }
	}

}
