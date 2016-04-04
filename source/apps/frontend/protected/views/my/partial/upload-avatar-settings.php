<?php
if(empty($photo->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$photo->path .'/detail425x320/'. $photo->name)){
    $src = Yii::app()->createAbsoluteUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
} else {
    $src = Yii::app()->createAbsoluteUrl($photo->path .'/detail425x320/'. $photo->name);
}
?>
<div class="wrap-upload-image">
	<img src="<?php echo $src;?>" align="absmiddle" id="uploadPreview">
	<?php echo CHtml::hiddenField('photo_id', $photo->id, array('id'=>'photo_id'));?>
</div>

<script type="text/javascript">
<?php 

if(!empty($src)){
list($width, $height, $type, $attr) = getimagesize(Yii::getPathOfAlias ( 'pathroot' ) . DS .$photo->path .'/detail425x320/'. $photo->name);
?>
    $(function(){
        $('#uploadPreview').Jcrop({
        	allowSelect:     false,
            allowResize:     true,
            aspectRatio: 1,
            bgFade:     true,
            bgOpacity: .2,
            boxWidth: 425,
            boxHeight: 320,
            setSelect: [ 210, 210, 50, 50 ],
            onSelect: updateCoords
        });
    	$( "#uploadPreview" ).load(function() {
        	var _w_jcrop = $(this).width();
        	var _h_jcrop = $(this).height();
        	var _width_frame = 425;
        	var _height_frame = 320;
        	$('.wrap-upload-image').css({
                position: 'relative'
            });      
        	$('.wrap-upload-image').animate({
        	    left: ((_width_frame/2) - (_w_jcrop/2)) + "px",
        	    top: ((_height_frame/2) - (_h_jcrop/2)) + "px",
        	  }, "slow");
  		});
    });
    
    function updateCoords(c)
    {
        $('#x1').val(c.x);
        $('#y1').val(c.y);
        $('#w1').val(c.w);
        $('#h1').val(c.h);
    };
    
<?php 
}
?>


</script>
