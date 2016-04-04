<?php

class uploadWeb {

    public $allowedExtensions = array();
    public $sizeLimit = null;
    public $inputName = 'qqfile';

    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week
	public $img_resource = null;
	public $min_image_width_size;
	public $min_image_height_size;
	    
    protected $uploadName;

    function __construct(){
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
			$result ['success'] = false;
			$result ['error'] = 'File has an invalid extension, it should be one of '. $these . '.';
			
			return $result;
			
		}

		//validate img size
		list ( $width, $height ) = getimagesize ( $file['tmp_name'] );
		if ($width < $this->min_image_width_size || $height < $this->min_image_height_size) {
			$result ['success'] = false;
			$result ['error'] = Lang::t('photo', 'Please choose an image with minimum size is {limit_w}x{limit_h}px.', array('{limit_w}' => $this->min_image_width_size, '{limit_h}' => $this->min_image_height_size));
			return $result;
		} 		
		
		// Save a chunk
		$params = CParams::load ();
		$target = $this->getUniqueTargetPath($params->params->upload_path . DS . $uploadDirectory, $name);
	
		if ($target){
			$this->uploadName = basename($target);
	
			if (move_uploaded_file($file['tmp_name'], $target)){
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
        // Allow only one process at the time to get a unique file name, otherwise
        // if multiple people would upload a file with the same name at the same time
        // only the latest would be saved.

        if (function_exists('sem_acquire')){
            $lock = sem_get(ftok(__FILE__, 'u'));
            sem_acquire($lock);
        }

        $pathinfo = pathinfo($filename);
        $base = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $ext = $ext == '' ? $ext : '.' . $ext;

        $unique = $base;
        $suffix = 0;

        // Get unique file name for the file, by appending random suffix.

        while ($this->check_file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext)){
            $suffix += rand(1, 999);
            $unique = $base.'-'.$suffix;
        }

        $result =  $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;

        // Create an empty target file
        if (!touch($result)){
            // Failed
            $result = false;
        }

        if (function_exists('sem_acquire')){
            sem_release($lock);
        }

        return $result;
    }
    public function check_file_exists($file_path){
    	try{
    		if(is_file($file_path)){
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
    	    if(file_exists($uploadDirectory)){
    			return true;
    		}else{
    			return false;
    		}
    	}
    	catch (Exception $e){
    		return false;
    	}
    }
    /**
     * Deletes all file parts in the chunks folder for files uploaded
     * more than chunksExpireIn seconds ago
     */
    protected function cleanupChunks(){
        foreach (scandir($this->chunksFolder) as $item){
            if ($item == "." || $item == "..")
                continue;

            $path = $this->chunksFolder.DIRECTORY_SEPARATOR.$item;

            if (!is_dir($path))
                continue;

            if (time() - filemtime($path) > $this->chunksExpireIn){
                $this->removeDir($path);
            }
        }
    }
    /**
     *
     * @param
     *        	$conditions
     * @return boolean
     */
    public static function checkDir($dir) {
    	$params = CParams::load ();
    	if(!file_exists($params->params->upload_path . $dir)){
    		mkdir($params->params->upload_path . $dir, 755, true);
    		return $params->params->upload_path . $dir;
    	}
    	return true;
    }
    /**
     * Removes a directory and all files contained inside
     * @param string $dir
     */
    protected function removeDir($dir){
        foreach (scandir($dir) as $item){
            if ($item == "." || $item == "..")
                continue;

            unlink($dir.DIRECTORY_SEPARATOR.$item);
        }
        rmdir($dir);
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
			$this->img_resource	=	$this->img_resource->resize ( $width, $height, $resize_type);		
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
    public function loadImageResource($file_source){    
    	$params = CParams::load ();
    	$this->img_resource	=	Yii::app ()->image->load ( $params->params->upload_path . $file_source );
    	return $this->img_resource;
    }
    public function saveImg($file_desc){
    	$params = CParams::load ();
    	$this->img_resource->save($params->params->upload_path . $file_desc, false);
    }
    
    public function detroyResource(){
    	unset($this->img_resource);
    
    }        
}
