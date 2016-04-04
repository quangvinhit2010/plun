<?php

class Table42ResultController extends PController
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
	
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$result_model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Table42Result']))
		{
			$result_model->attributes=$_POST['Table42Result'];
				
			if($result_model->save())
				
				//upload
				$params = CParams::load ();
				$thumb203x204 = $params->params->uploads->photo->table42_thumb203x204;
				$thumb768x1024 = $params->params->uploads->photo->table42_thumb768x1024;
				
				$origin = $params->params->uploads->photo->table42_origin;
				
				Yii::import ( "backend.extensions.plunUploader.upload");
				
				for ($i = 1; $i <= 5 ; $i++){
					if(!isset($_FILES['Table42Result' . $i]['name'])){
						break;
					}
					if($_FILES['Table42Result' . $i]['name'] != ''){
						$uploader = new upload($params->params->uploads->upload_method);
						$uploader->allowedExtensions = array (
								'jpg',
								'jpeg',
								'png'
						);
						$uploader->inputName	=	'Table42Result' . $i;
						$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
							
						$thumb203x204_folder = $uploader->setPath ( $thumb203x204->p , false );
						$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
				
						$origin_folder = $uploader->setPath ( $origin->p, false );
						$path_folder = $uploader->setPath ( $params->params->uploads->photo->table42_path, false );
						$result = $uploader->upload ( $origin_folder );
				
					
						if ($result ['success']) {
							// get origin path after file upload successfully
							$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
							//load images resource
							$uploader->loadImageResource($origin_uploaded_path);
				
							// resize & crop for thumbnail (width 203 height 204)
							$uploader->resizeImage($thumb203x204->w, $thumb203x204->h);
							$uploader->sharpen(20);
							$uploader->crop($thumb203x204->w, $thumb203x204->h, 'top');
							$uploader->saveImg($thumb203x204_folder . DS . $uploader->getUploadName ());
				
							// resize & crop for thumbnail (width 768 height 1024)
							$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
							$uploader->sharpen(20);
							$uploader->crop($thumb768x1024->w, $thumb768x1024->h, 'top');
							$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());
				
							// save to database
							$model = new Table42ResultPhoto ();
							$model->result_id = $result_model->id;
							$model->name = $uploader->getUploadName ();
							$model->path = ltrim($path_folder,'\/');
							$model->status = 1;
							$model->date_created = time ();
							$model->save ();
				
							$uploader->detroyResource();
						}
					}
				}
				//end upload
				
				$this->redirect(array('admin'));
		}
		
		$this->render('update',array(
			'model'=>$result_model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Table42Result');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Table42Result('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Table42Result']))
			$model->attributes=$_GET['Table42Result'];

		$this->render('admin',array(
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
		$model=Table42Result::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$result_model=new Table42Result;
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['Table42Result']))
		{
			$result_model->attributes=$_POST['Table42Result'];
			if( $result_model->save())
				
				//upload
				$params = CParams::load ();
				$thumb203x204 = $params->params->uploads->photo->table42_thumb203x204;
				$thumb768x1024 = $params->params->uploads->photo->table42_thumb768x1024;
				
				$origin = $params->params->uploads->photo->table42_origin;
				
				Yii::import ( "backend.extensions.plunUploader.upload");
				
				for ($i = 1; $i <= 5 ; $i++){
					
					if($_FILES['Table42Result' . $i]['name'] != ''){
						$uploader = new upload($params->params->uploads->upload_method);
						$uploader->allowedExtensions = array (
								'jpg',
								'jpeg',
								'png'
						);
						$uploader->inputName	=	'Table42Result' . $i;
						$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
							
						$thumb203x204_folder = $uploader->setPath ( $thumb203x204->p , false );
						$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
						
						$origin_folder = $uploader->setPath ( $origin->p, false );
						$path_folder = $uploader->setPath ( $params->params->uploads->photo->table42_path, false );
						$result = $uploader->upload ( $origin_folder );
						
						if ($result ['success']) {
							// get origin path after file upload successfully
							$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
							//load images resource
							$uploader->loadImageResource($origin_uploaded_path);
						
							// resize & crop for thumbnail (width 203 height 204)
							$uploader->resizeImage($thumb203x204->w, $thumb203x204->h);
							$uploader->sharpen(20);
							$uploader->crop($thumb203x204->w, $thumb203x204->h, 'top');
							$uploader->saveImg($thumb203x204_folder . DS . $uploader->getUploadName ());
						
							// resize & crop for thumbnail (width 768 height 1024)
							$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
							$uploader->sharpen(20);
							$uploader->crop($thumb768x1024->w, $thumb768x1024->h, 'top');
							$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());
						
							// save to database
							$model = new Table42ResultPhoto ();
							$model->result_id = $result_model->id;
							$model->name = $uploader->getUploadName ();
							$model->path = ltrim($path_folder,'\/');
							$model->status = 1;
							$model->date_created = time ();
							$model->save ();
						
							$uploader->detroyResource();					
						}	
					}				
				}
				//end upload
				
				
				$this->redirect(array('admin'));
						
						
						
		}
	
		$this->render('create',array(
				'model'=>$result_model,
		));
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
