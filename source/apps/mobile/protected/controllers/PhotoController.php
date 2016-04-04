<?php
/*
 * Nam Le nam@dwm.vn Photo Controller
 */
class PhotoController extends MemberController {
	
	public $total_public_upload = 0;
	public $total_vault_upload = 0;
	
	public function actionIndex($type = null, $page = null) {
		
		if ($this->user->isMe ()) {
			$type = (isset($type)) ? $type : Photo::PUBLIC_PHOTO;
			$params = CParams::load ();
			$limit_public_thumbnail = $params->params->uploads->photo->limit_display->public_thumbnail;
			$limit_private_thumbnail = $params->params->uploads->photo->limit_display->private_thumbnail;
			$limit_vault_thumbnail = $params->params->uploads->photo->limit_display->vault_thumbnail;
			$limit_default_thumbnail = $params->params->uploads->photo->limit_display->page;
			
			$user_id = $this->usercurrent->id;
			$public_photos = Photo::model ()->getPhotoByType($user_id, $page, empty($type) ? Photo::PUBLIC_PHOTO : $type , $limit_public_thumbnail);
			$private_photos =  Photo::model ()->getPhotoByType($user_id, $page, empty($type) ? Photo::PRAVITE_PHOTO : $type, $limit_private_thumbnail);
			$vault_photos =  Photo::model ()->getPhotoByType($user_id, $page, empty($type) ? Photo::VAULT_PHOTO : $type, $limit_vault_thumbnail);
			
			if(Yii::app()->request->isAjaxRequest){
				if($type = Photo::VAULT_PHOTO ){
					$limit_default_thumbnail = $limit_vault_thumbnail;
				}
				
				$photos = Photo::model ()->getPhotoByType($user_id, $page, $type , $limit_default_thumbnail);
				if(count($photos) > 0 ){
					$this->renderPartial('partial/my_photo/my_photo_ajax',array(
							'photos' => $photos,
							'is_me'	=>	$this->user->isMe ()
					));
				} else {
					echo 'end';
					Yii::app()->end();
				}
			} else {
				$this->render('page/my_photos', array(
						'type' => $type,
						'public_photos' => $public_photos,
						'private_photos' => $private_photos,
						'vault_photos' => $vault_photos,
						'limit_public_thumbnail' => $limit_public_thumbnail,
						'limit_private_thumbnail' => $limit_private_thumbnail,
						'limit_vault_thumbnail' => $limit_vault_thumbnail,
				));
			}
			
		} else {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		
	}
	public function actionListPhoto($type = null, $page = null) {
		$params = CParams::load ();
		$limit_public_thumbnail = $params->params->uploads->photo->limit_display->public_thumbnail;
		$limit_private_thumbnail = $params->params->uploads->photo->limit_display->private_thumbnail;
		$limit_vault_thumbnail = $params->params->uploads->photo->limit_display->vault_thumbnail;
		$limit_default_thumbnail = $params->params->uploads->photo->limit_display->page;
		
		$user_id = $this->user->id;
		$request_user_id = $this->usercurrent->id;
		// check accept all
		
		$public_photos = Photo::model ()->getPhotoByType($user_id, $page, Photo::PUBLIC_PHOTO, $limit_public_thumbnail);
		$private_photos =  Photo::model ()->getPhotoByType($user_id, $page, Photo::PRAVITE_PHOTO, $limit_private_thumbnail);
		$vault_photos =  Photo::model ()->getPhotoByType($user_id, $page, Photo::VAULT_PHOTO, $limit_vault_thumbnail);
		
		$SysPhotoAcceptall = SysPhotoAcceptall::model ()->find ( "user_id = {$this->user->id} AND request_user_id  = {$this->usercurrent->id}" );
		$accept_all = ( isset($SysPhotoAcceptall) ) ? $SysPhotoAcceptall->id : false;
		
		if(Yii::app()->request->isAjaxRequest){
			if($type == Photo::VAULT_PHOTO){
				$limit = $limit_vault_thumbnail;
			} elseif($type == Photo::PUBLIC_PHOTO) {
				$limit = $limit_public_thumbnail;
			} elseif($type == Photo::PRAVITE_PHOTO) {
				$limit = $limit_private_thumbnail;
			} else{
				$limit = $limit_default_thumbnail;
			}
			$photos = Photo::model ()->getPhotoByType($user_id, $page, $type , $limit);
			if(count($photos) > 0 ){
				$this->renderPartial('partial/your_photo_ajax',array(
						'photos' => $photos,
						'accept_all' => $accept_all,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->renderPartial('page/your_photos', array(
					'public_photos' => $public_photos,
					'private_photos' => $private_photos,
					'vault_photos' => $vault_photos,
					'user' => $this->user,
					'accept_all' => $accept_all,
					'limit_public_thumbnail' => $limit_public_thumbnail,
					'limit_private_thumbnail' => $limit_private_thumbnail,
					'limit_vault_thumbnail' => $limit_vault_thumbnail,
			));
		}
		
	}
	public function actionMyrequest() {
		$limit	=	Yii::app()->params->uploads['photo']['limit_display']['limit_group_day'];
		
		$data	=	SysPhotoRequest::model ()->getPhotoRequest(Yii::app()->user->id, 0, $limit);
		
		$this->render ( 'page/my_photo_requests', array (
				'return' => $data['data'],
				'response_id_json'	=>	$data['response_id_json'],
				'user_id' => Yii::app()->user->id,
				'user' => $this->user,
				'limit'	=>	$limit,
				'total'	=>	$data['total']
		) );
	}
	//photo request show more
	public function actionPhotoRequestShowMore(){
		$offset = Yii::app ()->request->getPost ( 'offset', 0 );
		$limit	=	Yii::app()->params->uploads['photo']['limit_display']['limit_group_day'];
	
		$data	=	SysPhotoRequest::model ()->getPhotoRequest(Yii::app()->user->id, $offset, $limit);

		$this->renderPartial ( 'page/my_photo_requests_showmore', array (
				'return' => $data['data'],
				'response_id_json'	=>	$data['response_id_json'],
				'user_id' => Yii::app()->user->id,
				'user' => $this->user,
		) );
	}
	//photo request show more (end)
	public function actionUpdateReadStatus(){
		$ids = Yii::app ()->request->getPost ( 'ids', false );
		if($ids){
			$ids	=	json_decode($ids, true);
			if(isset($ids[0])){
				$criteria = new CDbCriteria;
				$ids	=	array_map('add_str_queryin', $ids);
				$criteria->addCondition("id IN(" . implode(',', $ids) . ")");
				SysPhotoRequest::model ()->updateAll(array('is_read'=> 3), $criteria);
			}
		}
		exit;
	}
	public function actionDelete() {
		$photo_id = Yii::app ()->request->getPost ( 'photo_id', false );
		$type = Yii::app ()->request->getPost ( 'type', false );
		if ($photo_id) {
			if (Yii::app ()->request->isAjaxRequest && isset ( Yii::app ()->user->id )) {
				$model = Photo::model ()->findByPk ( $photo_id );
				if($model->user_id == Yii::app ()->user->id){
					$model->status = 0;
					$model->save();
					if(isset($model->id)){
						SysPhotoRequest::model()->deleteAll('photo_id = :photo_id', array(':photo_id' => $model->id));
					}
					echo 0;
				}
			}
		}
		Yii::app ()->end ();
	}
	
	// Nam Le
	public function actionUpload($type) {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if($type == Photo::PUBLIC_PHOTO){
				$limit_public_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PUBLIC_PHOTO));
				if($limit_public_upload >= Yii::app()->params['uploads']['photo']['limit_upload']['public']){
					$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Public Photos limit of {limit_photo}.', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['public'])));
					$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
					echo $result;
					Yii::app()->end();
				}
			}
			if($type == Photo::VAULT_PHOTO){
				$limit_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::VAULT_PHOTO));
				if($limit_upload >= Yii::app()->params['uploads']['photo']['limit_upload']['vault']){
					$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Vault Photos limit of {limit_photo}.', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['vault'])));
					$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
					echo $result;
					Yii::app()->end();
				}
			}
			
			$params = CParams::load ();
			$thumb275x275 = $params->params->uploads->photo->thumb275x275;
			$thumb768x1024	=	$params->params->uploads->photo->thumb768x1024;
			$detailw200 = $params->params->uploads->photo->detailw200;
			$detail1600x900 = $params->params->uploads->photo->detail1600x900;
			$origin = $params->params->uploads->photo->origin;
					
			Yii::import ( "backend.extensions.plunUploader.upload");
			$uploader = new upload($params->params->uploads->upload_method);
			$uploader->allowedExtensions = array (
					'jpg',
					'jpeg',
					'png'
			);
			$uploader->inputName	=	'qqfile';
			$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
			
			$thumb275x275_folder = $uploader->setPath ( $thumb275x275->p , false );
			$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
			$detailw200_folder = $uploader->setPath ( $detailw200->p , false );
			$detail1600x900_folder = $uploader->setPath ( $detail1600x900->p, false  );
			$origin_folder = $uploader->setPath ( $origin->p, false );
			$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
			$result = $uploader->upload ( $origin_folder );
			


			/*
			Yii::import ( "backend.extensions.EFineUploader.qqFileUploader" );
			$uploader = new qqFileUploader ();
			$uploader->allowedExtensions = array (
					'jpg',
					'jpeg',
					'png' 
			);
			$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
			$uploader->chunksFolder = $origin_folder;
			
			$result = $uploader->handleUpload ( $origin_folder );
			*/
			
			// get origin path after file upload successfully
			$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
			
			//load images resource
			$uploader->loadImageResource($origin_uploaded_path);
						
			$resize_large_img = false;
							
				if (isset ( $result ['success'] )) {

					// check resize large photo
					
					if ($uploader->img_resource->height > $detail1600x900->h || $uploader->img_resource->width > $detail1600x900->w) {
						$resize_large_img = true;
					}
					
					// begin resize and crop for thumbnail
					$uploader->resizeImage($thumb275x275->w, $thumb275x275->h);
					$uploader->crop($thumb275x275->w, $thumb275x275->h, 'top');
					$uploader->saveImg($thumb275x275_folder . DS . $uploader->getUploadName ());
									
					// resize for thumbnail (width 200)
					$uploader->resizeImage($detailw200->w, $detailw200->w);
					$uploader->saveImg($detailw200_folder . DS . $uploader->getUploadName ());
					
					// resize for thumbnail (width 768×1024)
					$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
					$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());						
					
					if ($resize_large_img) {
						// begin resize and crop for large images
						$uploader->resizeImage($detail1600x900->w, $detail1600x900->h);						
					} else {
						$uploader->resizeImage($uploader->img_resource->width, $uploader->img_resource->height);
					}
					$logoPng = YiiBase::getPathOfAlias('pathroot').'/themes/plun2/resources/html/css/images/logo_in_photo.png';
					$uploader->logo($logoPng);
					
					$uploader->saveImg($detail1600x900_folder . DS . $uploader->getUploadName ());
					
					$uploader->detroyResource();
					
					// save to database
					$model = new Photo ();
					$model->album_id = 0;
					$model->user_id = Yii::app()->user->id;
					$model->title = pathinfo ( $uploader->getUploadName (), PATHINFO_FILENAME );
					$model->name = $uploader->getUploadName ();
					$model->path = ltrim($path_folder,'\/');
					$model->status = 1;
					$model->type = $type;
					$model->created = time ();
					$model->save ();
					
					
					if($type == Photo::VAULT_PHOTO){
						$this->total_vault_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::VAULT_PHOTO));
						if($this->total_vault_upload > Yii::app()->params['uploads']['photo']['limit_upload']['vault']){
							$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Vault Photos limit of {limit_photo}', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['vault'])));
							$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
							$delete = $this->loadModel ($model->id);
							$delete->deletePermanentlyPhoto();
							echo $result;
								
							Yii::app()->end();
						}
					}
					
					/**log activity**/
					if($type == Photo::PUBLIC_PHOTO){
						$this->total_public_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PUBLIC_PHOTO));
						if($this->total_public_upload > Yii::app()->params['uploads']['photo']['limit_upload']['public']){
							$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Public Photos limit of {limit_photo}', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['public'])));
							$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
							$delete = $this->loadModel ($model->id);
							$delete->deletePermanentlyPhoto();
							echo $result;
							
							Yii::app()->end();
						}
						
    					$hour = time();
    					$cri = new CDbCriteria();
    					$cri->addCondition("user_id = :user_id AND action = :action AND (timestamp BETWEEN :from AND :to) AND status = 1 AND group_id = 0");
    					$cri->params = array(':user_id'=>$this->usercurrent->id, ':action'=>Activity::LOG_PHOTO_UPLOAD, ':from'=>strtotime("-1 hours", $hour), ':to'=> $hour );
    					$logExist = Activity::model()->find($cri);
    					$group_id = 0;
    					if(!empty($logExist->id)){
    					    $group_id = $logExist->id;
    					}
    					Activity::model()->log(
        					Activity::LOG_PHOTO_UPLOAD,
        					array(
        					'{user}' => $this->usercurrent->username,
        					'{photo}' => $model->id,
        					'{typePhoto}' => $type,
        					),
        					$this->usercurrent->id,
        					$this->usercurrent->username,
        					$model->id,
        					0,
        					$group_id
    					);
					}
					/**end log**/
					$result ['fileid'] = $model->id;
					
				}
				
			$result ['photoid'] = $model->id;
			$result ['filename'] = $uploader->getUploadName ();
			$result ['filepath'] = ltrim($path_folder,'\/');
			$result ['total'] = Photo::model()->count('user_id = :user_id AND status = 1', array('user_id' => Yii::app()->user->id));
			header ( "Content-Type: text/plain" );
			$result = htmlspecialchars ( json_encode ( $result ), ENT_NOQUOTES );
			echo $result;
			
			Yii::app ()->end ();
		}
	}
	
	// Nam Le
	public function actionDiscard() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['id'] )) {
				$model = $this->loadModel ( $_POST ['id'] );
				$model->deletePermanentlyPhoto();
				Yii::app ()->end ();
			}
		}
	}
	
	// Nam Le
	public function actionDiscardAll() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			
			if (isset ( $_POST ['data'] )) {
				foreach ($_POST['data'] as $value){
					$model = $this->loadModel ( $value['id'] );
					$model->deletePermanentlyPhoto();
				}
				Yii::app ()->end ();
			}
		}
	}
	
	// Nam Le
	public function actionSave() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['id'] )) {
				$model = $this->loadModel ( $_POST ['id'] );
				$model->description = htmlspecialchars($_POST ['caption']);
				$model->updated = time ();
				$model->save ();
				Yii::app ()->end ();
			}
		}
	}
	
	// Nam Le
	public function actionSaveAll() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['data'] )) {
				foreach ($_POST['data'] as $value){
					$model = $this->loadModel ( $value['id'] );
					$model->description = htmlspecialchars($value['caption']);
					$model->updated = time ();
					$model->save ();
				}
				Yii::app ()->end ();
			}
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * 
	 * @param
	 *        	integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model = Photo::model ()->findByPk ( $id );
		if ($model === null)
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		return $model;
	}

	public function actionRequestViewPhoto() {
		$photo_id = Yii::app ()->request->getPost ( 'photo_id', false );
		if ($photo_id) {
			if (Yii::app ()->request->isAjaxRequest && isset ( Yii::app ()->user->id )) {
				$modelPhoto = Photo::model ()->findByPk ( $photo_id );
				if ($modelPhoto->id) {
					// check request
					$modelRequest = SysPhotoRequest::model ()->find ( "request_user_id = {$this->usercurrent->id} AND photo_id = {$photo_id}" );
					if (! isset ( $modelRequest->id )) {
						$SysPhotoRequest = new SysPhotoRequest ();
						$SysPhotoRequest->request_user_id = $this->usercurrent->id;
						$SysPhotoRequest->photo_id = $photo_id;
						$SysPhotoRequest->photo_user_id = $modelPhoto->user_id;
						$SysPhotoRequest->status = SysPhotoRequest::REQUEST_PENDING;
						$SysPhotoRequest->date_request = time ();
						$SysPhotoRequest->save ();
						
						/* $user = Yii::app()->user->data();
						if(empty($user)){
							$to  = $user->profile->email;
						}
						
						
						$subject  = 'Request View Photo';
						$body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/request-private-photos',array('user'=>$user, 'activation_url'=>$user->getActivationUrl()), true);
						$sent = Mailer::send ( $to,  $subject, $body); */
						
						
						echo 1;
						Yii::app ()->end ();
					}
				}
			}
		}
		Yii::app ()->end ();
	}
	public function actionAcceptRequest() {
		$request_id = Yii::app ()->request->getPost ( 'request_id', false );
		if ($request_id) {
			if (Yii::app ()->request->isAjaxRequest && isset ( Yii::app ()->user->id )) {
				$modelRequest = SysPhotoRequest::model ()->find('id = :request_id', array(':request_id' => $request_id));
				$data_update = array (
						'date_accepted' => time(), 
						'status' => SysPhotoRequest::REQUEST_ACCEPTED, 
						'is_read' => 2, 
				);
				$check = SysPhotoRequest::model ()->updateAll ( $data_update, "id={$request_id}" );
				if ($check) {
					echo 1;
				}
			}
		}
		Yii::app ()->end ();
	}
	public function actionDeclineRequest() {
		$request_id = Yii::app ()->request->getPost ( 'request_id', false );
		if ($request_id) {
			if (Yii::app ()->request->isAjaxRequest && isset ( Yii::app ()->user->id )) {
				$modelRequest = SysPhotoRequest::model ()->find('id = :request_id', array(':request_id' => $request_id));
				$data_update = array (
						'date_accepted' => time(),
						'status' => SysPhotoRequest::REQUEST_DECLINED ,
						'is_read' => 2,
				);
				$check = SysPhotoRequest::model ()->updateAll ( $data_update, "id={$request_id}" );
				if ($check) {
					echo 1;
				}
			}
		}
		Yii::app ()->end ();
	}
	public function actionAcceptAllRequest() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['data'] )) {
				foreach ($_POST['data'] as $value){
					$model = SysPhotoRequest::model()->findByPk($value['photo_id']);
					if(isset($model)){
						$model->date_accepted = time();
						$model->status = SysPhotoRequest::REQUEST_ACCEPTED;
						$model->is_read = 2;
						$model->update();
						
					}
				}
				echo 1;
				Yii::app ()->end ();
			}
		}
		
	}
	public function actionDeclineAllRequest() {
		if (Yii::app ()->request->isPostRequest && Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['data'] )) {
				foreach ($_POST['data'] as $value){
					$model = SysPhotoRequest::model()->findByPk($value['photo_id']);
					if(isset($model)){
						$model->date_accepted = time();
						$model->status = SysPhotoRequest::REQUEST_DECLINED;
						$model->is_read = 2;
						$model->update();
						
					}
				}
				echo 1;
				Yii::app ()->end ();
			}
		}
		
	}
	public function actionPhotoMore($alias = null, $type = 1) {
		// check settings - create new settings then it's empty
		if ($this->user->isMe ()) {
			$user_id = $this->usercurrent->id;
		} else {
			$user_id = $this->user->id;
		}
		$model = UsrProfileSettings::model ()->findByAttributes ( array (
				'user_id' => $user_id 
		) );
		if (! $model) {
			// create settings
			$model = new UsrProfileSettings ();
			$model->user_id = $user_id;
			$model->save ();
		}
		// end check settings
		if (Yii::app ()->request->isAjaxRequest)
			$this->renderPartial ( 'partial/photo_more', array () );
		else
			$this->render ( 'page/photo_more', array () );
	}
	public function actionPhotoMoreList($alias, $type) {
		$cs = Yii::app ()->clientScript;
		$params = CParams::load ();
		
		if ($this->user->isMe ()) {
			$user_id = $this->usercurrent->id;
			$view = 'page/photo_more_list_my';
		} else {
			$user_id = $this->user->id;
			$view = 'page/photo_more_list_your';
		}
		
		$cs->registerScriptFile ( Yii::app ()->theme->baseUrl . '/resources/js/photo/photo.js' );
		
		$limit_public_thumbnail = $params->params->uploads->photo->limit_display->public_list;
		$limit_private_thumbnail = $params->params->uploads->photo->limit_display->private_list;
		
		if ($type == Photo::PUBLIC_PHOTO) {
			$photos = Photo::model ()->getPhotoList ( $user_id, $this->usercurrent->id, Photo::PUBLIC_PHOTO, $limit_public_thumbnail );
		} else {
			$photos = Photo::model ()->getPhotoList ( $user_id, $this->usercurrent->id, Photo::PRAVITE_PHOTO, $limit_private_thumbnail );
		}
		
		$accept_all = isset ( $SysPhotoAcceptall->id ) ? $SysPhotoAcceptall->id : false;
		
		$this->renderPartial ( $view, array (
				'photos' => $photos,
				'user_id' => $user_id,
				'user' => $this->user,
				'type' => $type,
				'accept_all' => $accept_all 
		) );
	}
	
	
	public function beforeAction($action) {
		if( parent::beforeAction($action) ) {
			$cs = Yii::app()->clientScript;
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/photo/photo.js?t=' .time(), CClientScript::POS_END);
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/avatar.js?t=' .time(), CClientScript::POS_END);
			return true;
		}
		return false;
	}
	public function actionViewphoto(){
		$params = CParams::load ();
		$limit_public_thumbnail = $params->params->uploads->photo->limit_display->public_thumbnail;
		$limit_private_thumbnail = $params->params->uploads->photo->limit_display->private_list;
		if($this->user->isMe()){
			$is_me	=	true;
			$user_id	=	$this->usercurrent->id;
			$user		=	$this->usercurrent;
		}else{
			$is_me	=	false;
			$user_id	=	$this->user->id;
			$user		=	$this->user;
		}		
		
		$public_photos = Photo::model ()->getPhotoByType($user_id, null, Photo::PUBLIC_PHOTO , $limit_public_thumbnail);
		$vault_photos = Photo::model ()->getPhotoList($user_id, $this->usercurrent->id, Photo::VAULT_PHOTO , $limit_private_thumbnail);
		
		$total_vault_photo	=	Photo::model ()->countPhotoList($user_id, $this->usercurrent->id, Photo::VAULT_PHOTO);
	
		$this->render ('partial/viewphoto', array (
				'user'	=>	$user,
				'is_me'	=>	$is_me,
				'public_photos'	=>	$public_photos,
				'limit_private_thumbnail'	=>	$limit_private_thumbnail,
				'total_vault_photo'	=>	$total_vault_photo,
				'vault_photos'	=>	$vault_photos
		) );
	}
	public function actionViewphotomore(){
		$offset = Yii::app()->request->getParam('offset', 0);
		
		
		$params = CParams::load ();
		$limit_private_thumbnail = $params->params->uploads->photo->limit_display->private_list;
		if($this->user->isMe()){
			$is_me	=	true;
			$user_id	=	$this->usercurrent->id;
			$user		=	$this->usercurrent;
		}else{
			$is_me	=	false;
			$user_id	=	$this->user->id;
			$user		=	$this->user;
		}
		
		$total_vault_photo	=	Photo::model ()->countPhotoList($user_id, $this->usercurrent->id, Photo::VAULT_PHOTO);
		
		$vault_photos = Photo::model ()->getPhotoList($user_id, $this->usercurrent->id, Photo::VAULT_PHOTO , $limit_private_thumbnail, $offset);
				
		$show_more = ($total_vault_photo > ($offset + sizeof($vault_photos))) ? true : false;
		
		$html = $this->renderPartial('partial/viewphotomore', array(
			'is_me'	=>	$is_me,
			'user'	=>	$user,
			'vault_photos'	=>	$vault_photos
		), true);
			
        $array_result_json = array(
            'html' => $html,
        	
        	'show_more'	=>	$show_more
        );
        echo json_encode($array_result_json);
        exit;
	}	
	public function actionSuggestUserSharePhoto(){
		$keyword	=	Yii::app()->request->getParam('q', false);
		$offset = Yii::app()->request->getParam('offset', 0);
			
		//set keyword search
		$search_conditions = array(
				'keyword' => strtolower($keyword),
				'country_id'	=>	0
		);
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		//remove myself
		$user_id_except = array(Yii::app()->user->id);
			
		$elasticsearch	=	new Elasticsearch();
		$sort_script	=	"doc['username'].value.length()";
		$elasticsearch->setSortRules($sort_script, 'asc');
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, 5);
			
		$dbrows	=	array();
		foreach($data_search['fulldata'] AS $row){
			$row	=	$row['_source'];
			$dbrows[]	=	array(
					'id' => $row['user_id'],
					'text' => $row['username']
			);
		}
		echo CJSON::encode($dbrows);
		Yii::app()->end();
	}
	public function actionSharePhotoView(){		
		$photoid	=	Yii::app()->request->getParam('photoid', 0);
				
		$photo_detail = Photo::model()->findByAttributes(array('id' => $photoid));
		if(isset($photo_detail->id)){
			if($photo_detail->user_id != Yii::app()->user->id){
				exit;
			}else{
				$this->render('page/sharephotoview', array(
						'photo_detail'	=>	$photo_detail,
						'photoid'	=>	$photoid,
						'user'	=>	Yii::app()->user->data()	
				));				
			}
		}
	}
	public function actionSharePhoto(){
		$SysPhotoRequest = new SysPhotoRequest ();
	
		$photoid = Yii::app ()->request->getPost ( 'photoid', 0 );
		$userid = Yii::app ()->request->getPost ( 'userid', 0 );
		if(isset(Yii::app()->user->id)){
			if($photorequest	=	$SysPhotoRequest->checkPhotoRequest($userid, $photoid, Yii::app()->user->id)){
				if($photorequest->status == SysPhotoRequest::REQUEST_DECLINED || $photorequest->status == SysPhotoRequest::REQUEST_PENDING){
					SysPhotoRequest::model ()->updateAll(array('date_request' => time(),'status'=> SysPhotoRequest::REQUEST_ACCEPTED, 'is_read' => SysPhotoRequest::IS_SHARE_READ), "id={$photorequest->id}");
					echo '1';
				}else{
					echo '2';
				}
			}else{
				$SysPhotoRequest = new SysPhotoRequest ();
				$SysPhotoRequest->request_user_id	=	$userid;
				$SysPhotoRequest->photo_id	=	$photoid;
				$SysPhotoRequest->photo_user_id	=	Yii::app()->user->id;
				$SysPhotoRequest->status	=	SysPhotoRequest::REQUEST_ACCEPTED;
				$SysPhotoRequest->date_request	=	time();
				$SysPhotoRequest->is_read	=	SysPhotoRequest::IS_SHARE_READ;
				$SysPhotoRequest->save();
				echo '1';
			}
		}else{
			echo '0';
		}
		exit;
	}
	public function actionResendRequest(){
		$photo	=	new Photo();
		$photoid = Yii::app ()->request->getPost ( 'photoid', 0 );
		$userid = Yii::app ()->request->getPost ( 'userid', 0 );
	
		echo $photo->resendRequest($photoid, $userid);
		exit;
	}	
}
if(!function_exists('add_str_queryin')){
	function add_str_queryin($str) {
		if($str != ''){
			return "'{$str}'";
		}
	}
}