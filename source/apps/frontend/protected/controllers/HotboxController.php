<?php
/*
 *Nam Le 
 *nam@dwm.vn
 *HotBoxController
 */

class HotboxController extends Controller {
	
	public $clsAccNotActived;
	public $total_upload;
	
	public function actionIndex($type = null, $page = null){
		$page = (isset($page)) ? $page : 1;
		if(isset($type)){
			$new_type = Hotbox::Type2Id($type);
			if($new_type != 'me'){
				$type_condition = ($new_type > 0 ) ? 'AND type = '.$new_type : '';
			} else {
				$me_condition = ($new_type == 'me' && !Yii::app()->user->isGuest) ? 'AND author_id = :author_id':  null;
			}
		}
		
		$criteria=new CDbCriteria;
		if(isset($me_condition)){
			$criteria->addCondition('public_time <= :time '.$me_condition);
			$criteria->params = array(':time' => time(), ':author_id' => Yii::app()->user->id);
		} elseif(isset($type_condition)) {
			$criteria->addCondition('public_time <= :time AND status = :status '.$type_condition);
			$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE);
		} else {
			$criteria->addCondition('public_time <= :time AND status = :status');
			$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE);
		}
		$criteria->order = 't.id DESC';
		$total = Hotbox::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['hotbox_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$hotboxs = Hotbox::model()->findAll($criteria);
		if(Yii::app()->request->isAjaxRequest){
			if(count($hotboxs) > 0 ){
				$this->renderPartial('partial/items',array(
					'hotboxs' => $hotboxs,
					'pages' => $pages,
					'total_hotboxs' => $total,
					'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
				'hotboxs' => $hotboxs,
				'pages' => $pages,
				'total_hotboxs' => $total,
				'next_page' => $next_page,
			));
		}
	}
	
	public function actionSearch($keyword, $page = null){
		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('public_time <= :time AND status = :status');
		$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE);
		$criteria->addSearchCondition('t.title', $keyword);
		//$criteria->addSearchCondition('t.body', $keyword);
		$criteria->order = 't.id DESC';
		$total = Hotbox::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['hotbox_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$hotboxs = Hotbox::model()->findAll($criteria);
		
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($hotboxs) > 0 ){
				$this->renderPartial('partial/index_ajax',array(
						'hotboxs' => $hotboxs,
						'pages' => $pages,
						'total_hotboxs' => $total,
						'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
					'hotboxs' => $hotboxs,
					'pages' => $pages,
					'total_hotboxs' => $total,
					'next_page' => $next_page,
			));
		}
		
	}
	
	public function actionLoad($id){
		if(isset($id)){
			$hotbox = $this->loadModel($id);
			$my_hotboxs = $hotbox->getNewHotbox();
			Comment::model()->page_size = Yii::app()->params->page_limit['hotbox_comment_limit'];
			$list_comment = Comment::model()->getComments($id, Comment::COMMENT_HOTBOX, 1);
			$is_like = Like::model()->isLike($id, Comment::COMMENT_HOTBOX, Yii::app()->user->id);
			if(Yii::app()->request->isAjaxRequest){
				$this->renderPartial('partial/detail', array('hotbox' => $hotbox, 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like));
			} else {
				$page = 1;
				$criteria=new CDbCriteria;
				$criteria->addCondition('public_time <= :time AND status = :status');
				$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE);
				$criteria->order = 't.id DESC';
				$total = Hotbox::model()->count($criteria);
				$pages = new CPagination($total);
				$pages->pageSize = Yii::app()->params->page_limit['hotbox_limit'];
				$pages->applyLimit($criteria);
				$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
				$hotboxs = Hotbox::model()->findAll($criteria);
				$detail = $this->renderPartial('partial/detail', array('hotbox' => $hotbox, 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like), true);
				$this->render('page/index', array(
					'hotboxs' => $hotboxs,
					'pages' => $pages,
					'total_hotboxs' => $total,
					'next_page' => $next_page,
					'detail' => $detail,
				));
			}
		}
	}
	
	
	public function actionCreate(){
		if(Yii::app()->user->isGuest){
			Yii::app()->user->setFlash('msgLogin', Lang::t('login', 'Please sign-in to use this feature!'));
			$this->redirect(Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl('hotbox'))));
			throw new CHttpException(403, 'You must login !');
		}
		$model= new HotboxForm;
		$country_in_cache = new CountryonCache();
		$state_in_cache = new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache  =   new DistrictonCache();
			
			
		$list_city = array();
		$list_district =   array();
		$list_state = array();
		
		if (Yii::app()->request->isAjaxRequest && isset($_POST['HotboxForm'])){
			$model->attributes=$_POST['HotboxForm'];
			$model->author_id = Yii::app()->user->id;
			$model->status = Hotbox::PENDING;
			$model->public_time = time();
			$model->created = time();
			if($model->type == Hotbox::EVENT){
				$model->country_id = $_POST['HotboxForm']['country_id'];
				$model->state_id = $_POST['HotboxForm']['state_id'];
				$model->city_id = $_POST['HotboxForm']['city_id'];
				$model->district_id = $_POST['HotboxForm']['district_id'];
				$model->start = $_POST['HotboxForm']['start'];
				$model->end = $_POST['HotboxForm']['end'];
				$model->setscenario('event');
			}
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				$my_hotboxs =    $model->getMyHotbox();
				$list_comment = Comment::model()->getComments($model->id, Comment::COMMENT_HOTBOX, 1);
				$after_save = $this->renderPartial('partial/detail', array('hotbox' => $model, 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => null), true);
				
				echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//hotbox/load', array('id' => $model->id)),  'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		} else {
			$list_country = $country_in_cache->getListCountry();
			if ($list_country) {
				if (!$model->country_id) {
					$current_country = $country_in_cache->getCurrentCountry();
					$model->country_id	=	$current_country['id'];
				}
				//get state list
				$list_state	=	$state_in_cache->getlistStateinCountry($model->country_id);
					
				if($list_state){
					//get city list
					if(!$model->state_id){
						$first_state	=	current($list_state);
						$model->state_id	=	$first_state['id'];
					}
					$list_city	=	$city_in_cache->getlistCityinState($model->state_id);
					if($list_city){
						//get district list
						if(!$model->city_id){
							$first_city	=	current($list_city);
							$model->city_id	=	$first_city['id'];
						}
						$list_district	=	$district_in_cache->getlistDistrictinCity($model->city_id);
						if(!$list_district){
							$list_district	=	array();
						}
						if(!$model->district_id){
							$first_district	=	current($list_district);
							$model->district_id	=	$first_district['id'];
						}
					}else{
						$list_city	=	array();
					}
				}else{
					$list_state	=	array();
				}
			
			}
		}
		$this->renderPartial('partial/choose_type',array(
			'model'=>$model,
			'list_country' => $country_in_cache->getListCountry(),
			'list_city' => ($list_city ? $list_city : array()),
			'list_state' => ($list_state ? $list_state : array()),
			'list_district' => ($list_district ? $list_district : array()),
		), false, true);
	}
	
	public function actionEdit($id){
		$model= $this->loadModel($id);
		$country_in_cache = new CountryonCache();
		$state_in_cache = new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache  =   new DistrictonCache();
			
			
		$list_city = array();
		$list_district = array();
		$list_state = array();
		
		if (Yii::app()->request->isAjaxRequest && isset($_POST['Hotbox'])){
			
			$model->attributes=$_POST['Hotbox'];
			if(isset($_POST['HotboxForm'])){
				$model->tmp_images = $_POST['HotboxForm'];
			}
			if(isset($_POST['HotboxForm']['thumbnail_id'])){
				$model->thumbnail_id = $_POST['HotboxForm']['thumbnail_id'];
			}
			if($model->type == Hotbox::EVENT){
				$model->country_id = $_POST['HotboxForm']['country_id'];
				$model->state_id = $_POST['HotboxForm']['state_id'];
				$model->city_id = $_POST['HotboxForm']['city_id'];
				$model->district_id = $_POST['HotboxForm']['district_id'];
				$model->start = $_POST['Hotbox']['start'];
				$model->end = $_POST['Hotbox']['end'];
				$model->setscenario('event');
			}
			
			$model->modify = time();
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				$my_hotboxs =    $model->getMyHotbox();
				Comment::model()->page_size = Yii::app()->params->page_limit['hotbox_comment_limit'];
				$list_comment = Comment::model()->getComments($id, Comment::COMMENT_HOTBOX, 1);
				$is_like = Like::model()->isLike($id, Comment::COMMENT_HOTBOX, Yii::app()->user->id);
				
				$hotbox = $this->loadModel($model->id);
				
				$after_save = $this->renderPartial('partial/detail', array('hotbox' => $hotbox , 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like), true);
				echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//hotbox/load', array('id' => $model->id)),  'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		} else {
			$list_country = $country_in_cache->getListCountry();
			if ($list_country) {
				if (!$model->country_id) {
					$current_country = $country_in_cache->getCurrentCountry();
					$model->country_id	=	$current_country['id'];
				}
				//get state list
				$list_state	=	$state_in_cache->getlistStateinCountry($model->country_id);
					
				if($list_state){
					//get city list
					if(!$model->state_id){
						$first_state	=	current($list_state);
						$model->state_id	=	$first_state['id'];
					}
					$list_city	=	$city_in_cache->getlistCityinState($model->state_id);
					if($list_city){
						//get district list
						if(!$model->city_id){
							$first_city	=	current($list_city);
							$model->city_id	=	$first_city['id'];
						}
						$list_district	=	$district_in_cache->getlistDistrictinCity($model->city_id);
						if(!$list_district){
							$list_district	=	array();
						}
						if(!$model->district_id){
							$first_district	=	current($list_district);
							$model->district_id	=	$first_district['id'];
						}
					}else{
						$list_city	=	array();
					}
				}else{
					$list_state	=	array();
				}
					
			}
			$this->renderPartial('partial/create_hotbox',array(
				'model'=>$model,
				'list_country' => $country_in_cache->getListCountry(),
				'list_city' => ($list_city ? $list_city : array()),
				'list_state' => ($list_state ? $list_state : array()),
				'list_district' => ($list_district ? $list_district : array()),
			), false, true);
		}
	}
	
	public function actionDeleteImage($id){
		if(Yii::app()->request->isAjaxRequest){
			$model = HotboxPhoto::model()->findByAttributes(array('id' => $id));
			if($model){
				$model->deletePermanentlyPhoto();
			}
		}
	}
	
	public function actionRemove($id){
		if(Yii::app()->request->isAjaxRequest){
			$model = $this->loadModel($id);
			if($model->delete()){
				foreach ($model->images as $image){
					$image->deletePermanentlyPhoto();
				}
				echo json_encode(array('status'=>true));
				Yii::app()->end();
			}
		}
	}
	
		
	/**
	 * Upload File Controller
	 * Nam Le
	 * nam@dwm.vn
	 */
	public function actionUpload($id = null){ 
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
			
			$id = isset($id) ? $id : 0;
			if($id > 0){
				if(HotboxPhoto::model()->count('hotbox_id = :hotbox_id', array(':hotbox_id' => $id)) == 30){
					$result = array('error' => 'Too many items would be uploaded.  Item limit is 30.');	
					$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
					echo $result;
					Yii::app()->end();
				}
			}
			
			//upload photo hotbox
			$params 	= CParams::load();
			Yii::import ( "backend.extensions.plunUploader.upload");
			$uploader = new upload($params->params->uploads->upload_method);
						
			
			$thumb300x0 = $params->params->uploads->hotbox->thumb300x0;
			$detail600x0 = $params->params->uploads->hotbox->detail600x0;
			$origin 	= $params->params->uploads->hotbox->origin;
			$path_folder = $uploader->setPath ( $params->params->uploads->hotbox->path, false );
			
			
				
			$thumb300x0_folder = $uploader->setPath ( $thumb300x0->p , false);
			$detail600x0_folder = $uploader->setPath ( $detail600x0->p , false);
			$origin_folder = $uploader->setPath ( $origin->p , false);
			
			$uploader->allowedExtensions = array (
					'jpg',
					'jpeg',
					'png'
			);
			$uploader->inputName	=	'qqfile';
			$uploader->sizeLimit = $params->params->uploads->hotbox->size;//maximum file size in bytes
			
			$result = $uploader->upload ( $origin_folder );
			$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
			//end upload photo hotbox
			
			//load images resource
						
			if ($result ['success']) {
				
				$uploader->loadImageResource($origin_uploaded_path);
				// begin resize and crop for thumbnail
				$uploader->resizeImage($thumb300x0->w, $thumb300x0->w, Image::WIDTH);
				//$uploader->crop($thumb300x0->w, $thumb300x0->h, 'top');
				$uploader->saveImg($thumb300x0_folder . DS . $uploader->getUploadName ());
				$uploader->sharpen(20);
				// resize for thumbnail (width 200)
				$uploader->resizeImage($detail600x0->w, $detail600x0->w, true);
				$uploader->saveImg($detail600x0_folder . DS . $uploader->getUploadName ());
					
				$uploader->detroyResource();
					
				//save to database
				$image = new HotboxPhoto();
				$image->hotbox_id = $id;
				$image->title = $uploader->getName();
				$image->name = $uploader->getUploadName();
				$image->path = ltrim($path_folder,'\/');
				$image->status = 0;
				$image->sort = 0;
				$image->created = time();
				$image->save();
				$result['image_id'] = $image->id;
					
				if($id > 0){
					if(HotboxPhoto::model()->count('hotbox_id = :hotbox_id', array(':hotbox_id' => $id)) > 30){
						$image->deletePermanentlyPhoto();
						$result = array('error' => Lang::t('hotbox', 'Too many items would be uploaded.  Item limit is 30.'))	;
						$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
						echo $result;
						Yii::app()->end();
					}
				}				
			}						
			
			$result['filename'] = $uploader->getUploadName();
			$result['filepath'] = ltrim($path_folder,'\/');
			$result['host'] = Yii::app()->createAbsoluteUrl('/');
			header("Content-Type: text/plain");
			$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			echo $result;
			Yii::app()->end();
		}
		
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Hotbox the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		
		/* $criteria	=	new CDbCriteria;
		$criteria->addCondition('t.id = :id');
		$criteria->params = array(':id' => $id);
		$criteria->with = array(
				'like' => array(
						'alias'		=> 'l',
						'joinType'=>'LEFT JOIN',
						'together'=>false,
						'condition' => 'l.type_id = :type_id',
						'params'  => array('type_id' => Like::LIKE_HOTBOX)
				),
				'comment' => array(
						'alias'		=> 'cm',
						'joinType'=>'LEFT JOIN',
						'together'=>false,
						'condition' => 'cm.type_id = :type_id',
						'params'  => array('type_id' => Comment::COMMENT_HOTBOX)
				),
		);
		$model=	HotboxForm::model()->find($criteria); */
		$model = HotboxForm::model()->findByPk($id);
		if(isset($model->events)){
			$model->tmp_event_title = $model->events->title;
			$model->start = date('d-m-Y h:i',$model->events->start);
			$model->end = date('d-m-Y h:i',$model->events->end);
			
			$model->country_id = $model->events->country_id;
			$model->state_id = $model->events->state_id;
			$model->city_id = $model->events->city_id;
			$model->district_id = $model->events->district_id;
		}
		
		if($model->status == Hotbox::PENDING){
			if($model->author_id != Yii::app()->user->id){
				throw new CHttpException(404,'The requested page does not exist.');
			}
			
		}
		
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
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
	
	
	public function actionListComment($page = null){
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if(isset($_POST['id'])){
				$id = $_POST['id'];
				$page = (isset($page)) ? $page : 1;
				Comment::model()->page_size = Yii::app()->params->page_limit['hotbox_comment_limit'];
				$list_comment = Comment::model()->getComments($id, Comment::COMMENT_HOTBOX, $page);
				
				$after_save = $this->renderPartial('partial/comment', array('comments' => $list_comment), true);
				echo json_encode(array('pages'=>$list_comment['pages']->pageCount, 'status'=>true,'after_save' => $after_save));
				Yii::app()->end();
			}
		}
		
	}
	
	//Like controller
	public function actionLike(){
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest && !Yii::app()->user->isGuest) {
			if (isset ( $_POST ['id'] ) && isset ( $_POST ['type'] )) {
				$type = ($_POST ['type']  == 'comment') ? Like::LIKE_COMMENT : Like::LIKE_HOTBOX;
				$id = intval($_POST ['id']);
				$is_like = Like::model()->isLike($id, $type, Yii::app()->user->id);
				if($is_like != false){
					if($is_like->delete()){
						$like_count = $is_like->getCount($id, $type);
						echo json_encode(array('status' => true, 'like_count' => $like_count, 'text'=> Lang::t('hotbox', 'Like')));
						Yii::app()->end();
					}
				} else {
					$model = new Like();
					$model->item_id = $id;
					$model->like_by = Yii::app()->user->id;
					$model->like_date = time();
					$model->type_id = $type;
					$model->save();
					$like_count = $model->getCount($id, $type);
					echo json_encode(array('status' => true, 'like_count' => $like_count, 'text'=> Lang::t('hotbox', 'Unlike')));
					Yii::app()->end();
				}
			}
		}
	}
	
	public function actionComment(){
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['id'] ) && isset ( $_POST ['content'] )) {
				$content = htmlspecialchars($_POST ['content']);
				$id = intval($_POST ['id']);
				
				$comment = new Comment();
				$comment->content = $content;
				$comment->created_date = time();
				$comment->created_by = Yii::app()->user->id;
				$comment->approved = 1;
				$comment->type_id = Comment::COMMENT_HOTBOX;
				$comment->item_id = $id;
				if($comment->save()){
					$hotbox = $this->loadModel($id);
					
					$comments = array('data' => array($comment));
					
					$after_save = $this->renderPartial('partial/comment', array('comments' => $comments), true);
					echo json_encode(array('status'=>true,'after_save' => $after_save, 'comment_count' => $hotbox->stats->comment_count));
					Yii::app()->end();
				}
				
			}
		}
	}
	
	public function actionDeleteComment(){
		if(Yii::app()->request->isAjaxRequest){
			$id = $_POST['id'];
			$model = Comment::model()->find('id = :id AND created_by = :created_by', array('id' => $id, 'created_by' => Yii::app()->user->id));
			if($model->delete()){
				$hotbox = Hotbox::model()->findByPk($model->item_id);
				echo json_encode(array('status'=>true, 'hotbox_id' => $hotbox->id, 'comment_count' =>$hotbox->stats->comment_count));
				Yii::app()->end();
			} 
		}
	}
	
	
}