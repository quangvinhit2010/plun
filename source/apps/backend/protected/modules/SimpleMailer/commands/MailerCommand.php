<?php
/**
 * mailer class file.
 * For running this from console simply type './yiic mailer' from the protected/ directory
 */

class MailerCommand extends CConsoleCommand
{
	/**
	 * Executes the command.
	 * @param array $args command line parameters for this command.
	 * @return int 0 on success, 1 on error.
	 */
	public function actionSend() {
		try {
			/**
			 * @var $emails SimpleMailerQueue
			 */
			$emails = SimpleMailerQueue::model()->findAllByAttributes(
				array(
					'status' => SimpleMailerQueue::STATUS_NOT_SENT,
				),
				array(
					'limit' => Yii::app()->getModule('SimpleMailer')->sendEmailLimit,
				)
			);

			echo 'Count: ' . count($emails) . "\n";

			if (!$emails) {
				Yii::log(Yii::t('mailer', 'No emails in queue. Exiting.'), 'error', 'application.commands.MailerCommand');
				exit(0);
			}

			foreach ($emails as $email) {
// 			    $sent = Mailer::send ( 'tram@dwm.vn',  $email->subject, $email->body);
			    $sent = Mailer::send ( $email->to,  $email->subject, $email->body);
				if ($sent) {
					$email->status = SimpleMailerQueue::STATUS_SENT;
					$email->times += 1; 
					$email->save();
					echo 'Sent: ' . $email->to . "\n";
				}
			}
			exit(0);
		}
		catch (Exception $e) {
			Yii::log($e->getMessage(), 'error', 'application.commands.MailerCommand');
			exit(1);
		}
	}
	
	public function actionReminder($limit = 500, $times = 1) {
	    Yii::app()->setBasePath(Yii::getPathOfAlias ( 'frontend' ));
	    if(empty($times)){
	        return false;
	    }
	    switch ($times){
	        case 1:	     
	            preg_match("/dbname=([^;]*)/", Yii::app()->db_mail->connectionString, $amatches);
	            $mailDbName = $amatches[1];
	            
        	    $to = strtotime(date('d-m-Y H:i:s', strtotime("-3 day")));
        	    $cri = new CDbCriteria();
        	    $cri->join = "INNER JOIN usr_profile p ON (p.user_id=t.id)";
        	    $cri->addCondition("status = :status AND lastvisit < :to");
        	    $cri->addCondition("(NOT EXISTS(SELECT `to` FROM `$mailDbName`.`sm_queue` q WHERE q.`to` = p.email))");
        	    $cri->params = array(':status'=>0, ':to'=>$to);
        	    $cri->order = "t.id DESC";
        	    $cri->limit = $limit;
        	    $users = Member::model()->findAll($cri);
        	    if(!empty($users)){            
        	        ob_start();
        	        echo "\n";	        
        	        foreach ($users as $user){
        	            if(!empty($user->profile_settings->language->code)){
        	                $lang = $user->profile_settings->language->code;
        	            }else{
        	                $lang = Yii::app()->language;
        	            }
        	            $template_name = 'reminder-verify-email-'.$lang;
        	            $template = SimpleMailerTemplate::model()->findByAttributes(array(
        	                    'name' => $template_name,
        	            ));
        	            $addQueue = false;
        	            if(!empty($user->profile) && !empty($user->profile->email)){
                            $exist = SimpleMailerQueue::model()->findByAttributes(array('to' => $user->profile->email, 'subject'=>$template->subject));
                            $addQueue = !empty($exist) ? false : true;
        	            }
        	            if ($addQueue == true) {
        	                echo $user->id.'_'.$lang.'_'.$user->profile->email;
        	                echo "\n";
        	                $activation_url = Yii::app()->createAbsoluteUrl('//register/activation', array('key'=>$user->activationKey, 'tk'=>Util::encryptRandCode($user->alias_name)));
        	                $template_vars = array(
        	                        '__url__' => Yii::app()->createAbsoluteUrl('/'),
        	                        '__username__' => $user->username,
        	                        '__url_activation__' => $activation_url,
        	                );
        	                SimpleMailer::$type = SimpleMailer::MAIL_REMINDER_VERIFY;
        	                SimpleMailer::enqueue($user->profile->email, $template_name, $template_vars);	                
        	                ob_flush();
        	                flush();
        	            }
        	        }
        	    }
	            break;
            case 2:
                $to = strtotime(date('d-m-Y H:i:s', strtotime("-4 day")));
                $cri = new CDbCriteria();
                $cri->addCondition("status = :status AND subject = :subject AND times = :times AND create_time <= :to");
                $cri->params = array(':status'=>1, ':subject'=> $template->subject, ':times'=>1, ':to'=>$to);
                $cri->order = "id DESC";
                $cri->limit = $limit;
                $mails = SimpleMailerQueue::model()->findAll($cri);
                if(!empty($mails)){
                    ob_start();
                    echo "\n";
                    foreach ($mails as $mail){
                        if(!empty($mail->to)){
                            $mail->status = 0;
                            $mail->save();
                            echo $mail->to;
                            echo "\n";
                            ob_flush();
                            flush();
                        }                        
                    }
                }
                break;
        }
	    
	}

	public function processLogs($event) {
		static $routes;
		$logger = Yii::getLogger();
		$routes = isset($routes) ? $routes : Yii::app()->log->getRoutes();
		foreach ($routes as $route) {
			if ($route->enabled) {
				$route->collectLogs($logger, true);
				$route->logs = array();
			}
		}
	}

	/**
	 * Provides the command description.
	 * This method may be overridden to return the actual command description.
	 * @return string the command description. Defaults to 'Usage: php entry-script.php command-name'.
	 */
	public function getHelp() {
		return 'Usage: yiic mailer - without parameters';
	}
}
