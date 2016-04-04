<?php

class HomeController extends Controller
{
	public $_assetsUrl;
	public $debug = true;
	public function filters()
	{
		return array(
				'rights',
		);
	}
	public function beforeAction($action){
		$assetsPath = Yii::getPathOfAlias('background.assets');
		if( $this->debug===true )
			$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
		else
			$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
		return parent::beforeAction($action);	
	}
	
	
	public function actionSlide() {		
		$this->render('slide', array());
	}	
	
	public function actionIndex() {
		$this->render('index', array());
	}	
	
	public function actionUpload() {
		$images = CUploadedFile::getInstanceByName('images');		
		$folderOrigin = VHelper::model()->path('uploads'.DS.'home'.DS.'photo'.DS.'origin');
		$pathinfo = pathinfo ( $images->getName() );
		$target = VHelper::model()->getUniqueTargetPath($folderOrigin, Util::getSlug($images->getName()));
		$newFileName = basename($target);
		if(!empty($_POST['type'])){
			$typeUpload = $_POST['type'];
			$attr = HomePhoto::model()->getAttrByColRow($typeUpload['col'], $typeUpload['row']);
			if(!empty($attr)){
				$_width = $attr['_width'];
				$_height = $attr['_height'];;
				$pathThumb = $attr['pathThumb'];
				$folderThumb = VHelper::model()->path($pathThumb);
				if(!empty($images)){				
					Yii::app ()->image->load ( $images->getTempName() )->sharpen(20)->save ( $folderOrigin . DS . $newFileName );
					$thumb=Yii::app()->phpThumb->create($images->getTempName());
					$thumb->crop($_POST['crop']['x'], $_POST['crop']['y'], $_POST['crop']['w'], $_POST['crop']['h'])->resize($_width, $_height);
	// 				$thumb->show();
					$thumb->save($folderThumb . DS . $newFileName);
					/**save file**/		
					$file = HomePhoto::model()->findByAttributes(array('position_id'=>$_POST['position']));		
					if(empty($file)){
						$file = new HomePhoto();
					}
					$file->file_name = $newFileName;
					$file->position_id = $_POST['position'];
					$file->created = time();
					$file->validate();
					if($file->save()){
						echo json_encode(array('id'=>$file->id, 'file'=>$pathThumb . DS . $newFileName));
					}				
				}
			}			
		}
		exit;
	}
	
	public function actionOrder() {
		$pos = Yii::app()->request->getParam('pos');
		$pos = explode('|', $pos);
		$order = Yii::app()->request->getParam('order');
		$order = explode('|', $order);
		if(!empty($pos) && !empty($order)){
			foreach ($pos as $key=>$id){
				if(!empty($order[$key])){
					$position = HomePosition::model()->findByPk($id);
					$position->order = $order[$key];
					$position->save();
				}
			}	
			echo true;		
		}
		exit();
	}
	
	public function actionUpdatephoto() {
		$id = Yii::app()->request->getPost('id');
		$attributes = Yii::app()->request->getPost('photo');
		$photo = HomePhoto::model()->findByPk($id);
		if(!empty($id) && !empty($photo)){
			$photo->attributes = $attributes;
			$photo->save();
		}
		echo true;
		exit();
	}
	
	public function actionDelphoto() {
		$id = Yii::app()->request->getPost('id');
		$photo = HomePhoto::model()->findByPk($id);
		if(!empty($photo->position)){
			$attr = HomePhoto::model()->getAttrByColRow($photo->position->col, $photo->position->row);
			$file = Yii::getPathOfAlias ( 'pathroot' ).DS.$attr['pathThumb'].DS.$photo->file_name;
			if(file_exists($file)){
				unlink($file);
			}
			$fileOrigin = Yii::getPathOfAlias ( 'pathroot' ).DS.'uploads'.DS.'home'.DS.'photo'.DS.'origin'.DS.$photo->file_name;
			if(file_exists($fileOrigin)){
				unlink($fileOrigin);
			}
			$photo->delete();
		}
		echo 1;
		exit();		
	}
}