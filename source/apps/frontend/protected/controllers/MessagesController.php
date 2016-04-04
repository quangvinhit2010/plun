<?php
/**
 * @author vinhnguyen
 * @desc Messages Controller
 */
class MessagesController extends MemberController
{
	public function actionIndex()
	{
	    if(!$this->user->isMe()){
	        throw new CHttpException ( 404, 'The requested page does not exist.' );
	    }	    
	    $this->render('page/index', array(
	    ));
	}
	
	public function actionConversation()
	{
		$msgs = array();
		
		$username = $this->usercurrent->username . '@' . Yii::app()->params['XMPP']['server'];
	
		$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM tig_ma_jids WHERE jid = ?");
		$command->bindParam(1, $username, PDO::PARAM_STR);
		$row = $command->queryRow();
		
		$page = Yii::app()->request->getParam('page', 1);
		$limit = CParams::load()->params->page_limit->message_conversation;
		$messages = array();
		
		if($row) {
			$jid_id = $row['jid_id'];
			
			$offsetExtend = Yii::app()->request->getParam('offset', 0);
			
			$offset = ($page-1) * $limit + $offsetExtend;
			
			$virtualLimit = $limit + 1;
			
			$query = "SELECT * FROM
					(SELECT * FROM tig_ma_msgs WHERE owner_id = $jid_id ORDER BY ts DESC) AS tbl
					LEFT JOIN tig_ma_jids tbl2 ON tbl2.`jid_id` = tbl.buddy_id
					GROUP BY buddy_id ORDER BY ts DESC LIMIT $offset, $virtualLimit";
			$command = Yii::app()->db_tigase->createCommand($query);
			$messages = $command->queryAll();
		}
		$this->renderPartial('partial/conversation', array(
				'page'=>$page,
				'limit'=>$limit,
				'messages'=>$messages,
		));
	}
	

