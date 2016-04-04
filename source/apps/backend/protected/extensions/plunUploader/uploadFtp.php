<?php
class uploadFtp{
	public $allowedExtensions = array();
	public $sizeLimit = null;
	public $inputName = 'qqfile';
	
	public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
	public $chunksExpireIn = 604800; // One week
	
	protected $uploader;
	protected $uploadName;
	
	public $img_resource	=	null;
	public $ftp_basedir	=	'';
	
	public $min_image_width_size;
	public $min_image_height_size;
		
	private $tmp_file	=	null;
	
	public function __construct(){
		$params = CParams::load ();

		
		$this->uploader	=	Yii::app()->ftp;
		if($params->params->uploads->ftp_basedir){
			$this->ftp_basedir	=	$params->params->uploads->ftp_basedir;
		}
		$this->sizeLimit = $this->toBytes(ini_get('upload_max_filesize'));
	}	
	
	/**
	 * Get the original filename
	 */
	public function getName(){
		if (isset($_REQUEST['qqfilename']))
			return Util::getSlug($_REQUEST['qqfilename']);
	
		if (isset($_FILES[$this->inputName]))
			return Util::getSlug($_FILES[$this->inputName]['name']);
	}
	
	/**
	 * Get the name of the uploaded file
	 */
	public function getUploadName(){
		return $this->uploadName;
	}
	
