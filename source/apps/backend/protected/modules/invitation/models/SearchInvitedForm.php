<?php
class SearchInvitedForm extends CFormModel
{
	public $username;
	public $from_date;
	public $to_date;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username are required
//			array('from_date, to_date', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'Username',
		);
	}

}