<?php 
//$cs = Yii::app()->clientScript;
//$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/photo.js?t=' .time(), CClientScript::POS_END);
$mediaFilter = new MediaFilter();
$mediaFilter->filterYoutube = CParams::load()->params->mediaFilter->filterYoutube;
$mediaFilter->filterZing = CParams::load()->params->mediaFilter->filterZing;
$mediaFilter->filterNhaccuatui = CParams::load()->params->mediaFilter->filterNhaccuatui;

$params = json_decode($data->params, true);
$model = new Comment();

$object_id = $data->id; 
$type_id = Comment::COMMENT_ACTIVITY;


$photos = $data->getObject(4);

/* people comment */
$dataobj = htmlspecialchars(json_encode(array('object_id' => Util::encrypt($object_id), 'action' => Util::encrypt($data->action))));
?>
<div class="wrap_newfeed clearfix">
<div class="avatar_feed left"><a href="<?php echo $data->member->getUserUrl();?>"><img class="lazy" data-original="<?php echo $data->member->getAvatar(false) ?>" align="absmiddle" width="35px" height="35px" onerror="$(this).attr('src','/public/images/no-user.jpg');" /></a></div>

<!--  checkin status -->
<?php 
	if($data->action == Activity::LOG_CHECK_IN): 
	$url	=	Yii::app()->createUrl('venues/getVenueDetail', array('venue_id' => $data->object_id));
?>
<div class="left info loadingItem">
	<p class="nick"><?php echo $data->getHeader() ?>
		<?php echo Lang::t('venue', 'Check-in at'); ?> <a class="popupListCheckIn" href="<?php echo $url; ?>"><?php echo $params['{message}']; ?><?php echo $params['{venue}']; ?></a>
	</p>
    <p class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></p>
    <?php if(!empty($params['{message}'])): ?>
    	<p><?php echo $params['{message}']; ?></p>
	<?php endif; ?>
    
   	<?php if(!empty(CParams::load()->params->newsfeed->public_like_comment) || $this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()): 
		$urlLiked = $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY));
	?>
	<ol class="function left">                                    	
		<li class="link_like"><a href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><?php echo $data->getLikeState() ?></a></li>
		<li class="link_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>" class="btn-comment"><?php echo Lang::t('newsfeed', 'Comment')?></a></li>
		<li class="num_like"><a href="javascript:void(0);"<?php echo ($data->is_like)	?	' class="active"'	:	''; ?> data-url="<?php echo $urlLiked; ?>" data-offset="0"><ins></ins><span><?php echo !empty($data->stats->like_count) ? $data->stats->like_count : 0; ?></span></a></li>
		<li class="num_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>"><ins></ins><span><?php echo!empty($data->stats->comment_count) ? $data->stats->comment_count : 0 ?></span></a></li>
	</ol>
	<?php endif; ?>
</div>
<?php endif; ?>
<!--  end check-in status -->

<!-- post post photo status -->
<?php if($data->action == Activity::LOG_PHOTO_UPLOAD): ?>
<div class="left info loadingItem">
	<p class="nick">
		<?php echo $data->getHeader() ?>
	</p>
	<p class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></p>
	<?php if ($data->getMessage()): 
			if(!empty($photos)):
	?>
			<div class="list_photo_upload">
				<?php foreach ($photos as $photo):?>
					 <a title="" href="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" class="list-preview-photo" rel="list-preview-photo<?php echo $data->id; ?>">
					 	<?php /*echo $photo->getImageThumbnail(false, array('width' => '50', 'height' => '50', 'data-rid' => '30'));*/?>
                        <img class="lazy" width="50" height="50" align="absmiddle" data-original="<?php echo $photo->getImageThumbnail(true);?>" onerror="$(this).attr('src','/public/images/no-image.jpg');" >
					 </a> 
		        <?php endforeach; ?>
	        </div>
	<?php 
			endif;
	 	endif; 
	 ?>
	
	<?php if(!empty(CParams::load()->params->newsfeed->public_like_comment) || $this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()): 
	$urlLiked = $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY));
	?>
	<ol class="function left">                                    	
		<li class="link_like"><a href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><?php echo $data->getLikeState() ?></a></li>
		<li class="link_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>" class="btn-comment"><?php echo Lang::t('newsfeed', 'Comment')?></a></li>
		<li class="num_like"><a href="javascript:void(0);"<?php echo ($data->is_like)	?	' class="active"'	:	''; ?> data-url="<?php echo $urlLiked; ?>" data-offset="0"><ins></ins><span><?php echo !empty($data->stats->like_count) ? $data->stats->like_count : 0; ?></span></a></li>
		<li class="num_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>"><ins></ins><span><?php echo!empty($data->stats->comment_count) ? $data->stats->comment_count : 0 ?></span></a></li>
	</ol>
	<?php endif; ?>
