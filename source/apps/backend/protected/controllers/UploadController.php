<?php
class UploadController extends Controller{

	
	public function actionIndex($path){
		
		
		$tempFolder = Yii::getPathOfAlias('webroot').'/'.Yii::app()->params['upload_path'].'/'.$path.'/';;
		VHelper::checkDir($tempFolder);
		
		/* $tempFolder=Yii::getPathOfAlias('webroot').'/'.Yii::app()->params['upload_path'].'/'.$path.'/';
		$chunksFolder = $tempFolder.$folder;
		
		mkdir($tempFolder, 0777, TRUE);
		mkdir($tempFolder.$chunksFolder, 0777, TRUE); */
		
		Yii::import("ext.EFineUploader.qqFileUploader");
		
		$uploader = new qqFileUploader();
		$uploader->allowedExtensions = array('jpg','jpeg');
		$uploader->sizeLimit = 20 * 1024 * 1024;//maximum file size in bytes
		$uploader->chunksFolder = $tempFolder;
		
		$result = $uploader->handleUpload($tempFolder);
		$result['filename'] = $uploader->getUploadName();
		//$result['folder'] = $webFolder;
		
		$uploadedFile=$tempFolder.$result['filename'];
		
		header("Content-Type: text/plain");
		$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		echo $result;
		Yii::app()->end();
		
	}
	
	
}