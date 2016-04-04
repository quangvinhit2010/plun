<?php
class BetaController extends Controller
{
	public function actionPutEmailReInvitation()
	{
		$model = new ReceiveInvitationForm();
		if(Yii::app()->request->isPostRequest){
			$model->attributes = Yii::app()->request->getPost('ReceiveInvitationForm');
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				echo json_encode(array('msg'=>'Email successfully submitted. Stay tuned!'));
			}
			Yii::app()->end();
		}
	}
}