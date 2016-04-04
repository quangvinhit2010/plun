<?php
/**
 * @author quangvinh
 */
class InviteModel
{
	const CONTACT_TWITTER='twitter';
	const CONTACT_FACEBOOK='facebook';
	const CONTACT_GOOGLE='google';
	const CONTACT_YAHOO='yahoo';
	private static $_models=array();
	
	public static function model($className=__CLASS__){
		if(isset(self::$_models[$className]))
			return self::$_models[$className];
		else
		{
			$model=self::$_models[$className]=new $className(null);
			return $model;
		}
	}
	/**
	 * get type contact
	 */
	public function getTypeContact($offset=NULL){
		$arr = array(self::CONTACT_FACEBOOK => self::CONTACT_FACEBOOK, self::CONTACT_GOOGLE => self::CONTACT_GOOGLE, self::CONTACT_YAHOO => self::CONTACT_YAHOO);
		if(!empty($offset)){
			return $arr[$offset];
		}	
		return $arr;
	}
	/**
	 * get status
	 */
	public function getStatus($offset=NULL){
		$arr = array(1 => 'Pending', 2=>'Approve');
		if(!empty($offset)){
			return $arr[$offset];
		}	
		return $arr;
	}
	/**
	 * get status
	 */
	public function getExecuteStatus($offset=NULL){
		$arr = array(0 => 'Not', 1=>'Done');
		if(isset($offset)){
			return $arr[$offset];
		}	
		return $arr;
	}
	
	/**
	 * Fillup Unique Count: data provider, chart 
	 * @param $form
	 */
	public function getPaymentReturn($user_id, $fromdate, $todate){
		if(!empty($user_id) && !empty($fromdate) && !empty($todate)){ 
			$url = 'https://pay.like.vn/History/UsedCashHistory.aspx?userid='.$user_id.'&fromdate='.$fromdate.'&todate='.$todate.'&gamecode=DP';
			$data = Yii::app()->CURL->run($url, FALSE, array());
			parse_str($data);
			if(!empty($errmsg) && $errmsg=='OK'){
				//retval=0&errmsg=OK&realcash=10800&bonuscash=1100
				return json_encode(array('realcash'=>$realcash, 'bonuscash'=>$bonuscash));
			}
		}
		return false;
	}

	public function getGiftcode($user_id){
		if(!empty($user_id)){
			$criteria=new CDbCriteria();
			$criteria->with = array(
				'historyInvited' => array(
					'alias' => 'h',
					'condition' => 'h.user_id = \''.$user_id.'\'',
				)
			);	
			$InviteBonus = InviteBonus::model()->findAll($criteria);
			if(!empty($InviteBonus)){
				return $InviteBonus;
			}
		}
		return false;
	}
	
}

class InviteForm extends CFormModel
{
	public $email;
	public $message;
	public $type;

	public function rules()
	{
		return array(
			array('email, message, type', 'required'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email'=>'Email / ID',
			'message'=>'Nội dung',
			'type'=>'Loại',
		);
	}
	
}
