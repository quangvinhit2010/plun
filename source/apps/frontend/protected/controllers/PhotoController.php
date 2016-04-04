<?php
/*
 * Nam Le nam@dwm.vn Photo Controller
 */
class PhotoController extends MemberController {
	
	public $total_public_upload = 0;
	public $total_vault_upload = 0;
	public $total_private_upload = 0;
	
	public function actionIndex($type = Photo::PUBLIC_PHOTO, $page = null) {
		if ($this->user->isMe()) {
			$this->render('page/index');
		} else {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
	}
	public function actionListPhotos() {
		$params = CParams::load ();
		$isMe = $this->user->isMe();
		$mappings = array();
		
		if(Yii::app()->request->isAjaxRequest) {
			$page = Yii::app()->request->getParam('page');
			$type = Yii::app()->request->getParam('type');
			$offset = Yii::app()->request->getParam('offset');
			if($type == Photo::PUBLIC_PHOTO) {
				$limit = $params->params->uploads->photo->limit_display->public_thumbnail;
			} else if($type == Photo::VAULT_PHOTO) {
				$limit = $params->params->uploads->photo->limit_display->vault_thumbnail;
			} else {
				$limit = $params->params->uploads->photo->limit_display->private_thumbnail;
			}
			$photos = Photo::model()->getPhotoByTypeExtend($this->user->id, $page, $type, $limit, $offset);
			
			$partialFolder = $this->user->isMe() ? 'my' : 'your';
			
			$this->renderPartial("partial/$partialFolder/".Photo::model()->mapPartial($type), array('photos'=>$photos));
		} else {
			$mappings['public'] = Photo::model()->getPhotoByType($this->user->id, null, Photo::PUBLIC_PHOTO, $params->params->uploads->photo->limit_display->public_thumbnail);
			$mappings['vault'] = Photo::model()->getPhotoByType($this->user->id, null, Photo::VAULT_PHOTO, $params->params->uploads->photo->limit_display->vault_thumbnail);
			if(!empty($params->params->uploads->photo->enable->private)){
				$mappings['private'] = Photo::model()->getPhotoByType($this->user->id, null, Photo::PRAVITE_PHOTO, $params->params->uploads->photo->limit_display->private_thumbnail);
			}
			
			$this->renderPartial('page/list_photos', array(
				'isMe'=>$isMe,
				'mappings'=>$mappings,
			));
		}
	}
	
	public function actionMyrequest() {
		$limit	=	Yii::app()->params->uploads['photo']['limit_display']['limit_group_day'];
		//$return = SysPhotoRequest::model ()->getPhotoRequestResponse ( Yii::app()->user->id, 0, $limit);
		
		$data	=	SysPhotoRequest::model ()->getPhotoRequest(Yii::app()->user->id, 0, $limit);
		
		/*
		$data = array();
		$temp = array();
		foreach ($return as $value){
			//$value->days = mktime($value->days);
			$details = $value->getDetailRequestReponse($value->ids);
			foreach ($details as $detail){
				
				if(Yii::app()->user->id == $detail->request_user_id) {
					if($detail->is_read == 2 || $detail->is_read == 3 && $detail->status != 1){
						$temp[$value->days]['response_tmp'][$detail->status][] = $detail->attributes;
					}
				} elseif(Yii::app()->user->id == $detail->photo_user_id) {
					if($detail->is_read != 3 && $detail->is_read != 2) {
						$temp[$value->days]['request_tmp'][] = $detail->attributes;
					} 
				}
				
				
			}
			if(isset($temp[$value->days]['request_tmp'])){
				foreach ($temp[$value->days]['request_tmp'] as $request){
					$data[$value->days]['request'][$request['request_user_id']][] = $request;
				}
			}
			

			
			if(isset($temp[$value->days]['response_tmp'])){
				foreach ($temp[$value->days]['response_tmp'] as $status => $response){
					foreach ($response as $get_user){
						
						$data[$value->days]['response'][$get_user['status']][$get_user['photo_user_id']][] = $get_user;
						
					}
				}
			}
		}
		krsort($data);
		*/
		
		$this->renderPartial ( 'page/my_photo_requests', array (
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
		DataNodejs::updateTotalPhotorequest(Yii::app()->user->id);
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
					$result = array(
						'error' => '0',
						'after_save'=>'',
					);
					if(isset($model->id)){
						
						SysPhotoRequest::model()->deleteAll('photo_id = :photo_id', array(':photo_id' => $model->id));
						
						//update for user did send request photo
						//get all request
						$criteria=new CDbCriteria;
						$criteria->addCondition("photo_id = $photo_id");
						$criteria->addCondition("status != " . SysPhotoRequest::REQUEST_PENDING);
						$all_request = SysPhotoRequest::model()->findAll($criteria);
						foreach($all_request AS $row){
							DataNodejs::updateTotalPhotorequest($row->request_user_id);
						}
						//end update alert
						
						if(Yii::app ()->request->getPost ( 'load_new', false )) {
							$params = CParams::load();
							if($type == Photo::PUBLIC_PHOTO) {
								$limit = $params->params->uploads->photo->limit_display->public_thumbnail;
							} else if($type == Photo::VAULT_PHOTO) {
								$limit = $params->params->uploads->photo->limit_display->vault_thumbnail;
							} else {
								$limit = $params->params->uploads->photo->limit_display->private_thumbnail;
							}
							$photos = Photo::model()->getPhotoByType($this->usercurrent->id, null, $type, $limit);
							$result['after_save'] = $this->renderPartial('partial/my/'.Photo::model()->mapPartial($type), array('photos'=>$photos), true);
						}
					}
					echo json_encode($result);
				}
			}
		}
		DataNodejs::updateTotalPhotorequest(Yii::app()->user->id);
		Yii::app ()->end ();
	}
	
