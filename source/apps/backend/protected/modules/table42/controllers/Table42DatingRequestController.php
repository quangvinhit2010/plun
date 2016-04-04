<?php

class Table42DatingRequestController extends PController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights',
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Table42DatingRequest']))
		{
			$model->attributes=$_POST['Table42DatingRequest'];
				
			if($model->save())
				$this->redirect(array('admin'));
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Table42DatingRequest');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Table42DatingRequest('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42DatingRequest']))
			$model->attributes=$_GET['Table42DatingRequest'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionWin()
	{
		$model=new Table42DatingRequest('win');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42DatingRequest']))
			$model->attributes=$_GET['Table42DatingRequest'];
	
		$this->render('win',array(
				'model'=>$model,
		));
	}	
	/**
	 * Manages all models.
	 */
	public function actionTopvote()
	{
		$model=new Table42DatingRequest('topvote');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42DatingRequest']))
			$model->attributes=$_GET['Table42DatingRequest'];
	
		$this->render('topvote',array(
				'model'=>$model,
		));
	}	
	/**
	 * Manages all models.
	 */
	public function actionApproved()
	{
		$model=new Table42Profile('approved');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42DatingRequest']))
			$model->attributes=$_GET['Table42DatingRequest'];
	
		$this->render('approved',array(
				'model'=>$model,
		));
	}	
	/**
	 * Manages all models.
	 */
	public function actionNotapproved()
	{
		$model=new Table42Profile('notapproved');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42Profile']))
			$model->attributes=$_GET['Table42Profile'];
	
		$this->render('notapproved',array(
				'model'=>$model,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PurpleguyRound the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Table42DatingRequest::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PurpleguyRound $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purpleguy-round-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
