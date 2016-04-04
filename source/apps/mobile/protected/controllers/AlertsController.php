<?php
/**
 * @author vinhnguyen
 * @desc Alerts Controller
 */
class AlertsController extends MemberController
{
	public function actionIndex(){	 
	    if(!$this->user->isMe()){
	        throw new CHttpException ( 404, 'The requested page does not exist.' );
	    }   
	    $user = Yii::app()->user->data();
	    
        $dates = Yii::app()->db->createCommand()
        ->select("DATE_FORMAT(FROM_UNIXTIME(`timestamp`), '%d-%m-%Y') AS ndate")
        ->from('sys_notifications sn')
        ->where('ownerid=:user_id', array(':user_id'=>$user->id))
        ->group('ndate')
        ->order('timestamp DESC')
        ->limit(7)
        ->queryAll();	    
	    $this->render('page/index', array(
            'user'=>$user,
            'dates'=>$dates,
	    ));
	}

	public function actionShow(){
	    $user = Yii::app()->user->data();
	    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	        $ids = Yii::app()->request->getPost('ids');
    	    $criteria=new CDbCriteria();
    	    $criteria->addCondition("id IN ($ids)");
    	    $criteria->order = "timestamp DESC";
    	    $datas = XNotifications::model()->findAll($criteria);
    	    
    	    $this->renderPartial('partial/_view-alert', array(
    	            'datas'=>$datas,
    	    ));
	    }
	}
}