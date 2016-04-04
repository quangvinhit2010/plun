<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('/invitation/history/admin'));
		$this->render('index');
	}
}