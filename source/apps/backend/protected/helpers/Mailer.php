<?php
class Mailer {
	static public function send($to, $subject = null, $body = null, $header = null){
	    if(Yii::app()->params->phpmailer['send']){
		    Yii::app()->validateEmail->debug = false;
	    	$results = Yii::app()->validateEmail->validate(array($to), Yii::app()->params['noreplyEmail']);
	    	if (!empty($results[$to])) {
		    	/*
		    	 $arrMail = array(
		    			array('username' => 'noreply1@plun.asia', 'password' => 'noreply123456',),
		    			array('username' => 'noreply2@plun.asia', 'password' => 'noreply123456',),
		    			array('username' => 'noreply3@plun.asia', 'password' => 'noreply123456',),
		    			array('username' => 'noreply4@plun.asia', 'password' => 'noreply123456',),
		    			array('username' => 'noreply5@plun.asia', 'password' => 'noreply123456',),
		    	);
		    	$randval = mt_rand(0, 4);
		    	Yii::app()->mail->transportOptions = array_merge(Yii::app()->mail->transportOptions, $arrMail[$randval]);
		    	 */
	            try {
	        	    Yii::import('backend.extensions.yii-mail.YiiMailMessage');
	        		$message = new YiiMailMessage();
	                $message->setBody($body, 'text/html');
	                $message->setSubject($subject);
	                $message->setTo(array($to=>'You'));
	                $message->setFrom(array(Yii::app()->params['noreplyEmail']=>'PLUN Asia'));
	                return Yii::app()->mail->send($message);
	            } catch (Exception $e) {
	                return false;
	            }
	    	} else {
	    		return false;
	    	}
	    	
	    }
        	
	}
	
	static public function sendByTemplate($to, $template_name, $template_vars = array()){
	    $template = SimpleMailerTemplate::model()->findByAttributes(array(
	            'name' => $template_name,
	    ));
	    if (is_array($template_vars) && !empty($template_vars))
	        $body = strtr($template->body, $template_vars);
	    else
	        $body = $template->body;
	    if(!empty($template->id)){
	        return Mailer::send($to, $template->subject, $body);
	    }
	    return false;
	}
}
