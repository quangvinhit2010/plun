<?php 
?>
<li class="item status_row_<?php echo $data->id; ?>">
    <?php 
	    if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_POST_WALL){
	    	$status_text_is_me	=	true;
	    }else{
	    	$status_text_is_me	=	false;
	    } 
	    if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_PHOTO_UPLOAD){
	    	$status_photo_is_me	=	true;
	    }else{
	    	$status_photo_is_me	=	false;
	    }	       
    ?>
	<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
            'data'=>$data,
			'status_text_is_me'	=>	$status_text_is_me,
			'status_photo_is_me'	=>	$status_photo_is_me
    ));?>
</li>
<script type="text/javascript">
$('.feedLasted').attr('data-time', '<?php echo $data->timestamp;?>');
</script>