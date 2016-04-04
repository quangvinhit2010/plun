<?php

class DefaultController extends PController
{
	public function actionIndex()
	{
		$this->render('index');
	}
}