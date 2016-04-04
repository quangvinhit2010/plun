<?php
if(empty($photo->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$photo->path .'/thumb275x275/'. $photo->name)){
    $src = Yii::app()->createAbsoluteUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
} else {
    $src = Yii::app()->createAbsoluteUrl($photo->path .'/thumb275x275/'. $photo->name);
}
?>
<div class="wrap-upload-image">
	<img src="<?php echo $src;?>" align="absmiddle" width="221">
	<?php echo CHtml::hiddenField('photo_id', $photo->id, array('id'=>'photo_id'));?>
</div>

<script type="text/javascript">
<?php 

if(!empty($src)){
list($width, $height, $type, $attr) = getimagesize($src);
if($width > 221){
    $width = 221;
}

?>
    $(function(){
      
        	var _w_jcrop = <?php echo $width;?>;
        	var _h_jcrop = <?php echo $height;?>;        	
        	var _width_frame = 221;
        	var _height_frame = 306;
        	$('.wrap-upload-image').css({
                position: 'relative'
            });      
        	$('.wrap-upload-image').animate({
        	    left: ((_width_frame/2) - (_w_jcrop/2)) + "px",
        	    top: ((_height_frame/2) - (_h_jcrop/2)) + "px",
        	  }, "slow");
    });
    
<?php 
}
?>


</script>
