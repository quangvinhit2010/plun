<?php

class CandyController extends MemberController
{
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'backend.extensions.captchaExtended.CaptchaExtendedAction',
				'mode'=> CaptchaExtendedAction::MODE_NUM,
				'offset'=>'4',
				'density'=>'0',
				'lines'=>'0',
				'fillSections'=>'0',
				'foreColor'=>'0x000000',
				'minLength'=>'6',
				'maxLength'=>'6',
				'fontSize'=>'24',
				'angle'=>false,
			),
		);
	}
	public function actionIndex()
	{
		if(!$this->user->isMe()) {
			$this->redirect(Yii::app()->user->data()->createUrl('//candy/index'));
		}
		
		$charge = Charge::model()->find('type = :type', array(':type' => Charge::TYPE_USER_TRANSFER));
		
		if(!$charge) {
			echo 'Charge Type of transfer is not defined in DB';
			exit();
		}

		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$errorMessage = "";
			
			$captcha = Yii::app()->getController()->createAction("captcha");
			$code = $captcha->verifyCode;
			if($code !== $_REQUEST['captcha']) {
				$errorMessage = Lang::t('candy', 'The verification code is incorrect.');
			} else {
				$candy = $_POST['candy'];
				$receivers = array_filter(explode(',', $_POST['receiver']));
				$totalReceiver = count($receivers);
					
				if(!$totalReceiver) {
					$errorMessage = Lang::t('candy', 'Please enter username of receiver');
				} else if(!$candy) {
					$errorMessage = Lang::t('candy', 'Please choose candy amount to transfer');
				} else {
					$balance = Balance::model()->getBalance($this->user->id);
					$totalCandy = $candy*count($receivers);
					if($charge->charge_type == Charge::CHARGE_TYPE_AMOUNT)
						$totalCandy = $totalCandy + ($charge->charge*$totalReceiver);
					else
						$totalCandy = $totalCandy + ($totalCandy*$charge->charge/100);
					if($totalCandy > $balance->candy) {
						$errorMessage = Lang::t('candy', 'You are not enought candy to transfer');
					} else {
						foreach ($receivers as $receiver) {
							$user = Member::model()->getUserByUsername($receiver);
							$receiverId = $user->id;
							$description = 'transfer candy';
							if(!$balance->transfer($this->user->id, $receiverId, NULL, Balance::TYPE_TRANSFER, $candy, $charge->id, $description)) {
								$errorMessage = $balance->getErrorCode();
								break;
							} else {
								$restCandy = $balance->candy - $totalCandy;
							}
						}
					}
				}
			}
			
			if($errorMessage)
				echo json_encode(array( 'error' => '1', 'message' => $errorMessage, ));
			else
				echo json_encode(array( 'error' => '0', 'message' => Lang::t('candy', 'Transfer successfully !!!'), 'restCandy' => $restCandy));
			
			exit();
		}
		$this->render('page/index', array('charge'=>$charge));
	}
	
	public function actionHistory()
	{
		if(!$this->user->isMe()) {
			exit();
		}
		
		$criteria = new CDbCriteria();
		$criteria->order = 'created DESC';
		$criteria->condition = 'user_id = '.$this->user->id;
		
		$params = array();
		
		if(Yii::app()->request->getQuery('actionType')) {
			$params['actionType'] = Yii::app()->request->getQuery('actionType');
			$criteria->addCondition('action_type = :action_type');
			$criteria->params[':action_type'] = $params['actionType'];
		}
		
		$params['start'] = (Yii::app()->request->getQuery('start')) ? Yii::app()->request->getQuery('start') : date('1-m-Y');
		$dateTime = explode('-', $params['start']);
		$start = mktime(0,0,0,$dateTime[1],$dateTime[0],$dateTime[2]);
		
		$criteria->addCondition('created >= :start');
		$criteria->params[':start'] = $start;
		
		if(Yii::app()->request->getQuery('end')) {
			$params['end'] = Yii::app()->request->getQuery('end');
			$dateTime = explode('-', $params['end']);
			$end = mktime(23,59,59,$dateTime[1],$dateTime[0],$dateTime[2]);
			$criteria->addCondition('created <= :end');
			$criteria->params[':end'] = $end;
		}
		
		$total = CandyHistory::model()->count($criteria);
		
		$page = Yii::app()->request->getQuery('page') ? Yii::app()->request->getQuery('page') : 1;
		$params['page'] = $page + 1;
		$criteria->limit = isset(Yii::app()->params->candy['limitHistoryShow']) ? Yii::app()->params->candy['limitHistoryShow'] : 20;
		$criteria->offset = ($page-1)*$criteria->limit;
		$compareWithTotal = $criteria->offset + $criteria->limit;
		
		$candyHistorys = CandyHistory::model()->findAll($criteria);
		
		$this->renderPartial('partial/history', array(
			'candyHistorys'=>$candyHistorys, 
			'total'=>$total, 
			'page'=>$page, 
			'compareWithTotal'=>$compareWithTotal,
			'params'=>$params,
		));
	}
	
	public function actionWelcome() {
		$this->renderPartial('partial/welcome');
	}
	
	public function actionGetFreeCandy() {
		$getFree = new GetFree();
		if($getFree->find('user_id = :user_id', array(':user_id'=>$this->user->id))) {
			echo json_encode(array('error'=>'1', 'message'=>Lang::t('candy', 'You are get free before. Every one only get free candy for once')));
		} else {
			$balance = new Balance();
			if($balance->transfer(FALSE, $this->user->id, FALSE, Balance::TYPE_GET_FREE, GetFree::FREE_AMOUNT, FALSE)) {
				$getFree->user_id = $this->user->id;
				$getFree->amount = GetFree::FREE_AMOUNT;
				$getFree->type = 1;
				$getFree->description = 'get Free candy for beta version';
				$getFree->created = time();
				$getFree->save();
				echo json_encode(array('error'=>'0', 'candy'=>GetFree::FREE_AMOUNT,'message'=>sprintf(Lang::t('candy', 'Congulation !!! You get %1 Candy for free'), GetFree::FREE_AMOUNT)));
			} else {
				$error = $balance->getErrorCode();
				echo json_encode(array('error'=>'1', 'message'=>$error));
			}
		}
		exit();
	}
	
	public function actionPurchase() {
		$html = $this->renderPartial('partial/purchase/index', array(), true);
		echo CJSON::encode(array('status'=>true, 'html'=>$html));
// 		echo json_encode(array('error'=>'1', 'message'=>$error));
		exit();
	}
}