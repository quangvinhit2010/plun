<?php
class CouponApi {
	
	const FLAG_KEY = 'ed7f20526782049fc431';
	const TIMEOUT 	= 300;
	
	public function __construct() {		
		$this->request = Yii::app ()->getRequest ();
	}
	

	public function getGiftCode($data){
		$result = array();
		if(time() - $_GET['time'] > self::TIMEOUT) {
			$result['errors']['flag'] = 'timeout';
		}elseif($data['flag'] != self::generateFlag($data)) {
			$result['errors']['flag'] = 'flag_invalid';			
		} else {
			$giftcode = Giftcode::model()->with('event')->findByAttributes(array('event_id' => $data['event_id'], 'user_id' => $data['user_id']));
			
			if($giftcode){
				$result['success'] = $giftcode->attributes;
			} else {
				//Update gift code
				$giftcode = Giftcode::model()->find('event_id = '.$data['event_id'].' AND user_id = 0');
				if($giftcode){
					$giftcode->status = 1;
					$giftcode->user_id = $data['user_id'];
					$giftcode->update();
					$result['success'] = $giftcode->attributes;
				} else{
					
					$result['errors']['no_giftcode_found'] = 'no_giftcode_found';
				}
				
					
			}
		}
		return $result;
	}
	
	
	private function generateFlag($params){
		if(count($params) > 1){
			$return = '';
			unset($params['flag']);
			foreach ($params as $value){
				$return .= $value;				
			}
			return md5($return.self::FLAG_KEY);
		} else {
			return md5($params.self::FLAG_KEY);
		}
	}
	
	
	
}