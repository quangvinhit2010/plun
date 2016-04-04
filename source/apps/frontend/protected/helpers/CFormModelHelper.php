<?php
/**
 * @author VSoft
 */
class CFormModelHelper {
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
	
	public function parseErrorToString($msgFrm){
	    $content = '';
	    if($msgFrm->getErrors()){
    	    foreach($msgFrm->getErrors() as $errors)
    	    {
    	        foreach($errors as $error)
    	        {
    	            if($error!='')
    	                $content.="$error\n";
    	        }
    	    }
	    }
	    return $content;	            
	}
	
} 