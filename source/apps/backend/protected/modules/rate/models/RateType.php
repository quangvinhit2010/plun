<?php
/**
 * @author VSoft
 */
class RateType {
	const R_EGGPLANT = 1;
	const R_REAL = 2;
	const R_FAKE = 3;
	const R_QUEENIE = 4;
	const R_BUNNY = 5;
	const R_CHILLI = 6;
	const R_HOT_MEAT = 7;
	const R_HANDSOME = 8;
	const R_KISSABLE_GUY = 9;
	const R_FOR_ONE_NIGHT = 10;
	
	private static $_models=array();	
	/**
	 * @param system $className
	 */
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
	 * @return multitype:number
	 */
	public function getTypes($index = null)
	{
	    $return = array(
            '1' =>  Lang::t('time', 'Eggplant'),
            '2' =>  Lang::t('time', 'Real'),
            '3' =>  Lang::t('time', 'Fake'),
            '4' =>  Lang::t('time', 'Queenie'),
            '5' =>  Lang::t('time', 'Bunny'),
            '6' =>  Lang::t('time', 'Chilli'),
            '7' =>  Lang::t('time', 'Hot Meat'),
            '8' =>  Lang::t('time', 'Handsome'),
            '9' =>  Lang::t('time', 'Kissable Guy'),
            '10' => Lang::t('time', 'For One Night'),
        );
	    if(!empty($index)){
	        return $return[$index];
	    }
	    return $return;
	}
	/**
	 * @return multitype:number
	 */
	public function getEmbeddedTypes($index = null)
	{
		$return = array(
				'1' =>  'eggplant',
				'2' =>  'real',
				'3' =>  'fake',
				'4' =>  'queenie',
				'5' =>  'bunny',
				'6' =>  'chilli',
				'7' =>  'hot_meat',
				'8' =>  'handsome',
				'9' =>  'kissable_guy',
				'10' => 'for_one_night',
		);
		if(!empty($index)){
			return $return[$index];
		}
		return $return;
	}	
} 