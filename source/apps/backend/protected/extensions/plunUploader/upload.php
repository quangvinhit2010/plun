<?php
class upload{
	public $allowedExtensions = array();
	public $sizeLimit = null;
	public $inputName = 'qqfile';
	public $min_image_width_size	=	200;
	public $min_image_height_size	=	200;
	
	protected $uploadName;
	protected $uploader;
	
	public $img_resource	=	null;
	protected $img_path	=	null;
	
	public function __construct($uploadMethod = 'ftp'){		
		//set uploader
		if($uploadMethod	==	'web'){
			Yii::import ( "backend.extensions.plunUploader.uploadWeb" );
			$this->uploader = new uploadWeb();
		}else{
			Yii::import ( "backend.extensions.plunUploader.uploadFtp" );
			$this->uploader	=	new uploadFtp();	
		}		
		
	}
	/**
	 * Get the original filename
	 */
	public function getName(){
		return $this->uploader->getName();
	}	
	/**
	 * Get the name of the uploaded file
	 */
	public function getUploadName(){
		return $this->uploader->getUploadName();
	}
		
	public function upload($uploadDirectory, $name = null){
		$this->uploader->allowedExtensions	=	$this->allowedExtensions;
		$this->uploader->min_image_width_size	=	$this->min_image_width_size;
		$this->uploader->min_image_height_size	=	$this->min_image_height_size;
		$this->uploader->sizeLimit	=	$this->sizeLimit;
		$this->uploader->inputName	=	$this->inputName;
		return $this->uploader->handleUpload($uploadDirectory, $name);
	}
	/**
	 *
	 * @param
	 *        	$conditions
	 * @return boolean
	 */
	public function checkDir($dir) {
		return $this->uploader->checkDir($dir);
	}	
	public function check_file_exists($file_path){
		return $this->uploader->check_file_exists($file_path);
	}
	public function getUniqueTargetPath($uploadDirectory, $filename){
		return $this->uploader->getUniqueTargetPath($uploadDirectory, $filename);
	}
	public function loadImageResource($file_source){
		$this->img_resource	=	$this->uploader->loadImageResource($file_source);
	}
	public function resizeImage($width, $height, $resize_type_auto = false){
		return $this->uploader->resizeImage($width, $height, $resize_type_auto);
	}
	public function crop($w, $h, $top = 'center', $left = 'center'){
		$this->uploader->crop($w, $h, $top, $left);
	}	
	public function watermark($watermark_pathfile){
		$this->uploader->watermark($watermark_pathfile);
	}
	public function logo($logo_pathfile){
		$this->uploader->logo($logo_pathfile);
	}	
	public function sharpen($file_source){
		$this->uploader->sharpen($file_source);
	}
	
	public function saveImg($file_desc){
		$this->uploader->saveImg($file_desc);
	}
	public function setPath($path, $full = true) {
		if (isset ( $path )) {
			$path = str_replace ( '{year}', date ( 'Y' ), $path );
			$path = str_replace ( '{month}', date ( 'm' ), $path );
			$path = str_replace ( '{day}', date ( 'd' ), $path );
			$folder = ($full) ? Yii::getPathOfAlias ( 'webroot' ) . DS . $path : DS . $path;
			if ($this->checkDir($folder)) {
				return $folder;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	public function detroyResource(){
		$this->uploader->detroyResource();
	
	}	
}