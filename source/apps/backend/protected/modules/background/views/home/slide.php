<?php
    Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/jquery-1.11.0.min.js', CClientScript::POS_BEGIN);
    Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/jquery-ui.min.js', CClientScript::POS_BEGIN);
    Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/packery.pkgd.js', CClientScript::POS_BEGIN);
    Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/draggabilly.pkgd.js', CClientScript::POS_BEGIN);
    Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/dragbox.js', CClientScript::POS_BEGIN);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->hostInfo . '/themes/plun2/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->hostInfo . '/themes/plun2/resources/js/jcrop/css/jquery.Jcrop.css');
	Yii::app()->clientScript->registerScriptFile($this->_assetsUrl . '/js/home.js', CClientScript::POS_BEGIN);
	Yii::app()->clientScript->registerCssFile($this->_assetsUrl . '/css/home.css');
	$urlAvatar = Yii::app()->request->hostInfo . '/themes/plun2/resources/html/css/images/avatar_upload.png';
    $urlImgSrc = Yii::app()->request->hostInfo . '/themes/plun2/resources/html/images';
?>
<img style="display: none;" id="loading-list" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif">
<div class="popUpload"></div>
<div style="display: none;">
	<input name="image_upload" type="file" id="uploadImage" onchange="Home.readURL(this);">
	<input type="hidden" id="x" name="x" />
	<input type="hidden" id="y" name="y" />
	<input type="hidden" id="w" name="w" />
	<input type="hidden" id="h" name="h" />
	<div id="upload_url" url-upload="<?php echo Yii::app()->createUrl('//background/home/upload')?>" url-delphoto="<?php echo Yii::app()->createUrl('//background/home/delphoto')?>" url-order="<?php echo Yii::app()->createUrl('//background/home/order')?>" url-update="<?php echo Yii::app()->createUrl('//background/home/updatephoto')?>"></div>
</div>
<div class="home_hot_pics left">
	<?php 
	$cri = new CDbCriteria();
	$cri->addCondition("status=1");
	$cri->order = "`order` ASC";
	$homePosition = HomePosition::model()->findAll($cri);
	?>
    <ul id="masonry_box" class="hero-masonry">
		<?php foreach ($homePosition as $position):?>
		<?php 
		$attr = HomePhoto::model()->getAttrByColRow($position->col, $position->row);
		$fid = (!empty($position->photo)) ? $position->photo->id : '';
		?>
        <li data-col="<?php echo $position->col;?>" data-row="<?php echo $position->row;?>" data-position="<?php echo $position->id;?>" data-order="<?php echo $position->order;?>" data-fid="<?php echo $fid;?>" class="item ui-state-default">
            <div class="wrapbox_img">
                    <?php if(!empty($position->photo)):?>
	                <a href="javascript:void(0);">
                    	<img src="/<?php echo $attr['pathThumb'].DS.$position->photo->file_name;?>">
	                </a>
	                <div class="wrap_edit_photo">
	                    <span class="delPhoto">Del</span><span class="addLink">Link</span>
	                    <div class="boxAddLink"><input class="link" type="text" /></div>
                    </div>
                    <?php else :?>
                    	<a href="javascript:void(0);"></a>
                    <?php endif;?>
            </div>
            
        </li>
        <?php endforeach;?>
    </ul>
</div>


