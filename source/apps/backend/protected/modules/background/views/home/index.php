<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->hostInfo . '/themes/plun2/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->hostInfo . '/themes/plun2/resources/js/jcrop/css/jquery.Jcrop.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->hostInfo . '/themes/purpleguy/resources/js/jquery/jquery.resizecrop-1.0.3.js', CClientScript::POS_BEGIN);
// 	$file = Home::model()->readFile();
// 	Yii::app()->clientScript->registerScript('homepage',
// 		"var files = ".json_encode($file).";",
// 	CClientScript::POS_HEAD);
	Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/home.js', CClientScript::POS_BEGIN);
	Yii::app()->clientScript->registerCssFile($this->_assetsUrl . '/css/home.css');
	$urlAvatar = Yii::app()->request->hostInfo . '/themes/plun2/resources/html/css/images/avatar_upload.png';
	/* @var $model Background */
	$this->pageTitle=Yii::app()->name;
?>
<div class="box_detail">
	<?php
		$form = $this->beginWidget('CActiveForm', array(
		'id' => 'frmCropAvatar',
		'action' => Yii::app()->createUrl('//background/home/crop'),
		'enableClientValidation' => true,
		'enableAjaxValidation' => true,
		'htmlOptions' => array(
			'enctype' => 'multipart/form-data', 
			'class'=>'frmFillOut'
			)
		));
	?>
	<label>Chose size</label>
	<select name="typeUpload" id="typeUpload">
		<option value="1">245 x 245</option>
		<option value="4">490 x 490</option>
		<option value="2">245 x 490</option>
		<option value="3">490 x 245</option>
	</select>
	<img style="display: none;" id="loading-list" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif">
	<div id="upload-new" class="button">
		<span>Upload</span>
		<input name="image_upload" type="file" id="____uploadImage" onchange="Home.readURL(this);">
	</div>
		
	
	<div class="upload_avatar">
		<div class="frame">
			<div class="wrap-upload-image">
			</div>
		</div>
	</div>
	<input type="hidden" id="x" name="x" />
	<input type="hidden" id="y" name="y" />
	<input type="hidden" id="w" name="w" />
	<input type="hidden" id="h" name="h" />
	<input type="hidden" id="upload_url" name="upload_url" value="<?php echo Yii::app()->createUrl('//background/home/upload')?>" />
	<?php $this->endWidget(); ?>
	<ul class="listPhotos">
		
	</ul>
</div>
