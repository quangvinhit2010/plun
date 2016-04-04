<?php

class PurpleguyProfileController extends PController
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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
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
		$model=new PurpleguyProfile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurpleguyProfile']))
		{
			$model->attributes=$_POST['PurpleguyProfile'];
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

		if(isset($_POST['PurpleguyProfile']))
		{
			$model->attributes=$_POST['PurpleguyProfile'];
			if($model->save()){
			    $purpleGuyRound = PurpleguyRound::model()->getCurrentRound();
			    if($model->status == PurpleguyProfile::PASS){
			        if(!empty($model->user->profile_settings->language->code)){
			            $lang = $model->user->profile_settings->language->code;
			        }else{
			            $lang = Yii::app()->language;
			        }
			        $username = $model->user->getAliasName();
			        $url_profile = CParams::load ()->params->purpleguy->url.'vote/'.$model->user_id.'-'.$model->username;
		            //begin send message
		            Yii::app()->setBasePath(Yii::getPathOfAlias ( 'purpleguy' ));
		            Yii::app()->setLanguage('vi');
		            Yii::import('frontend.models.*');
		            $msg = new MessageForm();
		            $msg->from = 'plunasia';
		            $msg->to = $username;
		            $msg->subject = 'PurpleGuy approved your profile';
		            $msg->body = Lang::t('messages', 'Congratulations, you have been accepted into the preliminary competition of the Purple Guy {round} May 2014. Here is your link profile: {url_profile}. Please share with your friends to get the most votes.', array('{round}' => date('m'), '{url_profile}' => '<a href="'.$url_profile.'">'.$url_profile.'</a>' ));
		            $msg->is_system = 1;
		            $msg->validate();
		            if(!$msg->hasErrors()){
		                $msg->send();
		            }else{
		                echo json_encode($msg->errors);
		            }
			    }
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$photos = array();
		if(!empty($model)){
		    $photos = PurpleguyPhoto::model()->findAllByAttributes(array('user_id'=>$model->user_id, 'status'=>1));
		}

		$this->render('update',array(
			'model'=>$model,
			'photos'=>$photos,
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
		$dataProvider=new CActiveDataProvider('PurpleguyProfile');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PurpleguyProfile('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PurpleguyProfile']))
			$model->attributes=$_GET['PurpleguyProfile'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PurpleguyProfile the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PurpleguyProfile::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PurpleguyProfile $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purpleguy-profile-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionThumb()
	{
	    $prid = Yii::app()->request->getParam('prid');
	    $phid = Yii::app()->request->getParam('phid');
	    $model=PurpleguyProfile::model()->findByPk($prid);
	    if($model===null)
	        throw new CHttpException(404,'The requested page does not exist.');
	    $model->thumbnail_id = $phid;
	    $model->save();
	    
	}
	
	public function actionDelPhoto()
	{
	    $prid = Yii::app()->request->getParam('prid');
	    $phid = Yii::app()->request->getParam('phid');
	    $pf = PurpleguyProfile::model()->findByPk($prid);
	    if($pf===null)
	        throw new CHttpException(404,'The requested page does not exist.');
	    if($pf->thumbnail_id == $phid ){
    	    $pf->thumbnail_id = 0;
    	    $pf->save();
	    }
	    $photo = PurpleguyPhoto::model()->findByPk($phid);
	    if(!empty($photo)){
	        if(!empty($photo->name) && file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/thumb270x270/'. $photo->name)){
	            unlink(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/thumb270x270/'. $photo->name);
	        }
	        if(!empty($photo->name) && file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/detail1600x900/'. $photo->name)){
	            unlink(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/detail1600x900/'. $photo->name);
	        }
	        $photo->status = 0;
	        $photo->save();
	        return true;
	    }
	    
	}
	
}
