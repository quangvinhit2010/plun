<?php

class Banner extends CWidget {
    public function run() {
    	Yii::import("backend.modules.banner.models.SysBanner");
    	$banners = SysBanner::model()->findAll('status = 1');
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