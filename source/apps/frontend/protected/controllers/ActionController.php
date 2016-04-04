<?php
/**
 * @author vinhnguyen
 * @desc ActionController
 */
class ActionController extends MemberController {
	public function actionCommentsPrevious() {
		$page = Yii::app()->request->getParam('page', 1);
		$params['object_id'] = Yii::app()->request->getParam('object_id');
		$params['data'] = Comment::model()->getComments($params['object_id'], Comment::COMMENT_ACTIVITY, $page);
		$view = Yii::app()->request->getParam('view');
		$view = Util::decrypt($view);
		$this->renderPartial($view, $params);
		Yii::app()->end();
	}
	/**
	 * like newsfeed
	 */
	public function actionLike($oid, $type) {
		$arr = Like::callLike(Util::decrypt($oid), $type, $this->usercurrent->id);
		if (!empty($arr)) {
			$activity = Activity::model()->findByPk(Util::decrypt($oid));
			$notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_LIKE_WALL));
			$notification_data = array(
					'params' => array('{userfrom}'=>$this->usercurrent->username, '{userto}'=>$activity->owner->username),
					'activity_id' => $activity->id,
					'message' => Yum::t('{userfrom} {like} {userto} {status}'),
			);
	
			$notiExist = XNotifications::model()->findByAttributes(array(
					'userid'=>$this->usercurrent->id,
					'ownerid'=>$activity->owner_id,
					'owner_type'=>'user',
					'notification_type'=>$notifiType->id,
					'notification_data'=>addslashes(json_encode($notification_data)),
			));
			if(empty($notiExist)){
				$data = array(
						'userid'=>$this->usercurrent->id,
						'ownerid'=>$activity->owner_id,
						'owner_type'=>'user',
						'notification_type'=>$notifiType->id,
						'notification_data'=>addslashes(json_encode($notification_data)),
						'timestamp'=>time(),
						'last_read'=>0,
						'object_id'	=>	$activity->id
				);
				XNotifications::model()->saveNotifications($data);
			}else{
				$notiExist->timestamp = time();
				$notiExist->last_read = 0;
				$notiExist->save();
			}
		}
		DataNodejs::updateTotalAlert($activity->owner_id);
	
		echo htmlspecialchars(json_encode($arr), ENT_NOQUOTES);
		Yii::app()->end();
	}
}