	/**
	 * Process the upload.
	 * @param string $uploadDirectory Target directory.
	 * @param string $name Overwrites the name of the file.
	 */
	public function handleUpload($uploadDirectory, $name = null){
	
		// Check that the max upload size specified in class configuration does not
		// exceed size allowed by server config
		if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit ||
			$this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit){
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			return array('error'=>"Server error. Increase post_max_size and upload_max_filesize to ".$size);
		}
	
	
		if(!isset($_SERVER['CONTENT_TYPE'])) {
			return array('error' => "No files were uploaded.");
		} else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') !== 0){
			return array('error' => "Server error. Not a multipart request. Please set forceMultipart to default value (true).");
		}
	
		// Get size and name
	
		$file = $_FILES[$this->inputName];
		$size = $file['size'];
	
		if ($name === null){
			$name = $this->getName();
		}
	
		// Validate name
	
		if ($name === null || $name === ''){
			return array('error' => 'File name empty.');
		}
	
		// Validate file size
	
		if ($size == 0){
			return array('error' => 'File is empty.');
		}
	
		if ($size > $this->sizeLimit){
			return array('error' => 'File is too large.');
		}
	
		// Validate file extension
	
		$pathinfo = pathinfo($name);
		$ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
	
		if($this->allowedExtensions && !in_array(strtolower($ext), array_map("strtolower", $this->allowedExtensions))){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
		}
		
		//validate img size
		list ( $width, $height ) = getimagesize ( $file['tmp_name'] );
		if ($width < $this->min_image_width_size || $height < $this->min_image_height_size) {
			$result ['success'] = false;
			$result ['error'] = Lang::t('photo', 'Please choose an image with minimum size is {limit_w}x{limit_h}px.', array('{limit_w}' => $this->min_image_width_size, '{limit_h}' => $this->min_image_height_size));
			return $result;
		} 		
		
		// Save a chunk
		$target = $this->getUniqueTargetPath($uploadDirectory, $name);
	
		if ($target){
			$this->uploadName = basename($target);
			if ($this->uploader->put($target, $file['tmp_name'], FTP_IMAGE)){
				return array('success'=> true);
			}
		}
	
		return array('error'=> 'Could not save uploaded file.' .
			'The upload was cancelled, or server error encountered');
	}	
	/**
	 * Returns a path to use with this upload. Check that the name does not exist,
	 * and appends a suffix otherwise.
	 * @param string $uploadDirectory Target directory
	 * @param string $filename The name of the file to use.
	 */
	public function getUniqueTargetPath($uploadDirectory, $filename)
	{
	
		$pathinfo = pathinfo($filename);
		$base = $pathinfo['filename'];
		$ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		$ext = $ext == '' ? $ext : '.' . $ext;
	
		$unique = $base;
		$suffix = 0;
	
		// Get unique file name for the file, by appending random suffix.
	
		while ($this->check_file_exists($this->ftp_basedir . $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext)){
			$suffix += rand(1, 999);
			$unique = $base.'-'.$suffix;
		}
	
		$result =  $this->ftp_basedir . $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;
	
		return $result;
	}	
	public function check_file_exists($file_path){

		try{
			if($this->uploader->size($file_path)){
				return true;
			}else{
				return false;
			}
		}
		catch (Exception $e){
			return false;
		}
	}
	public function check_dir_exist($uploadDirectory){
		try{
			$check	=	$this->uploader->chdir($uploadDirectory);
		}
		catch (Exception $e) {
			$check	=	false;
		}
		return $check;	
	}
	public function delete($file_path){
		try{
			if($this->uploader->delete($this->ftp_basedir . $file_path)){
				return true;
			}else{
				return false;
			}
		}
		catch (Exception $e){
			return false;
		}
	}
	public function mkdir($dir){	
		$dir	=	ltrim($dir,'\/');
		$parts = explode('/',$dir); // 2013/06/11/username

		//$this->uploader->chdir('/');
		$tmp_path	=	'';
		foreach($parts as $part){
			$tmp_path	.=	'/' . $part;
			if(!$this->check_dir_exist($tmp_path)){
				$this->uploader->mkdir($tmp_path);
			}			
		}
		return true;
	}
	/**
	 *
	 * @param
	 *        	$conditions
	 * @return boolean
	 */
	public function checkDir($dir) {
		try{
			if (!$this->check_dir_exist( $this->ftp_basedir . $dir )) {
				return $this->mkdir($this->ftp_basedir . $dir); 
			}
		}
		catch (Exception $e){
			return false;
		}		
		return true;
	}	
	
	public function getImageSize($file_path){
		if($this->uploader->size($this->ftp_basedir . $file_path)){
			return true;
		}else{
			return false;
		}		
	}

	public function resizeImage($width, $height, $resize_type_auto){
		if($width < $this->img_resource->width || $height < $this->img_resource->height){
			
			if($resize_type_auto){
				$resize_type	=	Image::AUTO;
			}else{
				if ($this->img_resource->width > $this->img_resource->height) {
					$resize_type = Image::HEIGHT;
				} else {
					$resize_type = Image::WIDTH;
				}
			}
			$this->img_resource	=	$this->img_resource->resize ( $width, $height, $resize_type );		
		}		
	}
	/**
	 * Converts a given size with units to bytes.
	 * @param string $str
	 */
	protected function toBytes($str){
		$val = trim($str);
		$last = strtolower($str[strlen($str)-1]);
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}
	/**
	 * Sharpen an image.
	 *
	 * @param   integer  amount to sharpen, usually ~20 is ideal
	 * @return  object
	 */
	public function sharpen($amount)
	{
		$this->img_resource->sharpen($amount);
	}	
	public function crop($w, $h, $top, $left){
		$this->img_resource->crop ( $w, $h, $top, $left );
	}
	public function watermark($watermark_pathfile){
		$this->img_resource->watermark ( $watermark_pathfile );
	}	
	public function logo($logo_pathfile){
		$this->img_resource->logo ( $logo_pathfile );
	}	
	public function imagesize($path_file){
		$ext_name	=	substr($this->ftp_basedir . $path_file, strrpos($this->ftp_basedir . $path_file, '/') + 1);
		$file_name	=	substr($this->ftp_basedir . $path_file, strrpos($this->ftp_basedir . $path_file, '/') + 1);
		
		
		$temp_file_dir = sys_get_temp_dir();
		$temp_filename	=	microtime() . ".{$ext_name}";

		$this->uploader->get($temp_file_dir . DS . $temp_filename, $this->ftp_basedir . $path_file, FTP_IMAGE);
		$info	=	getimagesize($temp_file_dir . DS . $temp_filename);
		@unlink($temp_file_dir . DS . $temp_filename);
		
		return $info;
	}
	public function loadImageResource($file_source){
		$file_name	=	substr($file_source, strrpos($file_source, '/') + 1);
		
		$temp_file_dir = sys_get_temp_dir();
		$temp_file	=	microtime() . ".{$file_name}";
		
		$this->tmp_file	=	$temp_file_dir . DS . $temp_file;
		$this->uploader->get($this->tmp_file, $this->ftp_basedir . $file_source, FTP_IMAGE);
		$this->img_resource	=	Yii::app ()->image->load ( $this->tmp_file );
		return $this->img_resource;
	}		
	public function saveImg($file_desc){
		$file_name	=	substr($file_desc, strrpos($file_desc, '/') + 1);
		
		$temp_file_dir = sys_get_temp_dir();
		$temp_file	=	microtime() . ".{$file_name}";
				
		$this->img_resource->save($temp_file_dir . DS . $temp_file);
		$this->uploader->put($this->ftp_basedir . $file_desc, $temp_file_dir . DS . $temp_file, FTP_IMAGE);
		
		@unlink($temp_file_dir . DS . $temp_file);
	}
	
	public function detroyResource(){
		unlink($this->tmp_file);
		
	}
}