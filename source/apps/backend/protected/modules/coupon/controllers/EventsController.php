<?php

class EventsController extends Controller
{
	public $layout='//layouts/column2';
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column2';
		$model=new Events;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Events']))
		{
			$model->attributes=$_POST['Events'];
			$model->start = strtotime($_POST['Events']['start']);
			$model->end   = strtotime($_POST['Events']['end']);
			$model->created = time();
			/*
			$image = CUploadedFile::getInstance($model,'image');
			$model->image = time() . '_'. $image->name;
			if($image){
				if($this->MakeDir() != false){
					$image->saveAs($this->MakeDir() . $model->image);
				}
			}
			*/
			if($model->validate() && $model->save()) 
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->layout='//layouts/column2';
		$model=$this->loadModel($id);
		$model->start = date('d-m-Y', $model->start);
		$model->end = date('d-m-Y', $model->end);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Events']))
		{
			$model->attributes = $_POST['Events'];
			$model->start = strtotime($_POST['Events']['start']);
			$model->end   = strtotime($_POST['Events']['end']);
			$image = CUploadedFile::getInstance($model,'image');
			if($image){
				if($this->MakeDir() != false){
					if(file_exists($this->MakeDir().$model->image)){
						try {
							@unlink($this->MakeDir().$model->image);
						} catch(ErrorException $ex) {
							echo "Error: " . $ex->getMessage();
						}
					}
					$model->image = time() . '_'. $image->name;
					$image->saveAs($this->MakeDir() . $model->image);
				}
			}
			if($model->validate() && $model->save()) {
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = $this->loadModel($id);
			if($model->codeCount == 0)
				$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin'));
		
		/*
		$dataProvider=new CActiveDataProvider('Events');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/column2';
		$model=new Events('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Events']))
			$model->attributes=$_GET['Events'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Events::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='events-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	private function MakeDir(){
		$folder = Yii::app()->getModule('coupon')->upload_path;
		if(!is_dir($folder)){
			try {
				$return = mkdir( $folder);
				if($return){
					return $folder;
				} else {
					return false;
				}
			} catch(ErrorException $ex) {
				echo "Error: " . $ex->getMessage();
			}
		} else {
			return $folder;
		}
	}
}
