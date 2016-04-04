<?php
/**
 * Begin Request Behavior 
 * @author quangvinh
 *
 */
class BeginRequestBehavior extends CBehavior{
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest',array($this,'handleLoadLanguage'));
//         $owner->attachEventHandler('onBeginRequest',array($this,'handleDetectDevice'));
    }
    
    public function handleLoadLanguage($event){    
        $app = Yii::app();
        $lang = $app->language;
//         Util::clearCookie('tmp_lang');
//         $_clang = Yii::app()->session['_lang'];
        $_clang = Util::getCookie('_lang');
        if(empty($_clang)){
            $this->getLangFromIp();
            $this->getLangFromBrowser();
        }
//         $ln = explode(";",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
//         if(!empty($ln) && !empty($ln[0])){
//             $lnn = explode(",",$ln[0]);
//             if(!empty($lnn) && !empty($lnn[1])){
//                 $lang = $lnn[1];
//             } 
//         }
        
        if (!empty($_GET['_lang']))
        {
            $app->language = $_GET['_lang'];
        }
        else if (!empty($_clang))
        {
            $app->language = $_clang;
        }else{
            $app->language = $lang;
        }
    }
    
    private function getLangFromBrowser(){
        $language=Yii::app()->request->getPreferredLanguage();
        if ($language=='en_us'){
            VLang::model()->setCookieLanguage(VLang::LANG_EN);
        }elseif ($language=='vi_vn'){
            VLang::model()->setCookieLanguage(VLang::LANG_VI);
            
        }
    }
    
    private function getLangFromIp(){
        $current_ip = Yii::app()->request->getUserHostAddress();
        $current_country_code = Yii::app()->geoip->lookupCountryCode($current_ip);
        if(!empty($current_country_code)){
            if($current_country_code == 'VN'){
                VLang::model()->setCookieLanguage(VLang::LANG_VI);
            }else{
                VLang::model()->setCookieLanguage(VLang::LANG_EN);
            }
        }
    }
    
    public function handleDetectDevice(){
    	$app = Yii::app();
    	$detect = Yii::app()->mobileDetect;
    	$request = trim($app->urlManager->parseUrl($app->request), '/');
    	$allowed = array('site/detectDevice');
    	if($detect->isMobile() && !$detect->isTablet() && !in_array($request, $allowed)){
    		$app->request->redirect( $app->createUrl('//site/detectDevice'));
    	}
    }
   
}