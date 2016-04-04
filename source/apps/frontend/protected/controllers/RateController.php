<?php
/**
 * @author vinhnguyen
 * @desc Rate Controller
 */
class RateController extends MemberController
{
	public function actionIndex()
	{
	    $this->renderPartial('partial/index', array());
	}
	
	public function actionList()
	{
		$view = Util::decryptRandCode(Yii::app()->request->getParam('var'));
		$html = $this->renderPartial('partial/'.$view, array(), true);
		echo CJSON::encode(array('error'=>0, 'html'=>$html));
		// 		echo json_encode(array('error'=>'1', 'message'=>$error));
		exit();		
	}
}