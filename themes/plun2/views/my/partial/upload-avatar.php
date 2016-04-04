<div class="wrap-upload-image">
	<img src="<?php echo $photo->getImageThumbnail425x320(true);?>" align="absmiddle" id="uploadPreview">
	<?php echo CHtml::hiddenField('photo_id', $photo->id, array('id'=>'photo_id'));?>
</div>

<script type="text/javascript">
<?php 

if($check_detail425x320){
//list($width, $height, $type, $attr) = getimagesize(Yii::getPathOfAlias ( 'pathroot' ) . DS .$photo->path .'/detail425x320/'. $photo->name);
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
                //position: 'relative'
            });      
        	$('.wrap-upload-image').animate({
        	    left: ((_width_frame/2) - (_w_jcrop/2)) + "px",
        	    top: ((_height_frame/2) - (_h_jcrop/2)) + "px",
        	  }, "slow");
  		});
    });
    
    function updateCoords(c)
    {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };
    
<?php 
}
?>
</script>
