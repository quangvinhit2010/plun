<?php
/**
 * @author VSoft
 */
class VHelper {
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
	 *
	 * @param
	 *        	$conditions
	 * @return boolean
	 */
	public static function activeMenu($module = '', $controller = '', $action = '') {
		$mFlag = $cFlag = $aFlag = false;
		if (! empty ( $module )) {
			$mFlag = (! empty ( Yii::app ()->controller->module->id ) && Yii::app ()->controller->module->id == $module) ? true : false;
		} else {
			$mFlag = true;
		}
		if (! empty ( $controller )) {
			$cFlag = (! empty ( Yii::app ()->controller->id ) && Yii::app ()->controller->id == $controller) ? true : false;
		} else {
			$cFlag = true;
		}
		if (! empty ( $action )) {
			$aFlag = (! empty ( Yii::app ()->controller->action->id ) && Yii::app ()->controller->action->id == $action) ? true : false;
		} else {
			$aFlag = true;
		}
		return ($mFlag == true && $cFlag == true && $aFlag == true) ? true : false;
	}
	/**
	 *
	 * @param
	 *        	$conditions
	 * @return boolean
	 */
	public static function checkDir($dir) {
		if (! is_dir ( $dir )) {
			Yii::import ( 'backend.extensions.yii-cfile.CFileHelpers' );
			$cf = CFileHelpers::get ( $dir , true);
			$cf->createDir ( 0777 );
		}
		return true;
	}
	/**
	 * Get unique target path
	 * 
	 * @param unknown $uploadDirectory        	
	 * @param unknown $filename        	
	 * @return Ambigous <string, boolean>
	 */
	public function getUniqueTargetPath($uploadDirectory, $filename) {
		// Allow only one process at the time to get a unique file name, otherwise
		// if multiple people would upload a file with the same name at the same time
		// only the latest would be saved.
		if (function_exists ( 'sem_acquire' )) {
			$lock = sem_get ( ftok ( __FILE__, 'u' ) );
			sem_acquire ( $lock );
		}
		
		$pathinfo = pathinfo ( $filename );
		$base = $pathinfo ['filename'];
		$ext = isset ( $pathinfo ['extension'] ) ? $pathinfo ['extension'] : '';
		$ext = $ext == '' ? $ext : '.' . $ext;
		
		$unique = $base;
		$suffix = 0;
		
		// Get unique file name for the file, by appending random suffix.
		
		while ( file_exists ( $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext ) ) {
			$suffix += rand ( 1, 999 );
			$unique = $base . '-' . $suffix;
		}
		
		$result = $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;
		
		// Create an empty target file
		if (! touch ( $result )) {
			// Failed
			$result = false;
		}
		
		if (function_exists ( 'sem_acquire' )) {
			sem_release ( $lock );
		}
		
		return $result;
	}
	/**
	 * get path
	 * 
	 * @param
	 *        	$path
	 * @param
	 *        	$params
	 * @return string boolean
	 */
	public function path($path, $params = array(), $root = true) {
		$proot = ($root) ? Yii::getPathOfAlias ( 'pathroot' ) . DS : '';
		if ($params === array ())
			$folder = $proot . $path;
		if (! empty ( $path ) && ! empty ( $params ) && is_array ( $params ))
			$folder = $proot . strtr ( $path, $params );
		if (VHelper::checkDir ( $folder )) {
			return $folder;
		}
		return false;
	}
	
	public function parseAttributesHtml($htmlOptions) {
	    $html = '';
	    if(!empty($htmlOptions)){
	        foreach($htmlOptions as $name=>$value)
	        {
	            $html .= ' ' . $name . '="' . $value . '"';	            
	        }
	    }
	    return $html;
	}
	
	public function checkAccess($itemname, $userid){
	    return Yii::app()->authManager->getAuthAssignment($itemname, $userid);
	}
} 