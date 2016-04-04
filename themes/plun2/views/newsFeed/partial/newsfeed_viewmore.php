<?php if ($activities['data']) { ?>
    <?php
    foreach ($activities['data'] as $data) {
    							//is my status
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
							if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_CHECK_IN){
								$checkin_is_me	=	true;
							}else{
								$checkin_is_me	=	false;
							}
			if(is_object($data)):					
        ?>
        
        <!-- single news feed item -->
        <li class="profile-feeds item item_showmore status_row_<?php echo $data->id; ?>">
            <?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                    'data'=>$data,
            		'status_text_is_me'	=>	$status_text_is_me,
            		'status_photo_is_me'	=>	$status_photo_is_me,
            		'checkin_is_me'	=>	$checkin_is_me
            ));?>
        </li>
        <!-- single news feed item -->
    <?php 
    		endif;
    	} ?>
<?php } else { ?>
<?php
}?>
<?php if(!$show_more){ ?>
<script type="text/javascript">
    if($('#newsfeed_offset_after').attr('id')){
        $('#newsfeed_offset_after').remove();
    }
    NewsFeed.hide_showmore_bt();
</script>
<?php } ?>
<input type="hidden" value="<?php echo $offset; ?>" name="newsfeed_offset_after" id="newsfeed_offset_after" /> 