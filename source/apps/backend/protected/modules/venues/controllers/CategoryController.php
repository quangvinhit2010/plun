<?php

class CategoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
				'rights',
		);
	}
	public function actionUpload() {
		$error = "";
		$time = "";
		if($_FILES["image_upload"]["type"] != "image/jpg" && $_FILES["image_upload"]["type"] != 'image/jpeg') {
			$error = 'Only accept jpg file';
		} else {
			$time = time();
			$fileName = YiiBase::getPathOfAlias('pathroot').SysBanner::PATH . $time . '.jpg';
			move_uploaded_file($_FILES["image_upload"]["tmp_name"], $fileName);
		}
		echo '<script>parent.uploadSuccess("'.SysBanner::PATH.'","'.$time.'.jpg", "'.$error.'")</script>';
		exit;
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CmsVenuesCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsVenuesCategory']))
		{
			if(!empty($_FILES['thumbnail']['name'])){
				//upload photo
				$params = CParams::load ();
				$thumb200x200 = $params->params->uploads->venues->detail200x200;
				$origin = $params->params->uploads->venues->origin;
				
				
				Yii::import ( "backend.extensions.plunUploader.upload");
				$uploader = new upload($params->params->uploads->upload_method);
				$uploader->allowedExtensions = array (
						'jpg',
						'jpeg',
						'png'
				);
				$uploader->inputName	=	'thumbnail';
				$uploader->sizeLimit = $params->params->uploads->venues->size; // maximum file size in bytes
					
				$thumb200x200_folder = $uploader->setPath ( $thumb200x200->p , false );
	
				$origin_folder = $uploader->setPath ( $origin->p, false );
				$path_folder = $uploader->setPath ( $params->params->uploads->venues->path, false );
				$result = $uploader->upload ( $origin_folder );
				
				// get origin path after file upload successfully
				$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
					
				//load images resource
				$uploader->loadImageResource($origin_uploaded_path);
				// resize for thumbnail (width 200)
				$uploader->resizeImage($thumb200x200->w, $thumb200x200->w);
				$uploader->saveImg($thumb200x200_folder . DS . $uploader->getUploadName ());
				
				$_POST['CmsVenuesCategory']['thumbnail_path']	=	ltrim($path_folder,'\/'); 
				$_POST['CmsVenuesCategory']['thumbnail']		=	$uploader->getUploadName ();
			}

			$model->attributes=$_POST['CmsVenuesCategory'];
			$model->date_created = time();
			$model->user_created = Yii::app()->user->getId();
			
			if($model->save())
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsVenuesCategory']))
		{
			
			if(!empty($_FILES['thumbnail']['name'])){
				//upload photo
				$params = CParams::load ();
				$thumb200x200 = $params->params->uploads->venues->detail200x200;
				$origin = $params->params->uploads->venues->origin;
					
					
				Yii::import ( "backend.extensions.plunUploader.upload");
				$uploader = new upload($params->params->uploads->upload_method);
				$uploader->allowedExtensions = array (
						'jpg',
						'jpeg',
						'png'
				);
				$uploader->inputName	=	'thumbnail';
				$uploader->sizeLimit = $params->params->uploads->venues->size; // maximum file size in bytes
				
				$thumb200x200_folder = $uploader->setPath ( $thumb200x200->p , false );
				
				$origin_folder = $uploader->setPath ( $origin->p, false );
				$path_folder = $uploader->setPath ( $params->params->uploads->venues->path, false );
				$result = $uploader->upload ( $origin_folder );
					
				// get origin path after file upload successfully
				$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
				
				//load images resource
				$uploader->loadImageResource($origin_uploaded_path);
				// resize for thumbnail (width 200)
				$uploader->resizeImage($thumb200x200->w, $thumb200x200->w);
				$uploader->saveImg($thumb200x200_folder . DS . $uploader->getUploadName ());
					
					
					
				$_POST['CmsVenues']['thumbnail_path']	=	ltrim($path_folder,'\/');
				$_POST['CmsVenues']['thumbnail']		=	$uploader->getUploadName ();
			}
			
			$model->date_modified = time();
			$model->attributes=$_POST['CmsVenuesCategory'];
			$model->validate();
			if($model->save())
				$this->redirect(array('admin'));
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
		$this->loadModel($id)->delete();
		//delete to elasticsearch
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('CmsVenuesCategory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CmsVenuesCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CmsVenuesCategory']))
			$model->attributes=$_GET['CmsVenuesCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SysBanner the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CmsVenuesCategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SysBanner $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sys-banner-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionLoadAvalableImage() {
		$banners = CmsVenues::model()->findAll(array("select"=>"file_name"));
		$this->renderPartial('partial/images_list', array('banners'=>$banners));
	}
	public function actionDeleteBackground($file_name) {
		unlink(YiiBase::getPathOfAlias('pathroot').SysBanner::PATH.$file_name.'.jpg');
	}
	public function actionGetState(){
		$country_id = Yii::app()->request->getPost('country_id');
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("country_id = {$country_id}");
		$state	=	LocationState::model()->findAll($criteria);

		$this->renderPartial('partial/list_state', array(
			'country_id'	=>	$country_id,
			'state'	=>	$state
		));		
	}
	public function actionGetCity(){
		$state_id = Yii::app()->request->getPost('state_id');
	
		$criteria=new CDbCriteria;
		$criteria->addCondition("state_id = {$state_id}");
		$city	=	SysCity::model()->findAll($criteria);
	
		$this->renderPartial('partial/list_city', array(
				'state_id'	=>	$state_id,
				'city'	=>	$city
		));
	}	
	public function actionGetDistrict(){
		$city_id = Yii::app()->request->getPost('city_id');
	
		$criteria=new CDbCriteria;
		$criteria->addCondition("city_id = {$city_id}");
		$district	=	SysDistrict::model()->findAll($criteria);
	
		$this->renderPartial('partial/list_district', array(
				'city_id'	=>	$city_id,
				'district'	=>	$district
		));
	}	
}
