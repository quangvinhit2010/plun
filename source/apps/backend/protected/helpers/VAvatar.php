<?php
/**
 * @author VSoft
 */
class VAvatar {
	const TMP_AVATAR = 'tmp_';
	const TMP_AVATAR_ORIGIN = 'tmp_origin_';
	private static $_models = array ();
	/**
	 * @param system $className        	
	 * @return multitype: unknown
	 */
	public static function model($className = __CLASS__) {
		if (isset ( self::$_models [$className] ))
			return self::$_models [$className];
		else {
			$model = self::$_models [$className] = new $className ( null );
			return $model;
		}
	}	
	/**
	 * Get path avatar
	 * @param unknown $filename
	 * @return string|boolean
	 */
	public function pathAvatar($filename){
		$p150x0 = CParams::load ()->params->uploads->avatar->p150x0;
		if(!empty($filename))
			return Yii::getPathOfAlias('pathroot').DS.$p150x0->p.DS.$filename;
		return false;
	}
	/**
	 * Get URL avatar
	 * @param unknown $filename
	 * @return boolean
	 */
	public function urlAvatar($filename){
		$params = CParams::load ();
		$p150x0 = $params->params->uploads->avatar->p150x0;
		/*
		if(Yii::app()->baseUrl == '/admin'){
			$image = DS.$p150x0->p.DS.$filename;
			//if(!empty($filename) && file_exists($image)){
			return $image;
			//}
		} else {
			if(!empty($filename) && file_exists(Yii::getPathOfAlias('pathroot').DS.$p150x0->p.DS.$filename)){
				return Yii::app()->createUrl($p150x0->p.DS.$filename);
			}
		}
		*/
		if(!empty($filename)){
			return "http://{$params->params->img_webroot_url}/{$p150x0->p}/{$filename}";
		}else{
			return false;
		}
	}
	/**
	 * Upload avatar
	 * @param unknown $filename
	 */
	public function uploadAvatar($filename) {
	    $user = Yii::app()->user->data();
		$params = CParams::load ();		
		$rp_params = array('{year}'=>date ( 'Y' ), '{month}'=>date ( 'm' ), '{day}'=>date ( 'd' ));
		
		$thumb50x50 = $params->params->uploads->photo->thumb50x50;
		$thumb50x50_folder = VHelper::model()->path ( $thumb50x50->p, $rp_params );
		
		$thumb160x160 = $params->params->uploads->photo->thumb160x160;
		$thumb160x160_folder = VHelper::model()->path ( $thumb160x160->p, $rp_params );
		
		$thumb275x275 = $params->params->uploads->photo->thumb275x275;
		$thumb275x275_folder = VHelper::model()->path ( $thumb275x275->p, $rp_params );
		
		$detailw200 = $params->params->uploads->photo->detailw200;
		$detailw200_folder = VHelper::model()->path ( $detailw200->p, $rp_params );
		
		$detail1600x900 = $params->params->uploads->photo->detail1600x900;
		$detail1600x900_folder = VHelper::model()->path ( $detail1600x900->p, $rp_params );
		
		$origin = $params->params->uploads->photo->origin;
		$origin_folder = VHelper::model()->path ( $origin->p, $rp_params );
		
		$path_folder = VHelper::model()->path ( $params->params->uploads->photo->path, $rp_params , false);
		
		$p150x0 = $params->params->uploads->avatar->p150x0;
		$origin_uploaded_path = VHelper::model()->path ($p150x0->p) . DS . VAvatar::TMP_AVATAR_ORIGIN.$filename;
		
		list ( $width, $height ) = getimagesize ( $origin_uploaded_path );
		
		if ($width > $height) {
			$resize_type = Image::HEIGHT;
		} else {
			$resize_type = Image::WIDTH;
		}
		
		$pathinfo = pathinfo ( $filename);
		
		$target = VHelper::model()->getUniqueTargetPath($thumb275x275_folder, $filename);
		$newFileName = basename($target);
		$resize_large_img = false;
		if ($height > $detail1600x900->h || $width > $detail1600x900->w) {
			$resize_large_img = true;
		}
		Yii::app ()->image->load ( $origin_uploaded_path )->sharpen(20)->save ( $origin_folder . DS . $newFileName );
		
		/** crop avatar **/
		$tmp_src = VHelper::model()->path ($p150x0->p) . DS . VAvatar::TMP_AVATAR.$filename;
		$thumb=Yii::app()->phpThumb->create($tmp_src);
		$thumb->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);
		$thumb->resize($thumb160x160->w);
		//                 $thumb->show();
		$thumb->save($thumb160x160_folder . DS . $newFileName);
		/**  end **/
		Yii::app ()->image->load ( $thumb160x160_folder . DS . $newFileName )->resize ( $thumb50x50->w, $thumb50x50->h )->sharpen(20)->save ( $thumb50x50_folder . DS . $newFileName );
		
