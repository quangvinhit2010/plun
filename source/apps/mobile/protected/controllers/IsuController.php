<?php
/*
 *Nam Le 
 *nam@dwm.vn
 *I See U controller
 */

class IsuController extends MemberController {
	
	
	public function actionIndex($page = null){
		
		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('status = :status OR (user_id = :user_id AND status != :status)');
		$criteria->params = array(':status' => Notes::STATUS_ACTIVE, ':user_id' => Yii::app()->user->id);
		$criteria->order = 't.id DESC';
		$total = Notes::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['isu_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$isus = Notes::model()->findAll($criteria);
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($isus) > 0 ){
				$this->renderPartial('partial/index_ajax',array(
						'isus' => $isus,
						'pages' => $pages,
						'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
					'isus' => $isus,
					'pages' => $pages,
					'next_page' => $next_page,
					'my_isu' => Notes::model()->exists('user_id = :user_id AND status = 1', array(':user_id' => Yii::app()->user->id)),
			));
		}
		
	}
	
	public function actionMy($page = null){

		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('user_id = :user_id');
		$criteria->addCondition('status = 1');
		$criteria->params = array(':user_id' => Yii::app()->user->id);
		$criteria->order = 'id';
		
		$total = Notes::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['isu_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$isus = Notes::model()->findAll($criteria);
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($isus) > 0 ){
				$this->renderPartial('partial/index_ajax',array(
						'isus' => $isus,
						'pages' => $pages,
						'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
					'isus' => $isus,
					'pages' => $pages,
					'next_page' => $next_page,
			));
		}
		
	}
	
	public function actionDelete($id){
		if(isset($id)){
			$model =	$this->loadModel($id);
			if(Yii::app()->request->isAjaxRequest && isset(Yii::app()->user->id) && Yii::app()->user->id == $model->user_id)
			{
				$model->deletePermanentlyPhoto();
				$model->delete();
				echo json_encode(array('status'=>true));
				Yii::app()->end();
			}
		}
	
	}
	
	
	public function actionEdit($id){
		if(isset($id)){
			$model =	$this->loadModel($id);
			if(isset($_POST['Notes']) && isset(Yii::app()->user->id))
			{
				$model->attributes=$_POST['Notes'];
				$model->date = strtotime($model->date);
				$model->end_date = strtotime($model->end_date);
				$model->status = Notes::STATUS_PENDING;
				$model->modify = time();
				$model->validate();
				
				if(!$model->hasErrors()){
					$model->save();
					$my_isus =    $model->getMyISU();
					$after_save = $this->renderPartial('partial/load', array('isu' => $model, 'my_isus' => $my_isus), true);
					echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//isu/load', array('id' => $model->id)),  'after_save' => $after_save));
					Yii::app()->end();
				}else{
					echo json_encode($model->errors);
					Yii::app()->end();
				}
				
			} 
			
			
			Yii::app()->clientScript
			->registerCoreScript( 'jquery' )
			->registerScriptFile( Yii::app()->theme->baseUrl . '/resources/html/js/jquery.simple-dtpicker.js' )
			->registerCssFile( Yii::app()->theme->baseUrl . '/resources/html/css/jquery.simple-dtpicker.css' );
			$this->render('partial/form', array('model' => $model));
		}
	
	}
	
	public function actionLoad($id){
		if(isset($id)){
			$model = $this->loadModel($id); 
			if($model){
				$my_isus =    $model->getISU($model->country_id, null/* $model->city_id */, Notes::FIRST_PAGE);
				if(Yii::app()->request->isAjaxRequest){
					$this->renderPartial('partial/load', array('isu' => $model, 'my_isus' => $my_isus));
				} else{
					$this->render('partial/load', array('isu' => $model, 'my_isus' => $my_isus));
				}
			}
		}
		
	}
	
	public function actionCreate(){
		$model = new Notes();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Notes']) && isset(Yii::app()->user->id))
		{
			$model->attributes=$_POST['Notes'];
			$model->user_id = Yii::app()->user->id;
			
			$model->date = strtotime($model->date);
			$model->end_date = strtotime($model->end_date);
			
			$model->status = Notes::STATUS_ACTIVE; 
			$model->created = time(); 
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				$my_isus =    $model->getMyISU();
				$after_save = $this->renderPartial('partial/load', array('isu' => $model, 'my_isus' => $my_isus), true);
				echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//isu/load', array('id' => $model->id)),  'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
			
		}
		Yii::app()->clientScript
		->registerCoreScript( 'jquery' )
		->registerScriptFile( Yii::app()->theme->baseUrl . '/resources/html/js/jquery.simple-dtpicker.js' )
		->registerCssFile( Yii::app()->theme->baseUrl . '/resources/html/css/jquery.simple-dtpicker.css' );
		$this->render('partial/form', array('model' => $model));
	}
	
