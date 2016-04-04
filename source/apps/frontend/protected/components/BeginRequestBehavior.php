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
        $owner->attachEventHandler('onBeginRequest',array($this,'handleDetectDevice'));
        $owner->attachEventHandler('onBeginRequest',array($this,'handleReferrer'));
    }
    
    public function handleLoadLanguage($event){    
        $app = Yii::app();
        $lang = $app->language;
//         Util::clearCookie('tmp_lang');
//         $_clang = Yii::app()->session['_lang'];
        $_clang = Util::getCookie('_lang');
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
    
    public function handleDetectDevice(){
        $params = CParams::load ();
    	$app = Yii::app();
    	$detect = Yii::app()->mobileDetect;
    	$request = trim($app->urlManager->parseUrl($app->request), '/');
    	$allowed = array('site/detectDevice');
    	if($detect->isMobile() && !in_array($request, $allowed)){
    		$app->request->redirect( $params->params->mobile->url);
    		$app->request->redirect( $app->createUrl('//site/detectDevice'));
    	}
    }
    
    public function handleReferrer(){
    	$plun = Util::get_domain(Yii::app()->getBaseUrl(true));
    	$referrer = Yii::app()->request->getUrlReferrer();
    	if(isset($referrer)){
    		$domain = Util::get_domain($referrer); 
    		if ($domain != $plun){
    			Util::writeCookie(array('name' => 'referrer', 'value' => ''));
    			unset(Yii::app()->request->cookies['referrer']);
    			$data = array(
    					'name' => 'referrer',
    					'value' => array('domain'=>$domain, 'type_referrer' => ReferrerLog::TYPE_DIRECT, 'redirect_url' => Util::getCurrentUrl(),  'referrer_url'=>$referrer),
    					'valueDefault' => serialize(array()),
    			);	
    			Util::writeCookie($data, 86400);
    		}
    	} else {
    		if(isset($_GET['utm_source']) && ReferrerDefine::checkHost($_GET['utm_source']) && $_GET['utm_source'] != $plun){
    			Util::writeCookie(array('name' => 'referrer', 'value' => ''));
    			unset(Yii::app()->request->cookies['referrer']);
    			$data = array(
    					'name' => 'referrer',
    					'value' => array('domain'=>$_GET['utm_source'], 'type_referrer' => ReferrerLog::TYPE_UTM, 'redirect_url' => Util::getCurrentUrl(),  'referrer_url'=> ''),
    					'valueDefault' => serialize(array()),
    					
    			);
    			Util::writeCookie($data, 86400);
    		}
    	}
    }
   
   
}