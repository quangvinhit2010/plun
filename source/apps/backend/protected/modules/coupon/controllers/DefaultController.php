<?php
require_once ('Facebook/facebook.php');
class DefaultController extends FrontController
{
	
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>array('index', 'events', 'view', 'getGiftOB'),
						'users'=>array('*'),
				),
				array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions'=>array('newbieGiftcode'),
						'users'=>array('@'),
				),
				array('deny',  // deny all users
					'users'=>array('*'),
				),
		);
	}
	
	public function actionIndex()
	{
		$this->render('index', array());
	}
	
	public function actionNewbieGiftcode()
	{
		$code  = Giftcode::model()->loadGiftCode(2, Yii::app()->user->id);
		$this->render('newbie', array('code' => $code));
		/* $facebook = new Facebook(array(
		  'appId'  => Yii::app()->params['Facebook']['app_id'],
		  'secret' => Yii::app()->params['Facebook']['app_secret'],
		));
		
		$user = $facebook->getUser();
 		$is_fan = false;
		$logoutUrl = null;
		$loginUrl = null;
		$code  = Giftcode::model()->loadGiftCode(2, Yii::app()->user->id);
		if ($user) {
		  try {
		    $likes = $facebook->api("/me/likes/".Yii::app()->params['Facebook']['fanpage_id']);
		    if( !empty($likes['data']) ){
		       $is_fan = true;
			}
		  } catch (FacebookApiException $e) {
			    error_log($e);
			    $user = null;
		  }
		}
		 
		if ($user) {
		  	$logoutUrl = $facebook->getLogoutUrl();
		} else {
		  	$loginUrl = $facebook->getLoginUrl(array('scope' => 'user_likes'));
		}
		 
		$this->render('newbie', array('user' => $user, 'code' => $code, 'is_fan' => $is_fan , 'logoutUrl' => $logoutUrl, 'loginUrl' => $loginUrl)); */
	}
	
	public function actionEvents()
	{
		$events = Events::model()->findAllByAttributes(array('enabled' => 1));
		$this->render('events', array('events' => $events));
	}
	
	public function actionView($id)
	{
		$event = $this->loadEventModel($id);
		$code  = Giftcode::model()->loadGiftCode($event->id, Yii::app()->user->id);
		$this->render('view', array('event' => $event, 'code' => $code));
	}
	
	public function loadEventModel($id)
	{
		$model = Events::model()->findByPk($id);
		if($model===null || $model->enabled == 0)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionGetGiftOB()
	{
		$user = Yii::app()->user->data();
		$start = strtotime('28-12-2012');
		$end = strtotime('10-01-2013');
		
		$criteria=new EMongoCriteria();
		$criteria->addCond('user_id', 'equals', (int) $user->id);
		$criteria->addCond('action', 'equals', YumActivityLog::LOG_USER_PLAY_GAME);
		$criteria->addCond('timestamp', 'greatereq', $start);
		$criteria->addCond('timestamp', 'lesseq', $end);
		$log = YumActivityLog::model()->find($criteria);
		
		$userPlayInGameOB = false;
		$code = '';
		$event = $this->loadEventModel(3);
		if(!empty($log)){
			$code  = Giftcode::model()->loadGiftCode($event->id, Yii::app()->user->id);
			$userPlayInGameOB = true;
		}
		
		$this->renderPartial('gift-ob', array('event' => $event, 'code' => $code, 'userPlayInGameOB'=>$userPlayInGameOB));
	}
}