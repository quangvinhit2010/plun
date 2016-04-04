<?php
/**
 * @author VSoft
 */
class UnitHelper {
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
    
	static function getFeetList(){
	    $unit_height_ft = self::model()->getUnitHeightFeet();
	    if(!empty($unit_height_ft)){
	        return array_combine($unit_height_ft, $unit_height_ft);
	    }
	    return array();
	}
	
	private function getUnitHeightFeet(){
	    $unit_height_ft = Yii::app()->config->get('unit_height_ft');
	    if(!empty($unit_height_ft)){
	        $unit_height_ft = explode("\n", $unit_height_ft);
	        $unit_height_ft = array_map('trim',$unit_height_ft);
	        return $unit_height_ft;
	    }
	    return false;
	}
	
	static function getCmList(){
		$unit_height_cm = self::model()->getUnitHeightCm();
		if(!empty($unit_height_cm)){
		    return array_combine($unit_height_cm, $unit_height_cm);
		}
		return array();
	}
	
	private function getUnitHeightCm(){
	    $unit_height_cm = Yii::app()->config->get('unit_height_cm');
		if(!empty($unit_height_cm)){
		    $unit_height_cm = explode("\n", $unit_height_cm);
		    $unit_height_cm = array_map('trim',$unit_height_cm);
		    return $unit_height_cm;
		}
		return false;
	}
	
	static function getKgList(){
		$unit_weight_kg = self::model()->getUnitWeightKg();
		if(!empty($unit_weight_kg)){
		    return array_combine($unit_weight_kg, $unit_weight_kg);
		}
		return array();
	}
	
	private function getUnitWeightKg(){
	    $unit_weight_kg = Yii::app()->config->get('unit_weight_kg');
	    if(!empty($unit_weight_kg)){
	        $unit_weight_kg = explode("\n", $unit_weight_kg);
	        $unit_weight_kg = array_map('trim',$unit_weight_kg);
	        return $unit_weight_kg;
	    }
	    return false;
	}
	
	static function getLbsList(){
		$unit_weight_lbs = self::model()->getUnitWeightLbs();
		if(!empty($unit_weight_lbs)){
		    return array_combine($unit_weight_lbs, $unit_weight_lbs);
		}
		return array();
	}
	
	private function getUnitWeightLbs(){
	    $unit_weight_lbs = Yii::app()->config->get('unit_weight_lbs');
	    if(!empty($unit_weight_lbs)){
	        $unit_weight_lbs = explode("\n", $unit_weight_lbs);
	        $unit_weight_lbs = array_map('trim',$unit_weight_lbs);
	        return $unit_weight_lbs;
	    }
	    return false;
	}
	
	public function convert($type, $value, $measurement){
	    $return = $value;
	    if(!empty($type)){
	        $unitHeightFeet = $this->getUnitHeightFeet();
	        $unitHeightCm = $this->getUnitHeightCm();
	        $unitWeightLbs = $this->getUnitWeightLbs();
	        $unitWeightKg = $this->getUnitWeightKg();
	        
    	    switch ($type){
    	        case 'height':
    	            if($measurement == UsrProfileSettings::VN_UNIT){
    	                $key = array_search($value, $unitHeightFeet);
    	                if(!empty($key) && !empty($unitHeightCm[$key])){
    	                    $return = $unitHeightCm[$key];
    	                }
    	            }elseif($measurement == UsrProfileSettings::EN_UNIT){
    	                $key = array_search($value, $unitHeightCm);
    	                if(!empty($key) && !empty($unitHeightFeet[$key])){
    	                    $return = $unitHeightFeet[$key];
    	                }
    	            }
    	            break;
    	        case 'weight':
    	            if($measurement == UsrProfileSettings::VN_UNIT){
    	                $key = array_search($value, $unitWeightLbs);
    	                if(!empty($key) && !empty($unitWeightKg[$key])){
    	                    $return = $unitWeightKg[$key];
    	                }    	                
    	            }elseif($measurement == UsrProfileSettings::EN_UNIT){
    	                $key = array_search($value, $unitWeightKg);    	                
    	                if(!empty($key) && !empty($unitWeightLbs[$key])){
    	                    $return = $unitWeightLbs[$key];
    	                }
    	            }
    	            break;
    	    }
	    }
	    
        return $return;
	    
	}
} 