	// Nam Le
	public function actionUpload() {
		
		$type = $_REQUEST['type'];
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
			if($type == Photo::PRAVITE_PHOTO){
				$limit_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PRAVITE_PHOTO));
				if($limit_upload >= Yii::app()->params['uploads']['photo']['limit_upload']['private']){
					$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Private Photos limit of {limit_photo}.', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['private'])));
					$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
					echo $result;
					Yii::app()->end();
				}
			}
			
			$params = CParams::load ();
			$thumb275x275 = $params->params->uploads->photo->thumb275x275;
			$thumbwatermark = $params->params->uploads->photo->thumbwatermark;
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
			$thumbwatermark_folder = $uploader->setPath ( $thumbwatermark->p , false );
			$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
			$detailw200_folder = $uploader->setPath ( $detailw200->p , false );
			$detail1600x900_folder = $uploader->setPath ( $detail1600x900->p, false  );
			$origin_folder = $uploader->setPath ( $origin->p, false );
			$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
			$result = $uploader->upload ( $origin_folder );
	
	
							
			if ($result ['success'] ) {
				
					// get origin path after file upload successfully
					$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
						
					//load images resource
					$uploader->loadImageResource($origin_uploaded_path);
					
					$resize_large_img = false;
					
					// check resize large photo
					
					if ($uploader->img_resource->height > $detail1600x900->h || $uploader->img_resource->width > $detail1600x900->w) {
						$resize_large_img = true;
					}
					
					// resize & crop for thumbnail (width 200)
					$uploader->resizeImage($thumb275x275->w, $thumb275x275->h);
					$uploader->sharpen(20);
					$uploader->crop($thumb275x275->w, $thumb275x275->h, 'top');
					$uploader->saveImg($thumb275x275_folder . DS . $uploader->getUploadName ());
					
					if($type == Photo::PRAVITE_PHOTO){
						$waterMarkPng = YiiBase::getPathOfAlias('pathroot').'/themes/plun2/resources/html/css/images/mask.png';
						$uploader->resizeImage($thumbwatermark->w, $thumbwatermark->h);
						$uploader->crop($thumbwatermark->w, $thumbwatermark->h, 'top');
						$uploader->watermark($waterMarkPng);
						$uploader->saveImg($thumbwatermark_folder . DS . $uploader->getUploadName ());
					}
									
					// resize for thumbnail (width 200)
					$uploader->resizeImage($detailw200->w, $detailw200->w);

					$uploader->saveImg($detailw200_folder . DS . $uploader->getUploadName ());
					
					// resize for thumbnail (width 768×1024)
					$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
					
		
					
					$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());					
					
					if ($resize_large_img) {
						// begin resize and crop for large images
						$uploader->resizeImage($detail1600x900->w, $detail1600x900->h, true);						
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
					
					if($type == Photo::PRAVITE_PHOTO){
						$photoPrivate = new SysPhotoPrivate();
						$photoPrivate->photo_id = $model->id;
						$photoPrivate->save();
					}
					
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
					
					if($type == Photo::PRAVITE_PHOTO){
						$this->total_private_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PRAVITE_PHOTO));
						if($this->total_private_upload > Yii::app()->params['uploads']['photo']['limit_upload']['private']){
							$result = array('error' => Lang::t("photo", 'Sorry! You have exceeded your Private Photos limit of {limit_photo}.', array('{limit_photo}'=>Yii::app()->params['uploads']['photo']['limit_upload']['private'])));
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
					
					$result ['photoid'] = $model->id;
					$result ['filename'] = $uploader->getUploadName ();
					$result ['filepath'] = ltrim($path_folder,'\/');
					$result ['total'] = Photo::model()->count('user_id = :user_id AND status = 1', array('user_id' => Yii::app()->user->id));
						
					$result['after_save'] = $this->renderPartial('partial/my/'.Photo::model()->mapPartial($type), array('photos'=>array('data'=>array($model))), true);
						
					if($type == Photo::PUBLIC_PHOTO) {
						$result['limit'] = $params->params->uploads->photo->limit_display->public_thumbnail;
					} else if($type == Photo::VAULT_PHOTO) {
						$result['limit'] = $params->params->uploads->photo->limit_display->vault_thumbnail;
					} else if($type == Photo::PRAVITE_PHOTO) {
						$result['limit'] = $params->params->uploads->photo->limit_display->private_thumbnail;
					}
			}
							
			echo json_encode ( $result );
			
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
						
						DataNodejs::updateTotalPhotorequest($modelPhoto->user_id);
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
	public function actionRequestViewPrivatePhoto() {
		$photo_id = Yii::app ()->request->getPost ( 'photo_id', false );
		if ($photo_id) {
			if (Yii::app ()->request->isAjaxRequest && isset ( Yii::app ()->user->id )) {
				$modelPhoto = Photo::model ()->findByPk ( $photo_id );
				if ($modelPhoto->id) {
					// check request
					$modelRequest = SysPhotoRequest::model ()->find ( "request_user_id = {$this->usercurrent->id} AND photo_id = {$photo_id}" );
					if (! isset ( $modelRequest->id )) {
						
						$balance = new Balance();
						if(!$balance->transfer(Yii::app ()->user->id, $modelPhoto->user_id, $photo_id, $balance::TYPE_PRIVATE_PHOTO, $modelPhoto->photo_private->getPrice(), NULL)) {
							echo json_encode(array(
									'success' => '0',
									'error' => $balance->getErrorCode(),
							));
							Yii::app ()->end ();
						}
						
						$SysPhotoRequest = new SysPhotoRequest ();
						$SysPhotoRequest->request_user_id = $this->usercurrent->id;
						$SysPhotoRequest->photo_id = $photo_id;
						$SysPhotoRequest->photo_user_id = $modelPhoto->user_id;
						$SysPhotoRequest->status = SysPhotoRequest::REQUEST_ACCEPTED;
						$SysPhotoRequest->date_request = time ();
						$SysPhotoRequest->date_accepted = time ();
						$SysPhotoRequest->is_read = 2;
						$SysPhotoRequest->save ();
	
						echo json_encode(array(
							'success' => '1',
							'detail' => $this->createUrl('Detail', array('id'=>$photo_id)),
							'thumbnail' => $this->createUrl('Thumbnail', array('id'=>$photo_id)),
						));
						Yii::app ()->end ();
					}
				}
			}
		} else {
			echo json_encode(array( 'success' => '0',));
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
				DataNodejs::updateTotalPhotorequest(Yii::app ()->user->id);
				DataNodejs::updateTotalPhotorequest($modelRequest->request_user_id);
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
				DataNodejs::updateTotalPhotorequest(Yii::app ()->user->id);
				DataNodejs::updateTotalPhotorequest($modelRequest->request_user_id );
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
						DataNodejs::updateTotalPhotorequest($model->request_user_id);
					}
				}
				echo 1;
				DataNodejs::updateTotalPhotorequest(Yii::app ()->user->id);
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
						DataNodejs::updateTotalPhotorequest($model->request_user_id);
					}
				}
				echo 1;
				DataNodejs::updateTotalPhotorequest(Yii::app ()->user->id);
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
	public function actionSuggestUserSharePhoto(){			
			$keyword	=	Yii::app()->request->getParam('q', false);
			$offset = Yii::app()->request->getParam('offset', 0);
			
			//set keyword search
			$search_conditions = array(
					'keyword' => $keyword,
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
				$url = Yii::app()->createUrl('//my/view', array('alias' => $row['alias_name']));
				$dbrows[]	=	array(
						'id' => $row['user_id'],
						'text' => $row['username']
				);
			}
			echo CJSON::encode($dbrows);
			Yii::app()->end();
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
		DataNodejs::updateTotalPhotorequest($userid);
		exit;
	}
	public function actionThumbnail($id) {
		$photo = Photo::model()->find('id = :id', array(':id'=>$id));
		
		if($photo->isAccept()) {
			$seconds_to_cache = 846000;
			$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
			$file = YiiBase::getPathOfAlias('pathroot').'/'.$photo->path.'/thumb275x275/'.$photo->name;
			$extension = substr(strrchr($photo->name,'.'),1);
			header('Content-Type: image/'.$extension);
			header("Expires: $ts");
			header("Pragma: cache");
			header("Cache-Control: max-age=$seconds_to_cache");
			readfile($file);
		}
		
		exit();
	}
	public function actionWaterMarkThumbnail($id) {
		$seconds_to_cache = 846000;
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
		$photo = Photo::model()->find('id = :id', array(':id'=>$id));
		$file = YiiBase::getPathOfAlias('pathroot').'/'.$photo->path.'/thumbwatermark/'.$photo->name;
		$extension = substr(strrchr($photo->name,'.'),1);
		header('Content-Type: image/'.$extension);
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$seconds_to_cache");
		readfile($file);
		exit();
	}
	public function actionDetail($id) {
		$photo = Photo::model()->find('id = :id', array(':id'=>$id));
		
		if($photo->isAccept()) {
			$seconds_to_cache = 846000;
			$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
			$file = YiiBase::getPathOfAlias('pathroot').'/'.$photo->path.'/detail1600x900/'.$photo->name;
			$extension = substr(strrchr($photo->name,'.'),1);
			header('Content-Type: image/'.$extension);
			header("Expires: $ts");
			header("Pragma: cache");
			header("Cache-Control: max-age=$seconds_to_cache");
			readfile($file);
		}
		
		exit();
	}
	public function actionSetPrivatePhotoCandy() {
		if (Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest) {
			$photo = Photo::model()->find('id = :id', array(':id'=>$_POST['photo_id']));
			if($photo->type == Photo::PRAVITE_PHOTO && $photo->user_id == $this->usercurrent->id) {
				if($_POST['price'] < SysPhotoPrivate::MIN_PRICE || $_POST['price'] > SysPhotoPrivate::MAX_PRICE) {
					echo json_encode(array('result'=>'0', 'message'=>Lang::t('photo', 'the price of photo must be between {min} - {max}.', array('{min}'=>SysPhotoPrivate::MIN_PRICE, '{max}'=>SysPhotoPrivate::MAX_PRICE))));
				} else if(!ctype_digit($_POST['price'])) {
					echo json_encode(array('result'=>'0', 'message'=>Lang::t('javascript', 'Please enter only numeric characters ( No space and special characters )')));
				} else {
					$photoPrivate = $photo->photo_private;
					$photoPrivate->price = $_POST['price'];
					if($photoPrivate->save())
						echo json_encode(array('result'=>'1'));
				}
			} else {
				echo json_encode(array('result'=>'0', 'message'=>'Lỗi'));
			}
			exit;
		}
	}
}
if(!function_exists('add_str_queryin')){
	function add_str_queryin($str) {
		if($str != ''){
			return "'{$str}'";
		}
	}
}