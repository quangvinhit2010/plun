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
        $app->language = $lang;
    }
}