		Yii::app ()->image->load ( $origin_uploaded_path )->resize ( $thumb275x275->w, $thumb275x275->h, $resize_type )->crop ( $thumb275x275->w, $thumb275x275->h, 'top' )->sharpen(20)->save ( $thumb275x275_folder . DS . $newFileName );
		
		// resize for thumbnail (width 200)
		Yii::app ()->image->load ( $origin_uploaded_path )->resize ( $detailw200->w, $detailw200->w, Image::WIDTH )->sharpen(20)->save ( $detailw200_folder . DS . $newFileName );
		
		if ($resize_large_img) {
			// begin resize and crop for large images
			Yii::app ()->image->load ( $origin_uploaded_path )->resize ( $detail1600x900->w, $detail1600x900->h , Image::AUTO)->sharpen(20)->save ( $detail1600x900_folder . DS . $newFileName );
		} else {
			copy ( $origin_uploaded_path, $detail1600x900_folder . DS . $newFileName );
		}
		// save to database
		$photo = new Photo ();
		$photo->album_id = 0;
		$photo->user_id = $user->id;
		$photo->title = pathinfo ( $newFileName, PATHINFO_FILENAME );
		$photo->name = $newFileName;
		$photo->path = $path_folder;
		$photo->status = 1;
		$photo->type = Photo::PUBLIC_PHOTO;
		$photo->created = time ();
		if($photo->save ()){
		    $hour = time();
		    $cri = new CDbCriteria();
		    $cri->addCondition("user_id = :user_id AND action = :action AND (timestamp BETWEEN :from AND :to)");
		    $cri->params = array(':user_id'=>$user->id, ':action'=>Activity::LOG_PHOTO_UPLOAD, ':from'=>strtotime("-1 hours", $hour), ':to'=> $hour );
		    $logExist = Activity::model()->find($cri);
		    $group_id = 0;
		    if(!empty($logExist->id)){
		        $group_id = $logExist->id;
		    }
		    Activity::model()->log(
		    Activity::LOG_PHOTO_UPLOAD,
		    array(
		    '{user}' => $user->username,
		    '{photo}' => $photo->id,
		    '{typePhoto}' => Photo::PUBLIC_PHOTO,
		    ),
		    $user->id,
		    $user->username,
		    $photo->id,
		    0,
		    $group_id
		    );
			return $photo;
		}
		return false;
	}
	
	public function autoResizeAvatar() {
	    $user = Yii::app()->user->data();
		$params = CParams::load ();	
		$origin = $params->params->uploads->photo->origin;
		$thumb50x50 = $params->params->uploads->photo->thumb50x50;
		$thumb160x160 = $params->params->uploads->photo->thumb160x160;
		$thumb275x275 = $params->params->uploads->photo->thumb275x275;
		$thumb768x1024	=	$params->params->uploads->photo->thumb768x1024;
		$detailw200 = $params->params->uploads->photo->detailw200;
		$detail425x320 = $params->params->uploads->photo->detail425x320;
		$detail1600x900 = $params->params->uploads->photo->detail1600x900;
		
		Yii::import ( "backend.extensions.plunUploader.upload");
		$uploader = new upload($params->params->uploads->upload_method);
		$uploader->allowedExtensions = array (
				'jpg',
				'jpeg',
				'png'
		);
		$uploader->inputName	=	'image';
		$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes

		$thumb50x50_folder = $uploader->setPath ( $thumb50x50->p , false );
		$detail425x320_folder = $uploader->setPath ( $detail425x320->p , false );
		$thumb160x160_folder = $uploader->setPath ( $thumb160x160->p , false );
		$thumb275x275_folder = $uploader->setPath ( $thumb275x275->p , false );
		$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
		$detailw200_folder = $uploader->setPath ( $detailw200->p , false );
		$detail1600x900_folder = $uploader->setPath ( $detail1600x900->p, false  );
		$origin_folder = $uploader->setPath ( $origin->p, false );
		$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
		$result = $uploader->upload ( $origin_folder );
				
		// get origin path after file upload successfully
		$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
		$uploader->loadImageResource($origin_uploaded_path);
		
		$resize_large_img = false;
		
		if (isset ( $result ['success'] )) {
			// check resize large photo
			if ($uploader->img_resource->height > $detail1600x900->h || $uploader->img_resource->width > $detail1600x900->w) {
				$resize_large_img = true;
			}			
			// begin resize and crop for thumbnail
			$uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
			$uploader->crop($thumb160x160->w, $thumb160x160->h, 'top');
			$uploader->sharpen(20);
			$uploader->saveImg($thumb160x160_folder . DS . $uploader->getUploadName ());
			

			
			$uploader->resizeImage($thumb50x50->w, $thumb50x50->h);
			$uploader->saveImg($thumb50x50_folder . DS . $uploader->getUploadName ());
			
			
			$uploader->resizeImage($thumb275x275->w, $thumb275x275->h);
			$uploader->crop($thumb275x275->w, $thumb275x275->h, 'top');
			$uploader->saveImg($thumb275x275_folder . DS . $uploader->getUploadName ());	
					
			$uploader->resizeImage($detailw200->w, $detailw200->w);
			$uploader->saveImg($detailw200_folder . DS . $uploader->getUploadName ());

			$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
			$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());
			
			$uploader->resizeImage($detail425x320->w, $detail425x320->h, true);
			$uploader->saveImg($detail425x320_folder . DS . $uploader->getUploadName ());
			
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
			$photo = new Photo ();
			$photo->album_id = 0;
			$photo->user_id = $user->id;
			$photo->title = pathinfo ( $uploader->getUploadName (), PATHINFO_FILENAME );
			$photo->name = $uploader->getUploadName ();
			$photo->path = ltrim($path_folder,'\/');
			$photo->status = 0;
			$photo->type = Photo::PUBLIC_PHOTO;
			$photo->created = time ();
			if($photo->save ()){
			    $hour = time();
			    $cri = new CDbCriteria();
			    $cri->addCondition("user_id = :user_id AND action = :action AND (timestamp BETWEEN :from AND :to)");
			    $cri->params = array(':user_id'=>$user->id, ':action'=>Activity::LOG_PHOTO_UPLOAD, ':from'=>strtotime("-1 hours", $hour), ':to'=> $hour );
			    $logExist = Activity::model()->find($cri);
			    $group_id = 0;
			    if(!empty($logExist->id)){
			        $group_id = $logExist->id;
			    }
			    Activity::model()->log(
			    Activity::LOG_PHOTO_UPLOAD,
			    array(
			    '{user}' => $user->username,
			    '{photo}' => $photo->id,
			    '{typePhoto}' => Photo::PUBLIC_PHOTO,
			    ),
			    $user->id,
			    $user->username,
			    $photo->id,
			    0,
			    $group_id
			    );
				return $photo;
			}
	    
		}else{
			return $result;
		}
	}
} 