<?php
class EventController extends MemberController
{
	public function actionWhitePartyManila()
	{
		$model = new WhitePartyManila();
		if(isset($_POST['WhitePartyManila'])) {
			$model->attributes = $_POST['WhitePartyManila'];
			
			if($model->validate()) {
				
				$events = new Events();
				$whitePartyEvent = $events->find('title=:title', array(':title'=>'White Party Manila'));
				
				$gifCodeModel = new Giftcode();
				
				$gifCode = $gifCodeModel->find('event_id=:event_id AND user_id=:user_id', array(':event_id'=>$whitePartyEvent->id, ':user_id'=>$this->usercurrent->id));
				
				if(!$gifCode) {
					$gifCode = $gifCodeModel->find('event_id=:event_id AND status=0', array(':event_id'=>$whitePartyEvent->id));
					if($gifCode) {
						$gifCode->user_id = $this->usercurrent->id;
						$gifCode->status = 1;
						$gifCode->update();
						
						$model->ip = $_SERVER['REMOTE_ADDR'];
						$model->createtime = time();
						$model->user_id = $this->usercurrent->id;
						$model->user_name = $this->usercurrent->username;
						$model->gift_code = $gifCode->code;
						$model->save(false);
						$response = array('errors'=>'', 'code'=>$gifCode->code);
						
						$msg = new MessageForm();
						$msg->from = 'plunasia';
						$msg->to = $this->usercurrent->username;
						$msg->subject = 'Your White Party Manila Code';
						$msg->body = "Here's confirmation code for your White Ticket to the White Party Manila 2014: ".$gifCode->code.". You can claim it at any SM Tickets outlet nationwide with a valid ID.<br/><br/>Email: <a href='mailto:support@plun.asia'>support@plun.asia</a> for any support you need";
							
						$msg->is_system = 1;
						$msg->validate();
						if(!$msg->hasErrors()){
							$msg->send();
						}
						
						echo json_encode($response);
						exit;
					}
				}
			} else {
				$response = array('errors'=>$model->getErrors());
				echo json_encode($response);
				exit;
			}
		}
	    $this->renderPartial('page/white-party-manila', array('model'=>$model));
	}
	
	public function actionVietPride()
	{
		$model=new LoginForm();
		$login_success	=	false;
		if(isset($_POST['LoginForm']) && Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['LoginForm'];
			$model->validate();
			if(!$model->hasErrors() && $model->login()){
				//check gift
				
				$model_gift	=	CmsLoginGifts::model();
				if($model_gift->checkGift(Yii::app()->user->id)){
	
					$model_gift->user_id	=	Yii::app()->user->id;
					$model_gift->date_login	=	time();
					$model_gift->ip	=	$_SERVER['REMOTE_ADDR'];
					
					$model_gift->save();
					
					$login_success	=	1;	
				}else{
					$login_success	=	0;
				}
			}
		}		
	    $this->render('page/viet-pride', array(
	    		'login_success'	=>	$login_success,
	    		'model'=>$model
	    ));
	}
	
	public function actionFeedback()
	{
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest && !Yii::app()->user->isGuest){
			$user = Yii::app()->user->data();
			$model = V2Feedback::model()->findByAttributes(array('user_id'=>$user->id));
			if(!empty($model)){
				$model->updated = time();
			}else{
				$model = new V2Feedback();
				$model->user_id = $user->id;				
				$model->created = time();				
			}
			$model->username = $user->username;
			$model->level = $_POST['level'];
			$model->message = $_POST['message'];
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				return 1;
			}
		}
	}
	
	public function actionBirthday()
	{
		$sql = "SELECT uu.username, uu.id 
			      	FROM `usr_profile_settings` us
					INNER JOIN `usr_user` uu ON uu.id = us.user_id 					
			      	WHERE  DATE_FORMAT(FROM_UNIXTIME(us.birthday),'%m-%d') = DATE_FORMAT(NOW(),'%m-%d') 
					LIMIT 100";
		$command = Yii::app()->db->createCommand($sql);
// 		$command->bindParam(":limit", 100, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		Yii::app()->end();
		
	}
}