<div class="data">
    <?php if ($activities) {?>
    <ul class="feed-list-update">
    	<?php 
    	foreach ($activities as $data) {
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
    	?>
    	<!-- single news feed item -->
    	<li class="item">
    	    <span class="marginline margin-top"></span>
    		<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                    'data'=>$data,
    				'status_text_is_me'	=>	$status_text_is_me,
    				'status_photo_is_me'	=>	$status_photo_is_me    				
            ));?>
    	    <span class="marginline margin-bottom"></span>
    	</li>
    	<?php }?>
    </ul>
    <?php }?>
    <?php 
	$time = '';
	if(!empty($activities[0])){
	    $time = $activities[0]->timestamp;
	}
	?>
    <div class="feedLasted" data-time="<?php echo $time;?>"></div>
</div>