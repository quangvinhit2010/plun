<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class PictureCommand extends CConsoleCommand {
    public function actionResizeThumnail(){

    	$params = CParams::load ();
    	$model = new Photo ();
    	
    	$origin = $params->params->uploads->photo->origin;
    	$detailw200 = $params->params->uploads->photo->detailw200;
    	
    	$data	=	$model->findAll();
    	foreach($data AS $row){
    		$base_path	=	Yii::getPathOfAlias ( 'pathroot' ) . DS . $row->path;
    		$detailw200_folder	=	$base_path . DS . 'detailw200';
    		VHelper::checkDir( $detailw200_folder);
    		$origin_images_path	=	$base_path . DS . 'origin' . DS . $row->name;
    		if(is_file($origin_images_path)){
    			Yii::app ()->image->load ( $origin_images_path )->resize ( $detailw200->w, $detailw200->w, Image::WIDTH )->save ( $detailw200_folder  . DS . $row->name, false);
    		}
    	}
    }
	public function actionUpdateAvatar(){		 
		
		$params = CParams::load ();
		$p150x0 = $params->params->uploads->avatar->p150x0;
		
		$cmd = Yii::app()->db_search->createCommand();
		$cmd->select("*");
		$cmd->from('user_data_search');
		
		$dataUser = $cmd->queryAll();
		
		
		
		$Elasticsearch	=	new Elasticsearch();
		
		foreach($dataUser AS $row){
			$return = array('avatar' => "/public/images/no-user.jpg", 'have_avatar'=>0);
			if(!empty($row['avatar'])){
				if(is_numeric($row['avatar'])){
					//$avatar = "http://{$params->params->img_webroot_url}/{$dataUser['photo_avatar_path']}/thumb160x160/{$dataUser['photo_avatar_name']}";
					$avatar = "/{$row['photo_avatar_path']}/thumb160x160/{$row['photo_avatar_name']}";
				}else{
					$avatar	=	 "/{$p150x0->p}/{$row['avatar']}";
				}
				
				$return = array('avatar'=>$avatar, 'have_avatar'=>1);
				
			}
			$Elasticsearch->update($return, $row['current_country_id'], $row['user_id']);
			
		}
	}
    public function actionResizeMobileThumbnail(){
    	$params = CParams::load ();
    	$model = new Photo ();
    	$thumb768x1024	=	$params->params->uploads->photo->thumb768x1024;
    	
    	Yii::import ( "backend.extensions.plunUploader.upload");
    	$uploader = new upload($params->params->uploads->upload_method);
    	$uploader->allowedExtensions = array (
    			'jpg',
    			'jpeg',
    			'png'
    	);
    	$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes    	
    	
    	$data	=	$model->findAll();
    	foreach($data AS $row){
    		$origin_uploaded_path 	= 	DS . $row->path . DS . 'origin' . DS . $row->name;
    		$thumb768x1024_folder	=	DS . $row->path . DS . 'thumb768x1024' . DS . $row->name;
    		$uploader->checkDir(DS . $row->path . DS . 'thumb768x1024');
    		
    		if(file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS . $row->path . DS . 'origin' . DS . $row->name)){    			
	    		$uploader->loadImageResource($origin_uploaded_path);
	    		$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
	    		$uploader->saveImg($thumb768x1024_folder);
	    		echo "resized $thumb768x1024_folder #{$row->id} \n";
    		}else{
    			echo 'Origin not found ' . Yii::getPathOfAlias ( 'pathroot' ) . DS . "{$thumb768x1024_folder} #{$row->id} \n";
    		}
    	}    	
    }
}
?>
