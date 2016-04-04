<?php
class DemoCommand extends CConsoleCommand
{
	public function actionIndex() {
		
		$path = '../../../../uploads/demo/';
		/* $image = Yii::app()->image->load($path.'chat.jpg');
		$image->resize(400, 100);
		$image->save($path.'chat-400-199.jpg'); */
		
		$image1 = Yii::app()->image->load($path.'5048655756_becd204306_o.jpg');
		$image1->resize(500, 500);
		$image1->save($path.'re-5048655756_becd204306_o-500-500.jpg');
		
	}
}