<?php
/**
 * 
 * Enter description here ...
 * @author vinh.nguyen
 *
 */
class Lang {
	public static function t($category, $message, $params=array(),$source=null,$language=null){
		$app = Yii::app();
		if(empty($language)){
    		$language = $app->language;
		}
		$ds = DIRECTORY_SEPARATOR;
		$pathMsgApp = $app->basePath.$ds.'messages';
		$tr = new CPhpMessageSource();
		
		if(!empty($category)){			
    		$tr->basePath = $pathMsgApp;			
		}elseif (file_exists($pathMsgApp.$ds.$language.$ds.'general'.'.php')){
			$tr->basePath = $pathMsgApp;
			$category = 'general';
		}
		$message = $tr->translate($category, $message, $language);
		if(is_array($params)){
		    $message = strtr($message,$params);
		}
		return $message;
	}
	
	public static function translationJs(){
	    $app = Yii::app();
        $language = $app->language;
	    $ds = DIRECTORY_SEPARATOR;
	    $pathMsgApp = $app->basePath.$ds.'messages';
	    $sourcelanguage = include($pathMsgApp.$ds.$language.$ds.'javascript.php');
	    $content = json_encode(array($language=>$sourcelanguage));
	    $script = '';
	    $script .= "$.tr.dictionary($content);";
	    $script .= "$.tr.language('$language');";
	    $script .= "var tr = $.tr.translator();";
	    $cs = Yii::app()->getClientScript();
	    $cs->registerScript('languageTranslation', $script, CClientScript::POS_BEGIN);
	    
	}
	        
}