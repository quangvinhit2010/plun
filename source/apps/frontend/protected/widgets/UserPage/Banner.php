<?php
class Banner extends CWidget {
	
	
	public $type = null;
    public function run() {
    	$this->type = !empty($this->type) ? $this->type : SysBanner::TYPE_W_160;
    	Yii::import("backend.modules.banner.models.SysBanner");
    	$banners = SysBanner::model()->cache(500)->findAll('status = 1 AND type = :type', array(':type'=>$this->type));
    	$length = count($banners);
    	if($length > 0) {
    		if($length > 1) {
    			$random = rand(0, $length-1);
    			$banner = $banners[$random];
    		} else {
    			$banner = $banners[0];
    		}
    		$this->render('banner', array('banner'=>$banner));
    	}
    }
}