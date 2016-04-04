<?php
/**
 * @author VSoft
 */
class VPurpleguy {
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
	 * @param string $absolute
	 */
    public function createUrl($url) {
		return CParams::load ()->params->url->plun.$url;
	}
	
} 