<?php
class InvitationForm extends CFormModel
{
	public $code;

	public function rules()
	{
		return array(
			array('code', 'required'),
			array('code', 'safe'),
			array('code', 'ValidateCode'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'code'=>'Invitation Code',
		);
	}
	
	public function ValidateCode(){
		if(!$this->hasErrors()){
			$model = Giftcode::model()->find('code = :code AND user_id = 0 AND status = 0', array('code' => $this->code));
			if(!$model){
				$this->addError('code', 'Your Invitation code invalid.');
			} else {
				$session = Util::getSession();
				$session->setTimeout ( 900 );
				$session->add ( 'invitation_code_unique', $model->unique);
			}
		}
	}
	
	public static function hasInvitation(){
		if(!empty(Yii::app()->params->Invitation['enable']) && Yii::app()->params->Invitation['enable'] == true){
			return true;
		}
		return false;
	}
	
	public static function enableInvitation(){
		if(self::hasInvitation()){
			if(empty(Yii::app()->session['invitation_code_unique'])){
				return true;
			}
			return false;
		}
		return false;
	}
	
	public static function validateInvitation(){
		if(self::hasInvitation()){
			if(!empty(Yii::app()->session['invitation_code_unique'])){
				return true;
			}
			return false;
		}
		return true;
	}
	
	public static function useGiftcode($user_id){
		if(!empty($user_id) && self::hasInvitation() && !empty(Yii::app()->session['invitation_code_unique'])){
			$return = Giftcode::model()->useGiftcode($user_id, Yii::app()->session['invitation_code_unique']);
			if($return){
			    $session = Util::getSession();
			    $session->remove ( 'invitation_code_unique' );
			}
		}
	}

}
