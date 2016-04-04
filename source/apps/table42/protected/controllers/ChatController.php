<?php

/**
 * 
 * Chat Class
 * @des: Chat
 * @author Nam
 *
 */


class ChatController extends MemberController 
{
	public function actionAuth() {
		
		if(Yii::app()->user->isGuest)
			exit;
		
		$bindServer = Yii::app()->params->XMPP['http-bind'];
		$server = Yii::app()->params->XMPP['server'];
		$jid = Yii::app()->user->data()->username;
		$password = Yii::app()->user->data()->chat_key;
		
		$xmpp = new Xmpp($bindServer, $server);
		$xmpp->connect($jid, $password);
		
		echo json_encode($xmpp);
	}
	
	public function actionDemo(){
		if(Yii::app()->user->isGuest){
			$this->redirect('/site/login');
		}
		$this->render('demo');
	}
	
	
	public function actionGroup(){
		
		if(Yii::app()->user->isGuest){
			$this->redirect('/site/login');
		}
		
		$this->render('group');
	}
	
	public function actionHistory($username, $offset = 1, $limit = 20){
	
		$return = array();
		if(Yii::app()->user->isGuest){
			$return = array('error' => 'not login');
		}
		$jid = $username.'@'.Yii::app()->params->XMPP['server'];
		$tjid = Yii::app()->user->name.'@'.Yii::app()->params->XMPP['server'];
		if(isset($offset)){
			$start = ($offset - 1) * $limit;
		} else {
			$start = 0;
		}
	
		if(isset($jid)){
			$sql = 'SELECT *, FROM_UNIXTIME(sentDate/1000) AS sentDate  FROM ';
			$sql .= '( SELECT * FROM ofMessageArchive WHERE (fromJID = :jid AND toJID = :tjid) OR (toJID = :jid and fromJID = :tjid) ORDER BY sentDate DESC LIMIT :limit OFFSET :offset)';
			$sql .= 'AS chatHistory ORDER BY sentDate ASC';
			$param = array('jid' => $jid, 'tjid' => $tjid, ':limit' => $limit, ':offset' => $start);
				
			/*
			 $criteria=new CDbCriteria;
			$criteria->select = '*, FROM_UNIXTIME(sentDate/1000) AS sentDate';
			$criteria->addCondition('(fromJID = :jid and toJID = :tjid) or (toJID = :jid and fromJID = :tjid)');
			$criteria->limit = $limit;
			$criteria->offset = $start;
			$criteria->order = 'sentDate DESC';
			$criteria->params = array('jid' => $jid, 'tjid' => $tjid);
			$result = MessageArchive::model()->findAll($criteria);
			*/
			$result = MessageArchive::model()->findAllBySql($sql, $param);
			if(count($result)){
				$return['offset'] = $offset + 1;
				$return['data'] = $result;
			}
		}
	
		echo CJSON::encode($return);
		exit();
	
	}
	
	public function actionHistory1($username, $offset = 1, $limit = 20){
		$return = array();
		if(Yii::app()->user->isGuest){
			$return = array('error' => 'not login');
		}
		$from = Yii::app()->user->name.'@'.Yii::app()->params->XMPP['server'];
		$to = $username.'@'.Yii::app()->params->XMPP['server'];
		if(isset($offset)){
			$start = ($offset - 1) * $limit;
		} else {
			$start = 0;
		}
		
		if(isset($to)){
			$sql = 'SELECT tbl.* FROM (';
				$sql .= 'SELECT *, FROM_UNIXTIME(`time`/1000) AS chat_time FROM `'. ArchiveMessages::model()->tableName().'` WHERE `conversationId` IN (';
					$sql .= 'SELECT MAX(`conversationId`) AS `conversationId` FROM `archiveConversations` ';
					$sql .= 'WHERE ownerJid =  :ownerJid AND withJid = :withJid)';
				$sql .= 'ORDER BY chat_time DESC LIMIT :limit OFFSET :offset';
			$sql .= ') AS tbl ORDER BY tbl.chat_time ASC';
			$param = array('ownerJid' => $from, 'withJid' => $to, ':limit' => $limit, ':offset' => $start);
			
			$result = ArchiveMessages::model()->findAllBySql($sql, $param);
			if(count($result)){
				$return['offset'] = $offset + 1;
				$return['data'] = $result;
				$return['from'] = $from;
				$return['to'] = $to;
			}
		}
		
		echo CJSON::encode($return);
		exit();
		
	}
	
	public function actionDelete(){
		if(Yii::app()->request->isAjaxRequest && isset(Yii::app()->user->id))
		{
			$jid = Yii::app()->request->getPost('jid', false);
			$tjid = Yii::app()->user->name.'@'.Yii::app()->params->XMPP['server'];
			if(isset($jid) && isset($tjid)){
				$model = MessageArchive::model()->deleteAll('(fromJID = :jid and toJID = :tjid) or (toJID = :jid and fromJID = :tjid)', array('jid' => $jid, 'tjid' => $tjid));
				if($model){
					echo 1;
					Yii::app()->end();
				}
			}
		}
	
	}
	
	public function actionGetUser($username){
		if($username){
			$member = Member::getUserByUsername($username);
			if($member->username) {
				$result = array();
				$result['name'] = $member->profile->firstname.' '.$member->profile->lastname;
				$result['avatar'] = $member->getAvatar();
			} else {
				$result['name'] = $username;
				$result['avatar'] = '/public/images/no-user.jpg';
			}
			echo CJSON::encode($result);
			exit();
		}
	}
	
	public function actionGetAvatar($avt){
		if(isset($avt) && is_numeric($avt)){
			$photo = Photo::model()->findByAttributes(array('id'=>$avt, 'status'=>1));
			if(!empty($photo->name) && file_exists($photo->path .'/thumb160x160/'. $photo->name)){
				$url = Yii::app()->createUrl($photo->path .'/thumb160x160/'. $photo->name);
				echo $url;
			}
		}
		
	}
	
}