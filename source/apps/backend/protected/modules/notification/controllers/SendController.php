<?php
class SendController extends Controller {
	public function actionSendToAll() {
		$default = 'en';
		$languages = $this->getLanguages();
		
		$errorMessage = '';
		
		if(Yii::app()->request->isPostRequest) {
			if(!isset($_POST[$default]) || $_POST[$default] == '')
				$errorMessage = 'Thông báo bằng tiếng Anh bắt buộc phải có';
			else {
				$notification = array('params'=>'', 'message'=>'');
				foreach ($languages as $language) {
					$notification['message'][$language] = $_POST[$language];
				}
				
				$notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_SYSTEM_ALERT));
				
				$data = array(
						'userid'            => 1,
						'ownerid'           => 0,
						'owner_type'        => 'system',
						'notification_type' => $notifiType->id,
						'notification_data' => addslashes(json_encode($notification)),
						'timestamp'         => time(),
						'last_read'         => 0,
				);
				XNotifications::model()->saveNotifications($data);
				
				$errorMessage = '<span style="color: blue; font-size: 18px;">Notification is Sent !!!</span>';
			}
		}
		
		$this->render('sendToAll', array('errorMessage'=>$errorMessage, 'languages'=>$languages));
	}
	public function getLanguages() {
		$messagePath = Yii::getPathOfAlias('frontend').DIRECTORY_SEPARATOR.'messages';
		
		$handle = opendir($messagePath);
		$languages = array();
		$i = 0;
		
		if ($handle) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					//self::$_items[$type][$model->code]=$model->name;
					$languages[$i] = $entry;
					$i++;
				}
			}
			closedir($handle);
		}
		
		if (!empty($this->excludedirs)){
			$languages=$this->excludes('dirs', $languages);
		}
		return $languages;
	}
}