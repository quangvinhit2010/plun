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
	
	/* public function actionIndex($to = null){
		
		
		if(Yii::app()->user->isGuest){
			$this->redirect('/site/login');
		}
		if(isset($to)){
			$to = Member::model()->findByAttributes(array('alias_name' => $to));
		} else {
			$to = false;
		}
		
		$friend_list =  Friendship::getFriendLists(Yii::app()->user->id);
		$this->render('index', array(
					'friend_list' => Friendship::getFriendLists(Yii::app()->user->id),
					'to' => $to
				
		));
	} */
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
			$result = array();
			$result['name'] = $member->profile->firstname.' '.$member->profile->lastname;
			$result['avatar'] = $member->getAvatar();
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