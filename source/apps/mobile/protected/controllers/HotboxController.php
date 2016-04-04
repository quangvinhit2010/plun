<?php
/*
 *Nam Le 
 *nam@dwm.vn
 *HotBoxController
 */

class HotboxController extends MemberController {
	
	public $total_upload;
	
	public function actionIndex($page = null){
		
		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('public_time <= :time AND status = :status OR (author_id = :author_id AND status != :status)');
		$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE, ':author_id' => Yii::app()->user->id);
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
	
	public function actionSearch($keyword, $page = null){
		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('public_time <= :time AND status = :status OR (author_id = :author_id AND status != :status)');
		$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE, ':author_id' => Yii::app()->user->id);
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
				$this->renderPartial('partial/load', array('hotbox' => $hotbox, 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like));
			} else{
				$cs = Yii::app()->clientScript;
				$cs->registerMetaTag($hotbox->title, null, null, array('property' => 'og:title'));
				if(isset($hotbox->images) && count($hotbox->images) >= 1){
					$image = Yii::app()->createAbsoluteUrl($hotbox->images[0]->getImageThumb(null, true));
					$cs->registerMetaTag($image, null, null, array('property' => 'og:image'));
				}
				$this->render('partial/load', array('hotbox' => $hotbox, 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like));
			}
			
		}
	}
	
	
	public function actionCreate(){
		$model= new HotboxForm;
		if (Yii::app()->request->isAjaxRequest && isset($_POST['HotboxForm'])){
			$model->attributes=$_POST['HotboxForm'];
			$model->author_id = Yii::app()->user->id;
			$model->status = Hotbox::PENDING;
			$model->public_time = time();
			$model->created = time();
			if($model->type == Hotbox::EVENT){
				$model->setscenario('event');
			}
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				$my_hotboxs =    $model->getMyHotbox();
				$after_save = $this->renderPartial('partial/load', array('hotbox' => $model, 'my_hotboxs' => $my_hotboxs, 'comments' => null, 'is_like' => null), true);
				echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//hotbox/load', array('id' => $model->id)),  'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		}
		$this->render('page/form',array(
			'model'=>$model,
		));
	}
	
	public function actionEdit($id){
		$model= $this->loadModel($id);
		if (Yii::app()->request->isAjaxRequest && isset($_POST['Hotbox'])){
			
			$model->attributes=$_POST['Hotbox'];
			if(isset($_POST['HotboxForm'])){
				$model->tmp_images = $_POST['HotboxForm'];
			}
			if(isset($_POST['HotboxForm']['thumbnail_id'])){
				$model->thumbnail_id = $_POST['HotboxForm']['thumbnail_id'];
			}
			if($model->type == Hotbox::EVENT){
				
				/* $model->setscenario('event');
				$model->tmp_event_title = $_POST['Hotbox']['tmp_event_title'];
				$model->tmp_event_start_date = $_POST['Hotbox']['tmp_event_start_date'];
				$model->tmp_event_end_date = $_POST['Hotbox']['tmp_event_end_date']; */
			}
			
			$model->modify = time();
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				$my_hotboxs =    $model->getMyHotbox();
				Comment::model()->page_size = Yii::app()->params->page_limit['hotbox_comment_limit'];
				$list_comment = Comment::model()->getComments($id, Comment::COMMENT_HOTBOX, 1);
				$is_like = Like::model()->isLike($id, Comment::COMMENT_HOTBOX, Yii::app()->user->id);
				$after_save = $this->renderPartial('partial/load', array('hotbox' => $model , 'my_hotboxs' => $my_hotboxs, 'comments' => $list_comment, 'is_like' => $is_like), true);
				echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//hotbox/load', array('id' => $model->id)),  'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		} else {
			$this->render('page/form',array(
				'model'=>$model,
			));
			
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
			$params 	= CParams::load();
			$thumb300x0 = $params->params->uploads->hotbox->thumb300x0;
			$detail600x0 = $params->params->uploads->hotbox->detail600x0;
			$origin 	= $params->params->uploads->hotbox->origin;
			
			$thumb300x0_folder = $this->setPath ( $thumb300x0->p );
			$detail600x0_folder = $this->setPath ( $detail600x0->p );
			$origin_folder = $this->setPath ( $origin->p );
			$path_folder = $this->setPath ( $params->params->uploads->hotbox->path, false );
			
			
			
			Yii::import("backend.extensions.EFineUploader.qqFileUploader");
			$uploader = new qqFileUploader();
			$uploader->allowedExtensions = array('jpg','jpeg','png');
			$uploader->sizeLimit = $params->params->uploads->hotbox->size;//maximum file size in bytes
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
						
					
					//save to database
					$image = new HotboxPhoto();
					$image->hotbox_id = $id;
					$image->title = $uploader->getName();
					$image->name = $uploader->getUploadName();
					$image->path = $path_folder;
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
			$model->tmp_event_start_date = date('Y-m-d',$model->events->start);
			$model->tmp_event_end_date = date('Y-m-d',$model->events->end);
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
	
	public function beforeAction($action) {
		if( parent::beforeAction($action) ) {
			$cs = Yii::app()->clientScript;
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/hotbox/hotbox.js?t='.time(), CClientScript::POS_END);
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/imagesloaded.pkgd.js', CClientScript::POS_END);
			
			
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
	
	
	public function actionListComment($page = null){
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if(isset($_POST['id'])){
				$id = $_POST['id'];
				$page = (isset($page)) ? $page : 1;
				Comment::model()->page_size = Yii::app()->params->page_limit['hotbox_comment_limit'];
				$list_comment = Comment::model()->getComments($id, Comment::COMMENT_HOTBOX, $page);
				
				$after_save = $this->renderPartial('partial/_ajax_item_comment', array('id' => $id, 'comments' => $list_comment), true);
				echo json_encode(array('status'=>true,'after_save' => $after_save));
				Yii::app()->end();
			}
		}
		
	}
	
	//Like controller
	public function actionLike(){
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['id'] ) && isset ( $_POST ['type'] )) {
				$type = ($_POST ['type']  == 'comment') ? Like::LIKE_COMMENT : Like::LIKE_HOTBOX;
				$id = intval($_POST ['id']);
				$is_like = Like::model()->isLike($id, $type, Yii::app()->user->id);
				if($is_like != false){
					if($is_like->delete()){
						$like_count = $is_like->getCount($id, $type);
						echo json_encode(array('status' => true, 'text'=> Lang::t('hotbox', 'Like').'('.$like_count.')'));
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
					echo json_encode(array('status' => true, 'text'=> Lang::t('hotbox', 'Unlike').'('.$like_count.')'));
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
					$after_save = $this->renderPartial('partial/_item_comment', array('comment' => $comment), true);
					echo json_encode(array('status'=>true,'after_save' => $after_save, 'comment_count' => Lang::t('hotbox', 'Comment').'('.$hotbox->stats->comment_count.')'));
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
				echo json_encode(array('status'=>true, 'hotbox_id' => $hotbox->id, 'comment_count' => Lang::t('hotbox', 'Comment').'('.$hotbox->stats->comment_count.')'));
				Yii::app()->end();
			} 
		}
	}
	
	
}