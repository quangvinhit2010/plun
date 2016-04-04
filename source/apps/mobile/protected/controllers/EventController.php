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
}