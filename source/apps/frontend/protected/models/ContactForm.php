<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CActiveRecord
{
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function init()
	{
		$this->create_time = time();
	}
	
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, subject, body, phone_number', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}
	
	public function tableName() {
		return 'sys_contact_form';
	}
	
	public function getSubjectOptions() {
		return array(
			''=>Lang::t('contact', 'Subject'),
			'1'=>Lang::t('contact', 'Advertising lnquiry'),
			'2'=>Lang::t('contact', 'Technical lssues'),
			'3'=>Lang::t('contact', 'Others'),
		);
	}
}