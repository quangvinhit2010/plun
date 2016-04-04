<?php 

$mobileMEdiaFilter = new MobileMediaFilter();

if(!empty($data)):
$params = json_decode($data->params, true);
$model = new Comment();
$object_id = $data->id;
$type_id = Comment::COMMENT_ACTIVITY;
	
$config = Comment::ConfigView();
	
/* people comment */
$fcomment = Comment::model()->getComments($object_id, $type_id);
$dataobj = htmlspecialchars(json_encode(array('object_id' => Util::encrypt($object_id), 'action' => Util::encrypt($data->action))));
$urlLiked = $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY));
?>
<div class="main_status">
	<div class="left avatar">
		<a href="javascript:void(0);"><img width="50" src="<?php echo $data->owner->getAvatar() ?>" align="absmiddle" /></a>
	</div>
	<div class="left info loadingItem">
		<p class="nick"><a href="javascript:void(0);"><?php echo $data->getHeader(); ?></a></p>
		<p class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></p>
		<?php if( $data->getMessage()): ?>
		<p>
			<?php echo $mobileMEdiaFilter->filter($data->getMessage()); ?>
		</p>
		<?php endif;?>
		<ol class="function left">                                    	
			<li class="link_like"><a href="javascript:void(0);" class="like_comment" rel="<?php echo $this->usercurrent->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><?php echo $data->getLikeState() ?></a></li>
			<li class="link_comment"><a href="javascript:void(0);"><?php echo Lang::t('newsfeed', 'Comment')?></a></li>
			<li class="num_like"><a class="<?php if($data->is_like){?>active<?php }?>" href="javascript:void(0);" data-url="<?php echo $urlLiked; ?>" data-offset="0"><ins></ins><span class="inline-text"><?php echo !empty($data->stats->like_count) ? $data->stats->like_count : 0; ?></span></a></li>
			<li class="num_comment"><a class="active" href="javascript:void(0);"><ins></ins><span class="inline-text"><?php echo !empty($data->stats->comment_count) ? $data->stats->comment_count : 0 ?></span></a></li>
		</ol>
	</div>                        
</div>    
<ol class="comment_list">
	<?php
	if ($fcomment){
		$params['data'] = $fcomment;
		$params['config'] = $config;
		$params['object_id'] = $object_id;
		$params['type_id'] = $type_id;
		$params['object'] = $dataobj;
		
		$this->renderPartial("//newsFeed/partial/list-comment", $params);
	}
	?>
</ol> 
<?php if(!empty($this->usercurrent)){?>
<div class="comment_feed left">
	<?php $form=$this->beginWidget('CActiveForm', array(
	    'action' => $this->usercurrent->createUrl("//newsFeed/commentActivity"),
	    'htmlOptions' => array(
	        	'class' => 'comment-form'
	        )
	)); ?>
	<div class="avatar left"><a href="javascript:void(0);"><img align="absmiddle" width="35" src="<?php echo $this->usercurrent->getAvatar() ?>"></a></div>
	<div class="">
		<?php echo $form->hiddenField($model,'item',array('value'=> $dataobj)); ?>
		<?php echo $form->textArea($model,'content', array('class' => 'cmt-post-text expand', 'placeholder' => Lang::t('newsfeed', 'Write a comment...'))); ?>
	</div>
	<?php $this->endWidget();?>
</div>
<?php }?>
<?php endif;?>
