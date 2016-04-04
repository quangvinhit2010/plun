<?php

class GiftcodeController extends Controller
{
	public $layout='//layouts/column2';
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/column2';
		$model=new Giftcode('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Giftcode'])) {
			$model->attributes = $_GET['Giftcode'];
			$model->username = $_GET['Giftcode']['username'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionImport() {
		$this->layout='//layouts/column2';
		$model = new ImportForm();
		
		if (isset($_POST['ImportForm'])) {
			$model->attributes = $_POST['ImportForm'];
			$import = explode("\n", $model->code);
			if(count($import) > 0) {
				foreach ($import as $code) {
					$code = trim($code);
					if(!empty($code)){
						$giftcode = new Giftcode();
						$giftcode->event_id = $model->event;
						$giftcode->code = $code;
						$giftcode->status = 0;
						$giftcode->user_id = 0;
						$giftcode->save();
					}
				}
				$this->redirect('admin');
			}		
		}
		
		$this->render('import',array(
			'model'=>$model,
		));
	}
	
	public function actionGenerate() {
		$this->layout='//layouts/column2';
		
		$model = new GiftcodeForm();

		if (isset($_POST['GiftcodeForm'])) {
			$model->attributes = $_POST['GiftcodeForm'];
			if($model->validate()) {
				Giftcode::model()->saveGiftCode($model->event, $model->type, $model->quantity, $model->formula, $model->numberOfDigit); 
				$this->redirect('admin');
			}
		}
	
		$this->render('generate',array(
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
		$model=Giftcode::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='giftcode-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
