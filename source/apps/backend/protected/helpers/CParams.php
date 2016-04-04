<?php
/**
 * @author VSoft
 */
class CParams {
    public $params;	
    private static $_cls=array();
    /**
     * Load class
     * @param system $className
     * @return multitype:|unknown
     */
    public static function load($className=__CLASS__){
        if(isset(self::$_cls[$className]))
            return self::$_cls[$className];
        else
        {
            $model=self::$_cls[$className]=new $className(null);
            return $model;
        }
    }
    /**
     * Construct
     */
	public function __construct(){
		$this->params = CArray::to_object(Yii::app()->params->toArray());
	}		
	/**
	 * Get path avatar
	 * @param unknown $filename
	 * @return string|boolean
	 */
	public function pathHotbox($filename){
	    $thumb300x0 = $this->params->uploads->hotbox->thumb300x0;
	    if(!empty($filename))
	        return Yii::getPathOfAlias('webroot').DS.$thumb300x0->p.DS.$filename;
	    return false;
	}
	/**
	 * Get URL avatar
	 * @param unknown $filename
	 * @return boolean
	 */
	public function urlHotbox($filename){
	    $thumb300x0 = $this->params->uploads->hotbox->thumb300x0;
	    if(!empty($filename) && file_exists(Yii::getPathOfAlias('webroot').DS.$thumb300x0->p.DS.$filename)){
    	    return Yii::app()->createUrl($thumb300x0->p.DS.$filename);
	    }
	    return false;
	}
} 