	public function actionUpdateUnread() {
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$username = Yii::app()->request->getPost('username');
			OfflineMessages::model()->updateOffline(Yii::app()->user->id, $username);
		}
	}
	public function actionDeleteMessage() {
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			
			$buddy_username = Yii::app()->request->getPost('buddy_username') . '@' . Yii::app()->params['XMPP']['server'];
			$owner_username = Yii::app()->user->data()->username . '@' . Yii::app()->params['XMPP']['server'];
			
			$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM tig_ma_jids WHERE jid = ?");
			$command->bindParam(1, $buddy_username, PDO::PARAM_STR);
			$row = $command->queryRow();
			$buddy_id = $row['jid_id'];
			
			$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM tig_ma_jids WHERE jid = ?");
			$command->bindParam(1, $owner_username, PDO::PARAM_STR);
			$row = $command->queryRow();
			$owner_id = $row['jid_id'];
			
			$command = Yii::app()->db_tigase->createCommand("DELETE FROM tig_ma_msgs WHERE owner_id = ? AND buddy_id = ?");
			$command->bindParam(1, $owner_id, PDO::PARAM_STR);
			$command->bindParam(2, $buddy_id, PDO::PARAM_STR);
			$command->execute();

			$result = '';
			
			if(Yii::app ()->request->getPost ( 'load_new', false )) {
				$limit = CParams::load()->params->page_limit->message_conversation;
				$virtualLimit = $limit + 1;
				
				$query = "SELECT * FROM
					(SELECT * FROM tig_ma_msgs WHERE owner_id = $owner_id ORDER BY ts DESC) AS tbl
					LEFT JOIN tig_ma_jids tbl2 ON tbl2.`jid_id` = tbl.buddy_id
					GROUP BY buddy_id ORDER BY ts DESC LIMIT 0, $virtualLimit";
				$command = Yii::app()->db_tigase->createCommand($query);
				$messages = $command->queryAll();
			
				$result = $this->renderPartial('partial/conversation', array(
					'page'=>1,
					'limit'=>$limit,
					'messages'=>$messages,
				));
			}
			
			echo $result;
		}
	}
	
	public function actionSend()
	{
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	        $results = array('status'=>false, 'msg'=>'', '_wd'=>330);
	        $data = Yii::app()->request->getPost('MessageForm');
	        if(is_string($data['to'])){
	            $toIds = explode(',', $data['to']);
	            if(is_array($toIds)){
	                foreach ($toIds as $id){	                    
	                    $msgFrm = new MessageForm();
	                    $msgFrm->bindData($data);
	                    $msgFrm->to = trim($id);
	                    $msgFrm->validate();	                    
	                    if(!$msgFrm->hasErrors()){	                        
	                        $_to_user = Member::model()->findByAttributes(array('alias_name'=>$msgFrm->to));
	                        $results = array('status'=>true, 'msg'=>Lang::t('messages', 'Message sent and delivered!'), '_wd'=>330);
	                        $msgFrm->send();
	                        DataNodejs::increaseTotalMessage($_to_user->id);
	                    }else{
// 	                        $results['msg'] = CFormModelHelper::model()->parseErrorToString($msgFrm);
	                        $results = array('status'=>false, 'msg'=>Lang::t('messages', 'Message send failure!'), '_wd'=>330);
	                    }
	                }
	            }
	        }else{
	            $model = new MessageForm();
                $model->bindData($data);
    	        $model->validate();
    	        if(!$model->hasErrors()){    	            
    	            $_to_user = Member::model()->findByAttributes(array('alias_name'=>$model->to));
    	            $results = array('status'=>true, 'msg'=>Lang::t('messages', 'Message sent and delivered!'), '_wd'=>330);
                    $model->send();
                    DataNodejs::increaseTotalMessage($_to_user->id);
    	        }else{
    	            $results = array('status'=>false, 'msg'=>Lang::t('messages', 'Message send failure!'), '_wd'=>330);
    	        }
	        }
	        echo CJSON::encode($results);
	        Yii::app()->end();
	    }
	    $this->renderPartial('partial/send', array('model'=>$model));
	}
	
	public function actionReply()
	{
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	        $to = Yii::app()->request->getPost('to');
	        $from = Yii::app()->request->getPost('from');
	        $subject = Yii::app()->request->getPost('subject');
	        $body = Yii::app()->request->getPost('body');
	        $answer_id = Yii::app()->request->getPost('answer_id');
	        if(!empty($answer_id) && !empty($to) && !empty($from) && !empty($body)){
    	        $message = Message::model()->findByPk($answer_id);
    	        $msg = new Message();
    	        $msg->timestamp = time();
    	        $msg->from_user_id = $from;
    	        $msg->to_user_id = $to;
    	        $msg->title = trim($subject);
    	        $msg->message = trim($body);
    	        $msg->message_read = Message::UNREAD_FROM;
    	        $msg->answered = $answer_id;
    	        $msg->validate();
    	        if(!$msg->hasErrors()){
    	            $message->message_read = Message::UNREAD_FROM;
    	            if($message->fromMe()){
    	                $message->message_read = Message::UNREAD_TO;
    	            }
    	            $message->timestamp = time();
    	            $message->status = Message::STATUS_ENABLE;
    	            $message->save();
    	            
    	            /**send email**/
    	            if($msg->save()){
    	            }
    	        }
    	        DataNodejs::updateTotalMessage($to);
    	        $this->renderPartial('partial/_view-msg', array('msg'=>$msg, 'read'=>false), false, true);
	            $to = Member::model()->findByPk($to);
	            if(!empty($to)){
    	            $model = new MessageForm();
    	            $model->sendMailMsg($to);
	            }
	            
    	        Yii::app()->end();
	        }
	        return false;
	    }
	}
	
	public function actionView()
	{
        $model = new Message();
        $data = array();
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	        $k = Yii::app()->request->getPost('k');
	        if(!empty($k)){
	            $pk = Util::decryptRandCode($k);
	            $model = Message::model()->findByPk($pk);
	            if(!empty($model)){
	            	$data['model'] = $model;
	            	$page = Yii::app()->request->getParam('page', 1);
		            $answers = $model->getAnswersPagging($page);
		            if(!empty($answers)){
		            	$data['answers'] = $answers;
		            }
	            }
	        }
	        $this->renderPartial('partial/view', $data, false, true);
	        DataNodejs::updateTotalMessage(Yii::app()->user->id);
	        Yii::app()->end();
	    }
	    return false;	    
	}
	
	public function actionDelete()
	{        
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	        $k = Yii::app()->request->getPost('k');
	        /** get next item **/
	        $offset = Yii::app()->request->getPost('offset');
	        $criteria = Message::model()->getMsgCDbCriteria($this->user->id, 1, $offset);
	        $messages = Message::model()->getMsgEnableByUser($criteria);
	        $message = (!empty($messages[0])) ?  $messages[0] : new Message();
	        /** delete item **/
	        if(!empty($k)){
	            $pk = Util::decryptRandCode($k);
	            $model = Message::model()->findByPk($pk);
	            if(!empty($model->id)){
	            	if(!empty($model->status) && ( ($model->status == Message::DELETE_FROM) || ($model->status == Message::DELETE_TO) )){
	            		$model->status = Message::DELETE_ALL;
	            	}else{
		            	if($model->fromMe()){
		                	$model->status = Message::DELETE_FROM;
		            	}elseif ($model->toMe()){
		            		$model->status = Message::DELETE_TO;
		            	}
	            	}
	                if($model->save()){	                    
	                    $this->renderPartial('partial/after-delete', array('message'=>$message), false, true);
	                    Yii::app()->end();
	                }
	            }
	            //DataNodejs::updateTotalMessage(Yii::app()->user->id);
	            DataNodejs::decreaseTotalMessage(Yii::app()->user->id);
	        }
	    }
	    
	}
}
