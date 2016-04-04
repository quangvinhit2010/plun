<?php
class MigrateController extends Controller {
	public function actionUpdatePassword() {
		if(Yii::app()->request->isPostRequest) {
			$access_token = Yii::app()->request->getPost('access_token');
			$oauth = new PDOOAuth2();
			if($access_token && $oauth->verifyAccessToken($access_token)) {
				$plunUserId = Yii::app()->request->getPost('plunUserId');
				
				$encryptedPassword = Yii::app()->request->getPost('encryptedPassword');
				$encryptPassSecret = 'sUxjwif4z6sKUAq7X4H6';
				$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
				$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
				$password = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryptPassSecret, $encryptedPassword, MCRYPT_MODE_ECB, $iv);
				
				$salt = YumEncrypt::generateSalt();
				$password = YumEncrypt::encrypt($password, $salt);
				
				$command = Yii::app()->db->createCommand("UPDATE usr_user SET password = :password, salt = :salt WHERE id = :id");
				$command->bindParam(":password", $password, PDO::PARAM_STR);
				$command->bindParam(":salt", $salt, PDO::PARAM_STR);
				$command->bindParam(":id", $plunUserId, PDO::PARAM_INT);
				if($command->execute())
					echo '1';
			} else
				echo 'invalid access token';
		} else
			echo 'only accept post request';
		exit();
	}
	public function actionUpdateUsername() {
		
		if(Yii::app()->request->isPostRequest) {
			
			$access_token = Yii::app()->request->getPost('access_token');
			$oauth = new PDOOAuth2();
			
			if($access_token && $oauth->verifyAccessToken($access_token)) {
				$chatDomain = Yii::app()->params['XMPP']['server'];
				$oldUsername = Yii::app()->request->getPost('oldUsername');
				$newUsername = Yii::app()->request->getPost('newUsername');
				$userId = Yii::app()->request->getPost('userId');
				
				$encryptedPassword = Yii::app()->request->getPost('encryptedPassword');
				$encryptPassSecret = 'sUxjwif4z6sKUAq7X4H6';
				$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
				$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
				$password = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryptPassSecret, $encryptedPassword, MCRYPT_MODE_ECB, $iv);
				
				$salt = YumEncrypt::generateSalt();
				$password = YumEncrypt::encrypt($password, $salt);
				
				$command = Yii::app()->db->createCommand("UPDATE usr_user SET password = :password, salt = :salt WHERE id = :id");
				$command->bindParam(":password", $password, PDO::PARAM_STR);
				$command->bindParam(":salt", $salt, PDO::PARAM_STR);
				$command->bindParam(":id", $userId, PDO::PARAM_INT);
				$command->execute();
					
				$notifyDataOld = '\"'.$oldUsername.'\"';
				$notifyDataNew = '\"'.$newUsername.'\"';
				$command = Yii::app()->db->createCommand("UPDATE sys_notifications SET notification_data = REPLACE(notification_data, ?, ?) WHERE userid = $userId OR ownerid = $userId");
				$command->bindParam(1, $notifyDataOld, PDO::PARAM_STR);
				$command->bindParam(2, $notifyDataNew, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db->createCommand("UPDATE sys_user_top SET username = ? WHERE user_id = $userId");
				$command->bindParam(1, $newUsername);
				$command->execute();
					
				$command = Yii::app()->db->createCommand("UPDATE usr_user SET username = ?, alias_name = ? WHERE id = $userId");
				$command->bindParam(1, $newUsername);
				$command->bindParam(2, $newUsername);
				$command->execute();
					
				$paramOld = '"'.$oldUsername.'"';
				$paramNew = '"'.$newUsername.'"';
				$command = Yii::app()->db_activity->createCommand("UPDATE activities SET params = REPLACE(params, ?, ?), user_name = ? WHERE user_id = $userId");
				$command->bindParam(1, $paramOld, PDO::PARAM_STR);
				$command->bindParam(2, $paramNew, PDO::PARAM_STR);
				$command->bindParam(3, $newUsername, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db_event->createCommand("UPDATE purpleguy_profile SET username = ? WHERE user_id = $userId");
				$command->bindParam(1, $newUsername, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db_event->createCommand("UPDATE v2_feedback SET username = ? WHERE user_id = $userId");
				$command->bindParam(1, $newUsername, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db_event->createCommand("UPDATE white_party_manila SET user_name = ? WHERE user_name = ?");
				$command->bindParam(1, $newUsername, PDO::PARAM_STR);
				$command->bindParam(2, $oldUsername, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db_tigase->createCommand("UPDATE tig_users SET user_id = ?, sha1_user_id = ?, acc_create_time = acc_create_time WHERE user_id = ?");
				$oldUserId = $oldUsername.'@'.$chatDomain;
				$newUserId = $newUsername.'@'.$chatDomain;
				$sha1NewUserId = sha1($newUserId);
				$command->bindParam(1, $newUserId, PDO::PARAM_STR);
				$command->bindParam(2, $sha1NewUserId, PDO::PARAM_STR);
				$command->bindParam(3, $oldUserId, PDO::PARAM_STR);
				$command->execute();
					
				$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM tig_ma_jids WHERE jid = ?");
				$command->bindParam(1, $oldUserId, PDO::PARAM_STR);
				$row = $command->queryRow();
					
				if($row) {
					$jidId = $row['jid_id'];
						
					$command = Yii::app()->db_tigase->createCommand("UPDATE tig_ma_jids SET jid = ? WHERE jid_id = $jidId");
					$command->bindParam(1, $newUserId, PDO::PARAM_STR);
					$command->execute();
						
					$msgOld = '"'.$oldUserId;
					$msgNew = '"'.$newUserId;
					$command = Yii::app()->db_tigase->createCommand("UPDATE tig_ma_msgs SET msg = REPLACE(msg, ?, ?), ts = ts WHERE owner_id = $jidId OR buddy_id = $jidId");
					$command->bindParam(1, $msgOld, PDO::PARAM_STR);
					$command->bindParam(2, $msgNew, PDO::PARAM_STR);
					$command->execute();
				}
					
				$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM user_jid WHERE jid = ?");
				$command->bindParam(1, $oldUserId, PDO::PARAM_STR);
				$row = $command->queryRow();
					
				if($row) {
					$jidId = $row['jid_id'];
						
					$command = Yii::app()->db_tigase->createCommand("UPDATE user_jid SET jid = ?, jid_sha = ? WHERE jid_id = $jidId");
					$command->bindParam(1, $newUserId, PDO::PARAM_STR);
					$command->bindParam(2, $sha1NewUserId, PDO::PARAM_STR);
					$command->execute();
						
					$msgOld = '"'.$oldUserId;
					$msgNew = '"'.$newUserId;
					$command = Yii::app()->db_tigase->createCommand("UPDATE msg_history SET message = REPLACE(message, ?, ?), ts = ts WHERE sender_uid = $jidId OR receiver_uid = $jidId");
					$command->bindParam(1, $msgOld, PDO::PARAM_STR);
					$command->bindParam(2, $msgNew, PDO::PARAM_STR);
					$command->execute();
				}
					
				// update eslantic here...
				$Elasticsearch = new Elasticsearch();
				$Elasticsearch->updateSearchIndexUser($userId);
					
				echo '1';
			} else
				echo 'invalid access token';
		} else
			echo 'only accept post request';
		
		exit();
	}
	public function actionCreate() {
		if(Yii::app()->request->isPostRequest) {
			$access_token = Yii::app()->request->getPost('access_token');
			$oauth = new PDOOAuth2();
			if($access_token && $oauth->verifyAccessToken($access_token)) {
				$username = Yii::app()->request->getPost('username');
				$encryptedPassword = Yii::app()->request->getPost('encryptedPassword');
				$email = Yii::app()->request->getPost('email');
				
				$encryptPassSecret = 'sUxjwif4z6sKUAq7X4H6';
				$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
				$password = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryptPassSecret, $encryptedPassword, MCRYPT_MODE_ECB, $iv);
				
				$registerForm = new RegisterForm();
				$registerForm->username = $username;
				$registerForm->email = $email;
				$registerForm->password = $password;
				$registerForm->lastname = $username;
				$registerForm->firstname = $username;
				$registerForm->save();
				
				echo '1';
			} else {
				echo 'invalid accesstoken';
			}
		} else {
			echo 'only accept post request';
		}
		exit();
	}
	
	public function actionUpdatePass() {
		if(Yii::app()->request->isPostRequest) {
			$access_token = Yii::app()->request->getPost('access_token');
			$username = Yii::app()->request->getPost('username');
			$oauth = new PDOOAuth2();
			$return = array();
			if($access_token && $oauth->verifyAccessToken($access_token)) {
				$user = Member::model()->findByAttributes(array('username'=>$username));
				if(empty($user)){
					$return[] = array('error'=>true, 'msg'=>'User not exist !');					
				}
				$encryptedPassword = Yii::app()->request->getPost('encryptedPassword');
				$password = Util::mcrypt_decrypt($encryptedPassword);
				if(!empty($password)){					
					$salt = YumEncrypt::generateSalt();				
					$user->setPassword($password, $salt);
					$user->validate();
					if(!$user->hasErrors()){
						$user->save();
						$return[] = array('error'=>false, 'msg'=>'Update password success !');
					}else{
						$return[] = array('error'=>true, 'msg'=>'Update password fail !');
					}				
				}
				$encryptedEmail = Yii::app()->request->getPost('encryptedEmail');
				$email = Util::mcrypt_decrypt($encryptedEmail);
				if(!empty($email)){					
					$user->profile->email = $email;
					$user->profile->validate();
					if(!$user->profile->hasErrors()){
						$user->profile->save();
						$return[] = array('error'=>false, 'msg'=>'Update email success !');
					}else{
						if(!empty($user->profile->errors['email'])){
							$return[] = array('error'=>true, 'msg'=>$user->profile->errors['email'][0]);
						}else{
							$return[] = array('error'=>true, 'msg'=>'Update email fail !');
						}
					}				
				}
			}else{
				$return[] = array('error'=>true, 'msg'=>'Token has expired !');
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
	}
}