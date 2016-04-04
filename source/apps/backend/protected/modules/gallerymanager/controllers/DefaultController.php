<?php
class DefaultController extends Controller
{
	public function actionIndex()
	{
		$galleries = Gallery::model()->findAll();
		$this->render('index', array(
			'galleries' => $galleries,
		));
	}
	
	public function actionDetail($id)
	{
		$gallery = Gallery::model()->findByPk($id);
		$this->render('detail', array(
			'gallery' => $gallery,
		));
	}
	
	public function actionCreate()
	{
		$model = new FrmAlbum();
		if(Yii::app()->request->isPostRequest){
			$model->attributes = Yii::app()->request->getPost('FrmAlbum');
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
			}
			$this->redirect('//gallerymanager/default/index');
		}
		$this->render('create', array(
				'model' => $model,
		));
	}
	
}