<?php
/**
 * @author VSoft
 */
class VLang {
    const LANG_VI = 'vi';
    const LANG_EN = 'en';
	private static $_models = array ();
	/**
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

	public function getLangs(){
	    $arrLang = array(
	            VLang::LANG_EN=>array('class'=>'flag-eng', 'title'=>'English'),
	            VLang::LANG_VI=>array('class'=>'flag-vn', 'title'=>'Ti&#7871;ng Vi&#7879;t'),
	    );
	    return $arrLang;
	}
	
	public function setLangDefault(){
	    $language = strtolower(Yii::app()->language);
	    $usr_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
	    if(!empty($usr_setting) && !empty($usr_setting->user_id)){
	        if(!empty($usr_setting->default_language)){
	            switch ($usr_setting->default_language){
	                case ProfileSettingsConst::LANGUAGES_DEFAULT_VIETNAMESE:
	                    $language = VLang::LANG_VI;
	                    break;
	                case ProfileSettingsConst::LANGUAGES_DEFAULT_ENGLISH:
	                    $language = VLang::LANG_EN;
	                    break;
	            }
	        }
	    }  
// 	    Yii::app()->session['_lang'] = $language;
	    self::model()->setCookieLanguage($language);
	}
	
	public function setCookieLanguage($language){
	    Util::writeCookie(array('name' => '_lang', 'value' => $language, 'valueDefault' => null));
	}
	
	public function getIdLangDefaultByCodeLang($code){
	    switch ($code){
	        case VLang::LANG_VI:
	            return ProfileSettingsConst::LANGUAGES_DEFAULT_VIETNAMESE;
	            break;
	        case VLang::LANG_EN:
	            return ProfileSettingsConst::LANGUAGES_DEFAULT_ENGLISH;
	            break;
	    }
	}
} 