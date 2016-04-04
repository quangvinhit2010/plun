<?php
/**
 * @author VSoft
 */
class VModel {
    const LIMIT_UPLOAD = 5;
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
	public function register(Member $user){
	    if(empty($user) || empty($user->id)){
	        return false;
	    }
	    $purpleGuyRound = PurpleguyRound::model()->getCurrentRound();
	    if(empty($purpleGuyRound)){
	        return false;
	    }
	    
	    $params = CParams::load ();
	    $rp_params = array('{year}'=>date ( 'Y' ), '{month}'=>date ( 'm' ), '{day}'=>date ( 'd' ));
	    $detail1600x900 = $params->params->uploads->photo->detail1600x900;
	    $detail1600x900_folder = VHelper::model()->path ( $params->params->uploads->photo->path.DS.$detail1600x900->p, $rp_params );
	    
	    $thumb270x270 = $params->params->uploads->photo->thumb270x270;
	    $thumb270x270_folder = VHelper::model()->path ( $params->params->uploads->photo->path.DS.$thumb270x270->p, $rp_params );
	    
	    $path_folder = VHelper::model()->path ( $params->params->uploads->photo->path, $rp_params , false);
	    
	    $model = PurpleguyProfile::model()->findByAttributes(array('user_id'=>$user->id));
	    if(!empty($model)){
    	    $model = $model;
	    }else{
	        $model = new PurpleguyProfile();
	    }
	    $form = Yii::app()->request->getParam('PurpleguyProfile');
	    $model->attributes = $form;
	    $model->user_id = $user->id;
	    $model->username = $user->username;
	    $model->thumbnail_id = 0;
	    $model->created = time();
	    $model->validate();
	    if(!$model->hasErrors()){
            $images = CUploadedFile::getInstancesByName('images');
            if(!empty($images)){
                $thumbnail_id = 0;
                $i = 0;
                foreach ($images as $img){
                    if($i >= self::LIMIT_UPLOAD){
                        break;
                    }
                    list($width, $height, $type, $attr) = getimagesize($img->getTempName());
                    if ($width > $height) {
                        $resize_type = Image::HEIGHT;
                    } else {
                        $resize_type = Image::WIDTH;
                    }
                    $resize_large_img = false;
                    if ($height > $detail1600x900->h || $width > $detail1600x900->w) {
                        $resize_large_img = true;
                    }
                    
                    $target = VHelper::model()->getUniqueTargetPath($thumb270x270_folder, $img->getName());
                    $newFileName = basename($target);
                    Yii::app ()->image->load ( $img->getTempName() )->resize ($thumb270x270->w, $thumb270x270->h, $resize_type)->crop ( $thumb270x270->w, $thumb270x270->h, 'top' )->sharpen(20)->save($thumb270x270_folder . DS . $newFileName );
                    
                    if ($resize_large_img) {
                        // begin resize and crop for large images
                        Yii::app ()->image->load ( $img->getTempName() )->resize ( $detail1600x900->w, $detail1600x900->h )->sharpen(20)->save ( $detail1600x900_folder . DS . $newFileName );
                    } else {
                        Yii::app ()->image->load ( $img->getTempName() )->sharpen(20)->save ( $detail1600x900_folder . DS . $newFileName );
                    }
                    
                    $photo = new PurpleguyPhoto();
                    $photo->user_id = $user->id;
                    $photo->title = $newFileName;
                    $photo->name = $newFileName;
                    $photo->path = $path_folder;
                    $photo->status = 1;
                    $photo->order = 0;
                    $photo->created = time();
                    $photo->validate();
                    $photo->save();
                    if($thumbnail_id == 0){
                        $thumbnail_id = $photo->id;
                    }
                    $i++;
                }
                $model->thumbnail_id = $thumbnail_id;
            }
            $model->save();
            
            
            $userVote = PurpleguyUserVote::model()->findByAttributes(array('user_id'=>$user->id, 'round_id'=>$purpleGuyRound->id));
            if(!empty($userVote)){
                $userVote = $userVote;
            }else{
                $userVote = new PurpleguyUserVote();
            }
            $userVote->user_id = $user->id;
            $userVote->round_id = $purpleGuyRound->id;
            $userVote->save();
            
	    }else{
	        echo CJSON::encode($model->errors);
	        return false;
	    }
	}
} 