	public function actionUpload($id = null){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
				
			$id = isset($id) ? $id : 0;
			
			$params 	= CParams::load();
			$thumb300x0 = $params->params->uploads->isu->thumb300x0;
			$detail600x0 = $params->params->uploads->isu->detail600x0;
			$origin 	= $params->params->uploads->isu->origin;
				
			$thumb300x0_folder = $this->setPath ( $thumb300x0->p );
			$detail600x0_folder = $this->setPath ( $detail600x0->p );
			$origin_folder = $this->setPath ( $origin->p );
			$path_folder = $this->setPath ( $params->params->uploads->isu->path, false );
				
				
				
			Yii::import("backend.extensions.EFineUploader.qqFileUploader");
			$uploader = new qqFileUploader();
			$uploader->allowedExtensions = array('jpg','jpeg','png');
			$uploader->sizeLimit = $params->params->uploads->isu->size;//maximum file size in bytes
			$uploader->chunksFolder = $origin_folder;
				
			$result = $uploader->handleUpload($origin_folder);
			$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
			$resize_large_img = false;
			list ( $width, $height ) = getimagesize ( $origin_uploaded_path );
				
				
			if ($width < $thumb300x0->w || $height < $thumb300x0->h) {
				$result ['success'] = false;
				$result ['error'] = "Please choose a image file with minimum weight size is {$thumb300x0->w}px and minimum height size is {$thumb300x0->h}px";
			} else {
	
				Yii::import("backend.extensions.image.Image");
	
				if ($width > $height) {
					$resize_type = Image::HEIGHT;
				} else {
					$resize_type = Image::WIDTH;
				}
	
				if (isset ( $result ['success'] )) {
	
					// begin resize and crop for thumbnail
					//Yii::app()->image->load($origin_uploaded_path)->resize($thumb300x0->w, $thumb300x0->h, $resize_type)->crop ( $thumb300x0->w, $thumb300x0->h )->save($thumb300x0_folder.DS.$uploader->getUploadName());
					Yii::app()->image->load($origin_uploaded_path)->resize($thumb300x0->w, $thumb300x0->h)->crop ( $thumb300x0->w, $thumb300x0->h )->save($thumb300x0_folder.DS.$uploader->getUploadName());
						
	
					// resize for detail (width 600)
					Yii::app ()->image->load ( $origin_uploaded_path )->resize ( $detail600x0->w, $detail600x0->w, Image::WIDTH )->save ( $detail600x0_folder . DS . $uploader->getUploadName () );
						
				}
			}
				
				
				
			$result['filename'] = $uploader->getUploadName();
			$result['filepath'] = $path_folder;
			$result['host'] = Yii::app()->createAbsoluteUrl('/');
			header("Content-Type: text/plain");
			$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			echo $result;
			Yii::app()->end();
		}
	
	}
	
	
	public function actionRemoveImg(){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
			if(isset($_POST['name']) && isset($_POST['path'])){
				$this->deletePermanentlyPhoto($_POST['name'], $_POST['path']);
				Yii::app()->end();
			}
			
		}
		
	}
	
	public function deletePermanentlyPhoto($name, $path){
		if(isset($name) && isset($path)){
	
			if(file_exists($path.DS.'thumb300x0'.DS.$name)){
				@unlink($path.DS.'thumb300x0'.DS.$name);
			}
			if(file_exists($path.DS.'detail600x0'.DS.$name)){
				@unlink($path.DS.'detail600x0'.DS.$name);
			}
			if(file_exists($path.DS.'origin'.DS.$name)){
				@unlink($path.DS.'origin'.DS.$name);
			}
			
		}
	
	}
	
	public function actionSend($type = false, $isu_id = false)
	{
		if (Yii::app()->request->isAjaxRequest){
			$post = Yii::app()->request->getPost('MessageForm');
			$model = new MessageForm();
			$model->subject = substr($post['body'], 0, 100);
			
			if($type == 'forward' && isset($isu_id)){
				$detail = new Notes();
				$detail = $detail->findByPk($isu_id); 
				$link = CHtml::link($detail->title, Yii::app()->createUrl('isu/load', array('id' => $detail->id)))."\n";
				$post['body'] = $link."\n".$post['body'];
			}
			$model->attributes = $post;
			$model->validate();
			if(!$model->hasErrors()){
				$model->send();
				echo json_encode(array('status'=>true));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Notes::model()->findByPk($id);
		if(isset($model)){
			if($model->status == Notes::STATUS_PENDING){
				if($model->user_id != Yii::app()->user->id){
					throw new CHttpException(404,'The requested page does not exist.');
				}
			
			}
		}
		
		if($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	
    public function beforeAction($action) {
    	if( parent::beforeAction($action) ) {
    		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/isu/isu.js?t='.time(), CClientScript::POS_END);
    		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/imagesloaded.pkgd.js', CClientScript::POS_END);
    		return true;
    	}
    	return false;
    }
    
    private function setPath($path, $full = true) {
    	if (isset ( $path )) {
    		$path = str_replace ( '{year}', date ( 'Y' ), $path );
    		$path = str_replace ( '{month}', date ( 'm' ), $path );
    		$path = str_replace ( '{day}', date ( 'd' ), $path );
    		$folder = ($full) ? Yii::app()->params['upload_path'] . DS . $path : $path;
    		if (VHelper::checkDir ( $folder )) {
    			return $folder;
    		} else {
    			return false;
    		}
    	} else {
    		return false;
    	}
    }
    
    public function actionGetCityList() {
    	$list_city = array();
    	try {
    		$country_id = Yii::app()->request->getParam('country_id', 0);
    		$stage = new StateonCache();
    		$stages = $stage->getlistStateinCountry($country_id);
    		$list_city = $stages; 
    		/* $city_in_cache = new CityonCache();
    		$country_id = Yii::app()->request->getParam('country_id', 0);
    		$list_city = $city_in_cache->getlistCityinCountry($country_id); */
    		if (!$list_city) {
    			$list_city = array();
    		}
    	} catch (Exception $e) {
    
    	}
    
    	$this->renderPartial('partial/list_city', array(
    			'list_city' => $list_city
    	));
    }
    public function actionGetDistrictList() {
    	$list_district = array();
    	try {
    		$city_id = Yii::app()->request->getParam('city_id', 0);
    		
    		$district = new CityonCache();
    		$districts = $district->getlistCityinState($city_id);
    		$list_district = $districts; 
    		if (!$list_district) {
    			$list_district = array();
    		}
    	} catch (Exception $e) {
    
    	}
    
    	$this->renderPartial('partial/list_district', array(
    			'list_district' => $list_district
    	));
    }
    
    
}