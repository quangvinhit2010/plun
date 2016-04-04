<?php

class PhotoController extends Controller
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Photo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Photo']))
		{
			$model->attributes=$_POST['Photo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['Photo']))
		{
			$model->attributes=$_POST['Photo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Photo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Photo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Photo']))
			$model->attributes=$_GET['Photo'];
			if($model->user_id){
				$user = Member::model()->find('username = :username', array(':username' => $model->user_id)); 
				$model->user_id = $user->id; 
			}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Photo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Photo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Photo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='photo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionAjax($action){
		if(isset($_POST['select_photo'])){
			if(count($_POST['select_photo']) > 0){
				foreach($_POST['select_photo'] as $photo_id){
					$model=$this->loadModel($photo_id);
					$activity = Activity::model()->find('user_id = :user_id AND action = :action AND object_id = :object_id',
							array(
									':user_id' => $model->user_id,
									':action' => Activity::LOG_PHOTO_UPLOAD,
									':object_id' => $model->id
					
							));
					if($action == 'doDelete'){
						
						$model->status = 0;
						
						if(!empty($model->user->profile_settings->language->code)){
							$lang = $model->user->profile_settings->language->code;
						}else{
							$lang = Yii::app()->language;
						}
						
						
						
						$email = $model->user->getEmail();
						$username = $model->user->getDisplayName();
						
						$template_name = 'mail-photo-removed-'.$lang;
						$template = SimpleMailerTemplate::model()->findByAttributes(array(
								'name' => $template_name,
						));
						
						$template_vars = array(
								'__url__' => Yii::app()->request->getHostInfo(),
								'__username__' => $username,
								'__imgsrc__' => $model->getAdminImageThumbnail(true, array("width" => 100)),
								'__time__' => date('H:i A d/m/Y', time()),
						);
						SimpleMailer::$type = SimpleMailer::MAIL_PHOTO_REMOVED;
						SimpleMailer::enqueue($email, $template_name, $template_vars);
						if($activity){
							$activity->status = 0;
							$activity->save();
						}
						
						if($model->type == Photo::PUBLIC_PHOTO || $model->id == $model->user->avatar){
							//begin send message
							Yii::app()->setBasePath(Yii::getPathOfAlias ( 'frontend' ));
							Yii::app()->setLanguage($lang);
							Yii::import('frontend.models.*');
							$msg = new MessageForm();
							$msg->from = 'plunasia';
							$msg->to = $username;
							$msg->subject = 'Your photo has been deleted';
							$msg->body = Lang::t('messages', 'Delete {username} photo and send message at {date}', array('{username}' => $username, '{date}' => date('H:i A d/m/Y', time())));
							$msg->is_system = 1;
							$msg->validate();
							if(!$msg->hasErrors()){
								$msg->send();
							}else{
								echo json_encode($msg->errors);
							}
						}
						
					} elseif($action == 'doMovePublic'){
						$model->type = Photo::PUBLIC_PHOTO;
						if($activity){
							$activity->status = 1;
							$activity->save();
						}
					} elseif($action == 'doMoveVault'){
						$model->type = Photo::VAULT_PHOTO;
						if($activity){
							$activity->status = 0;
							$activity->save();
						}
					} else {
						throw new Exception("Action not found!",404);
					}
					$model->save();
					echo 'ok';
				}
			}
		}
	}
}
