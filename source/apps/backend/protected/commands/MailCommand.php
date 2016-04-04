<?php
Yii::import('backend.extensions.ECSVExport');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MailCommand extends CConsoleCommand {
	/**
	 * yiic mail activation --limit=500
	 */
    public function actionActivation($limit, $lang, $resend = false){
    	$dataNew = $dataExist = array();
    	$arrIdExist = array();
    	VHelper::checkDir(Yii::getPathOfAlias ( 'pathroot' ).'/uploads/csv/');
    	$filename = Yii::getPathOfAlias ( 'pathroot' ).'/uploads/csv/sendMailActivation.csv';
    	/** check exist and not send **/
    	if($resend == false && file_exists($filename)){
    		$handle = fopen($filename, 'r');
    		if ($handle) {
    			while( ($line = fgetcsv($handle, 1000, ",")) != FALSE) {
    				if(!empty($line[0]) && is_numeric($line[0])){
	    				$dataExist[] = array('userid'=>$line[0], 'email'=>$line[1], 'send'=>$line[2]);
	    				$arrIdExist[] = $line[0];
    				}
    			}
    		}
    	}    	
    	/****/		
    	$params = CParams::load ();
    	$cri = new CDbCriteria();
    	$cri->addCondition("status = :status");
    	$cri->params = array(':status'=>0);
    	$cri->order = "id ASC";
    	$cri->limit = $limit;
    	$users = Member::model()->findAll($cri);
    	if(!empty($users)){    		
    		ob_start();
    		echo "\n";
    		foreach ($users as $user){
    			Yii::app()->setBasePath(Yii::getPathOfAlias ( 'frontend' ));
    			$path = Yii::getPathOfAlias('themes.plun1.views.layouts.email.'.$lang).'/register-welcome.php';
    			if (!empty($user->activationKey)) {
    				echo $user->id.'_'.$user->profile->email;
    				echo "\n";
	    			$params = array();
    				$params['key'] = $user->activationKey;
    				$params['tk'] = Util::encryptRandCode($user->alias_name);
    				$activation_url = Yii::app()->createAbsoluteUrl('//register/activation', $params);
	    			$body = $this->renderFile($path, array('user'=>$user, 'activation_url'=>$activation_url), true);
	    			$subject = strtr(Lang::t('yii','Please activate your account for {username}', array(), null, $lang), array(
	    					'{username}' => $user->username));	    			
	    			$sent = Mailer::send ( $user->profile->email,  $subject, $body);
					if(!in_array($user->id, $arrIdExist) && $sent){
						$dataNew[] = array('userid'=>$user->id, 'email'=>$user->profile->email, 'send' => 1);
					}
					ob_flush();
					flush();					
    			}    			
    		}    		
    		$data = array_merge($dataNew, $dataExist);
    		$csv = new ECSVExport($data);
    		$csv->toCSV($filename);
    		exit;
    	}    	  	
    }
}
?>
