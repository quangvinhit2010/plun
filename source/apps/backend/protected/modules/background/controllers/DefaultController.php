<?php

class DefaultController extends Controller
{
	public function filters()
	{
		return array(
				'rights',
		);
	}
	public function actionConfig() {
		
		$model = new Background();
		
		if(!empty($_POST)) {
			
			$error = "";
			
			if($_FILES["image_upload"]["type"] != "image/jpg" && $_FILES["image_upload"]["type"] != 'image/jpeg') {
				$error = 'Only accept jpg file';
			} elseif(!$this->_checkImageSize($_FILES["image_upload"]["tmp_name"], $_POST['image_size'])) {
				$error = 'Image width and height is invalid';
			} else {
				$time = time();
				
				$oldFile = $model->find('file_name=:file_name', array(':file_name'=>$_POST['image_name']));
				
				$fileName = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $_POST['image_name'] . '.jpg';
				$changeOldNameTo = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $time . '.jpg';
				
				if(file_exists($fileName))
					rename($fileName, $changeOldNameTo);
				
				if($oldFile) {
					$oldFile->file_name = $time;
					$oldFile->update();
				}
				
				$model->file_name = $_POST['image_name'];
				$model->size = $_POST['image_size'];
				$model->save();
				
				move_uploaded_file($_FILES["image_upload"]["tmp_name"], $fileName);
			}
			
			echo '<script>parent.uploadSuccess("'.$_POST['image_name'].'", "'.$error.'")</script>';
			
		} else {
			$this->render('config', array('model'=>$model));
		}
	}
	public function _checkImageSize($file, $size) {
		$dimension = explode('x', $size);
		$image_info = getimagesize($file);
		if($image_info[0] != $dimension[0] || $image_info[1] != $dimension[1])
			return false;
		else
			return true;
	}
	public function actionLoadOld($size, $exclude) {
		$model = new Background();
		$backgrounds = $model->findAll('size=:size AND file_name!=:file_name', array(':size'=>$size, ':file_name'=>$exclude));
		$this->renderPartial('partial/images_list', array('backgrounds'=>$backgrounds));
	}
	public function actionUpdateBackground($id) {
		$model = new Background();
		
		$fileToUpdate = $model->find('id=:id', array(':id'=>$id));
		
		$dimensionList = $model->getListImage();
		
		foreach($dimensionList as $d) {
			if($d['size']==$fileToUpdate->size) {
				$fileName = $d['file_name'];
				break;
			}
		}
		
		$time = time();
		
		$fileNameToChange = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $fileName . '.jpg';
		$changeOldNameTo = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $time . '.jpg';
		rename($fileNameToChange, $changeOldNameTo);
		
		$fileNameToChange = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $fileToUpdate->file_name . '.jpg';
		$changeOldNameTo = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $fileName . '.jpg';
		rename($fileNameToChange, $changeOldNameTo);
		
		$fileToUpdate1 = $model->find('file_name=:file_name', array(':file_name'=>$fileName));
		$fileToUpdate1->file_name = $time;
		$fileToUpdate1->update();
		
		$fileToUpdate->file_name = $fileName;
		$fileToUpdate->update();
	}
	public function actionDeleteBackground($id) {
		$model = new Background();
		$fileToUpdate = $model->find('id=:id', array(':id'=>$id));
		$fileName = YiiBase::getPathOfAlias('pathroot')."/uploads/background/" . $fileToUpdate->file_name . '.jpg';
		unlink($fileName);
		
		$fileToUpdate->delete();
	}
}