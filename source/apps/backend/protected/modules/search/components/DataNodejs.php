<?php
class DataNodejs {
	public static function getTotalAlert($user_id){
		
		$cmd = Yii::app()->db->createCommand();
		$cmd->select("count(*) as total_alert");
		$cmd->from('sys_notifications');
		$cmd->andWhere("status = 1 AND last_read = 0 and ownerid ='{$user_id}'");
	
		$total = $cmd->queryScalar();		
		return $total;
	}
	public static function increaseTotalAlert($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_alert', '+', $num);		
	}
	public static function decreaseTotalAlert($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_alert', '-', $num);
	}	
	public static function updateTotalAlert($user_id){
		$update_data	=	array(
				'total_alert'	=>	DataNodejs::getTotalAlert($user_id)
		);
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->update($update_data, 0, $user_id);		
	}
	public static function getTotalPhotorequest($user_id){
	
		$cmd = Yii::app()->db->createCommand();
		$cmd->select("count(*) as total_request");
		$cmd->from('sys_photo_request');
		$cmd->andWhere("( request_user_id = '{$user_id}' AND is_read = 4) OR (photo_user_id = '{$user_id}' AND is_read = 0) OR (request_user_id = '{$user_id}' AND is_read = 2)");
	
		$total = $cmd->queryScalar();
		return $total;
	}
	public static function increaseTotalPhotorequest($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_photo_request', '+', $num);
	}
	public static function decreaseTotalPhotorequest($user_id, $num){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_photo_request', '-', $num);
	}		
	public static function updateTotalPhotorequest($user_id){
		$update_data	=	array(
				'total_photo_request'	=>	DataNodejs::getTotalPhotorequest($user_id)
		);
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->update($update_data, 0, $user_id);
	}	
	public static function getTotalAddFriend($user_id){
	
		$cmd = Yii::app()->db->createCommand();
		$cmd->select("count(*) as total_friend");
		$cmd->from('usr_friendship');
		$cmd->andWhere("(friend_id ='{$user_id}') AND status = 1");
	
		$total = $cmd->queryScalar();
		return $total;
	}
	public static function increaseTotalAddFriend($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_addfriend_request', '+', $num);
	}
	public static function decreaseTotalAddFriend($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_addfriend_request', '-', $num);
	}	
	public static function updateTotalAddFriend($user_id){
		$update_data	=	array(
				'total_addfriend_request'	=>	DataNodejs::getTotalAddFriend($user_id)
		);
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->update($update_data, 0, $user_id);
	}	
	public static function getTotalMessage($user_id){
	
		$cmd = Yii::app()->db->createCommand();
		$cmd->select("count(*) as total_msg");
		$cmd->from('usr_message');
		$cmd->andWhere("((from_user_id ='{$user_id}' AND message_read = 0 AND `status` != -1) OR (to_user_id ='{$user_id}' AND message_read = 2 AND `status` != -2)) AND `status` != -3 AND answered = 0");
	
		$total = $cmd->queryScalar();
		return $total;
	}	
	public static function increaseTotalMessage($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_message', '+', $num);
	}
	public static function decreaseTotalMessage($user_id, $num = 1){
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->updateCounter($user_id, 'total_message', '-', $num);
	}	
	public static function updateTotalMessage($user_id){
		$update_data	=	array(
				'total_message'	=>	DataNodejs::getTotalMessage($user_id)
		);
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->update($update_data, 0, $user_id);
	}
	public static function updateFriendlist($user_id){
		$model = UsrProfileLocation::model()->findByAttributes(array('user_id' => $user_id));
		$my_friendlist	=	Friendship::model()->getAllFriendID($user_id);
		$update_data	=	array(
				'friendlist'	=>	implode(',', $my_friendlist)
		);
		$Elasticsearch	=	new Elasticsearch();
		$Elasticsearch->update($update_data, $model->current_country_id, $user_id);		
	}			
	public static function updateTotalCandyAlert($user_id){
		$model = UsrProfileLocation::model()->findByAttributes(array('user_id' => $user_id));
		if(Yii::app()->user->data()->balance) {
			$update_data	=	array(
					'total_candy_alert'	=>	isset(Yii::app()->user->data()->balance->new_transaction)	?	Yii::app()->user->data()->balance->new_transaction : 0
			);
			$Elasticsearch	=	new Elasticsearch();
			$Elasticsearch->update($update_data, $model->current_country_id, $user_id);	
		}
	}
}