</div>
<?php endif; ?>
<!--  end post photo status -->

<!-- post content on wall -->
<?php if($data->action == Activity::LOG_POST_WALL): ?>
<div class="left info loadingItem">
	<p class="nick">
		<?php echo $data->getHeader() ?>
	</p>
	<p class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></p>
	<?php if ($data->getMessage()): ?>
		<?php if(empty($photos)): ?>
			<p class="text text_status_<?php echo $data->id;?>"><?php echo $mediaFilter->filter(htmlspecialchars($data->getMessage())); ?></p>
			<?php if($status_text_is_me): ?>
			<div class="edit_feed edit_status edit_status_<?php echo $data->id;?>" style="display: none;">
				<div class="content_edit">
					<textarea rows="3" cols="30"><?php echo $data->getMessage(); ?></textarea>
				</div>
				<div class="save_edit">
					<div class="newfeed-emo-wrap">
						<div class="emoticons-item edit"><span class="emo emo-1"></span><span class="emo emo-2"></span><span class="emo emo-3"></span><span class="emo emo-4"></span><span class="emo emo-5"></span><span class="emo emo-6"></span><span class="emo emo-7"></span><span class="emo emo-8"></span><span class="emo emo-9"></span><span class="emo emo-10"></span><span class="emo emo-11"></span><span class="emo emo-12"></span><span class="emo emo-13"></span><span class="emo emo-14"></span><span class="emo emo-15"></span><span class="emo emo-16"></span><span class="emo emo-17"></span><span class="emo emo-18"></span><span class="emo emo-19"></span><span class="emo emo-20"></span><span class="emo emo-21"></span></div>
						<span class="emoticons"></span>
					</div>
					<a class="edit_status_bt" href="javascript:void(0);" onclick="NewsFeed.sendEditStatus('<?php echo $data->id; ?>');"><?php echo Lang::t('general', 'Save');?></a>
					<a class="cancel_status_bt" href="javascript:void(0);" onclick="NewsFeed.closeEditStatus('<?php echo $data->id; ?>');"><?php echo Lang::t('general', 'Cancel');?></a>
				</div>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php if(!empty(CParams::load()->params->newsfeed->public_like_comment) || $this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()): 
	$urlLiked = $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY));
	?>
	<ol class="function left">                                    	
		<li class="link_like"><a href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><?php echo $data->getLikeState() ?></a></li>
		<li class="link_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>" class="btn-comment"><?php echo Lang::t('newsfeed', 'Comment')?></a></li>
		<li class="num_like"><a href="javascript:void(0);"<?php echo ($data->is_like)	?	' class="active"'	:	''; ?> data-url="<?php echo $urlLiked; ?>" data-offset="0"><ins></ins><span><?php echo !empty($data->stats->like_count) ? $data->stats->like_count : 0; ?></span></a></li>
		<li class="num_comment"><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/listComments", array('oid' => Util::encrypt($object_id), 'type' => Util::encrypt($type_id))); ?>"><ins></ins><span><?php echo!empty($data->stats->comment_count) ? $data->stats->comment_count : 0 ?></span></a></li>
	</ol>
	<?php endif; ?>
</div>
<?php endif; ?>
<!--  end post content on wall -->

<?php if($status_text_is_me || $status_photo_is_me || $checkin_is_me): ?>
	<a href="javascript:void(0);" class="icon_list_func_feed"></a>
	<div class="list_func_feed">
		<ol>
			<?php if($status_text_is_me): ?>
	        	<li><a href="javascript:void(0);" onclick="NewsFeed.editStatus('<?php echo $data->id; ?>');"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Edit');?></span></a></li>
	        <?php endif; ?>
	        	<li><a href="javascript:void(0);" onclick="NewsFeed.delStatusConfirm('<?php echo $data->id; ?>');"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Delete');?></span></a></li>
		</ol>
	</div>
<?php endif; ?>
</div>
<div class="comment_list hideBox">
	<ol></ol>
						
	<?php if (!empty($this->usercurrent)): ?>				
	<div class="txt_cmt_box">
	    	<div class="avatar_feed left"><a href="<?php echo $this->usercurrent->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $this->usercurrent->getAvatar() ?>" alt="" border=""/></a></div>
	        <?php $form = $this->beginWidget('CActiveForm', array(
					'action' => $this->user->createUrl("//newsFeed/commentActivity"),
					'htmlOptions' => array(
						'class' => 'comment-form'
					)
				));
			?>
	        <div class="comment_feed loadingItem">
				<?php echo $form->hiddenField($model, 'item', array('value' => $dataobj)); ?>
				<?php echo $form->textArea($model, 'content', array('class' => 'cmt-post-text expand', 'placeholder' => 'Write a comment...', 'id'=>'')); ?>
	        </div>
	        <?php $this->endWidget(); ?>
	</div>
	<?php endif; ?>
</div>