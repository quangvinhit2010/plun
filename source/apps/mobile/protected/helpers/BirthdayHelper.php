<?php
/**
 * @author VSoft
 */
class BirthdayHelper {
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
	 * @return multitype:number
	 */
	public function getDates($index = null)
	{
	    $return  = array();
	    for ($count = 1; $count <= 31; $count++)
	    {
	        $return[$count] = $count;
	    }
	    if(!empty($index)){
	        return $return[$index];
	    }
	    return $return;
	}
	/**
	 * @return multitype:number
	 */
	public function getMonth($index = null)
	{
	    $return = array(
            '1' =>  Lang::t('time', 'JANUARY'),
            '2' =>  Lang::t('time', 'FEBRUARY'),
            '3' =>  Lang::t('time', 'MARCH'),
            '4' =>  Lang::t('time', 'APRIL'),
            '5' =>  Lang::t('time', 'MAY'),
            '6' =>  Lang::t('time', 'JUNE'),
            '7' =>  Lang::t('time', 'JULY'),
            '8' =>  Lang::t('time', 'AUGUST'),
            '9' =>  Lang::t('time', 'SEPTEMBER'),
            '10' => Lang::t('time', 'OCTOBER'),
            '11' => Lang::t('time', 'NOVEMBER'),
            '12' => Lang::t('time', 'DECEMBER')
        );
	    if(!empty($index)){
	        return $return[$index];
	    }
	    return $return;
	}
	/**
	 * @return multitype:string
	 */
	public function getYears($index = null)
	{
	    $current_year = date('Y') - 17;
	    $return  = array();
	    for ($count = $current_year; $count >= 1930; $count--)
	    {
	        $return[$count] = $count;
	    }
	    if(!empty($index)){
	        return $return[$index];
	    }
	    return $return;
	}
} 