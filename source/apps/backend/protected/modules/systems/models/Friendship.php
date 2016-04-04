<?php
/**
 * @author VSoft
 */
class Friendship extends YumFriendship {
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	/**
	 * @param $inviter
	 * @param $invited
	 * @return boolean|string
	 */
	public function invitationLink($inviter, $invited) {
		if($inviter === $invited)
			return false;
		if(!is_object($inviter))
			$inviter = Member::model()->findByPk($inviter);
		if(!is_object($invited))
			$invited = Member::model()->findByPk($invited);

		$friends = $inviter->getFriends(true);
		if($friends && $friends[0] != NULL)
			foreach($friends as $friend) 
				if($friend->id == $invited->id)
					return false; // already friends, rejected or request pending

		return CHtml::link(Yum::t('Add as a friend'), array(
					'//friend/invite', 'alias' => $invited->alias_name));
	}
	
	
	
	public static function addRoster($inviter, $invited){
		if($inviter === $invited)
			return false;
		if(!is_object($inviter))
			$inviter = Member::model()->findByPk($inviter);
		if(!is_object($invited))
			$invited = Member::model()->findByPk($invited);
		
		$exits = Roster::model()->find('username = :username and jid = :jid', array(':username' => $inviter->username, 'jid' => $invited->username.'@'.Yii::app()->params['XMPP']['domain']));
		if(empty($exits)){
			$roster = new Roster();
			$roster->username =  $inviter->username;
			$roster->jid =  $invited->username.'@'.Yii::app()->params['XMPP']['domain'];
			$roster->sub =  0;
			$roster->ask =  0;
			$roster->recv = -1;
			$roster->nick = $invited->username;
			if($roster->save()){
				$roster = new Roster();
				$roster->username =  $invited->username;
				$roster->jid =  $inviter->username.'@'.Yii::app()->params['XMPP']['domain'];
				$roster->sub =  0;
				$roster->ask =  -1;
				$roster->recv = 1;
				$roster->nick = $inviter->username;
				if($roster->save()){
					return true;
				}
				
			}
			
		}
		
		return false;
		
	}
	
	public static function getFriendLists($user_id){
		$cri = new CDbCriteria();
		if($user_id){
			$cri->addCondition("");
			$friendship = YumFriendship::model()->findAll('status = 2 AND inviter_id = '.$user_id . ' OR friend_id = '.$user_id);
			if($friendship){
				return $friendship;
			} else {
				return false;
			}
		}else{
			return false;
		}
		
	}
		
	public function getAllFriendID($user_id){
	    $sql = "SELECT tbl.user_id FROM (
	    SELECT inviter_id AS user_id  FROM usr_friendship WHERE (inviter_id = :user_id OR friend_id = :user_id) AND STATUS = 2 GROUP BY user_id
	    UNION ALL
	    SELECT friend_id AS user_id  FROM usr_friendship WHERE (inviter_id = :user_id OR friend_id = :user_id) AND STATUS = 2 GROUP BY user_id
	    ) tbl";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
        $result = $command->queryColumn();
	    if(!empty($result)){   
	    	if(!is_array($result)){ 	    
    	    	$result = array($result);
	    	}
	    	return $result;
	    }else{
	    	return array();
	    }
	}
} 