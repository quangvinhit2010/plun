<?php
class ReceiveInvitationForm extends CFormModel
{
	public $email;

	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
		);
	}
	
	public function save()
	{
		$event = EventBetaReceiveInvitation::model()->findByAttributes(array('email'=>$this->email));
		if(empty($event)){
			$event = new EventBetaReceiveInvitation();
		}
		$event->email = $this->email;
		$event->created = time();
		$event->validate();
		if(!$event->hasErrors()){
			$event->save();
		}
		
	}
	

}
