<?php

class ProfileController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionUpdateAvatar(){
		if(Yii::app()->request->isPostRequest){
			$token = Yii::app()->request->getPost('token', false);
			$macaddress = Yii::app()->request->getPost('macaddress', false);
			
			if($token && $macaddress){
				$modelToken = ApiMobileTokens::model()->findByAttributes(array('token' => $token, 'macaddress' => $macaddress));
				if($modelToken){
					
				}else{
					//invalid token
					$arr	=	array(
							'result'	=>	false,
							'response' => array('msg' => 'invalid token')
					);
				}
			}						
		}else{
			$arr	=	array(
				'result'	=>	false,
				'response' => array('msg' => 'invalid action method.')
			);			
		}
		echo json_encode($arr);
		Yii::app()->end();	
	}
}