<?php
/**
 * @author VSoft
 */
class VCandy {
	private static $_models = array ();
	/**
	 * Model
	 * 
	 * @param system $className        	
	 * @return multitype: unknown
	 */
	public static function model($className = __CLASS__) {
		if (isset ( self::$_models [$className] ))
			return self::$_models [$className];
		else {
			$model = self::$_models [$className] = new $className ( null );
			return $model;
		}
	}
	/**
	 * @param string $index
	 * @return Ambigous <string>|multitype:string
	 */
	public function listCandyCanTranfer($index=null){
		$return = array(
			'100' =>  '100',
			'500' =>  '500',
			'1000' =>  '1000',
			'5000' =>  '5000',
			'10000' =>  '10000',
			'50000' =>  '50000',
		);
		if(!empty($index)){
			return $return[$index];
		}
		return $return;
	}
	
	public function useCandy($user_id, $item_used_id, $type, $candy_amount, $candy_current, $charge_id, $description, $status = 1){
		$candyUse = CandyUse::model()->find();
	}
} 