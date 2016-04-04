<?php foreach ($hotboxs as $hotbox): ?>
<li id="hotbox-<?php echo $hotbox->id ?>" class="<?php echo $class ?>">
	<?php
		$image = $hotbox->getImage($originalImage);
		if(is_string($image)) :
	?>
	<p class="hotbox-detail">
		<a href="<?php echo $hotbox->createUrl() ?>" data-srcImg="<?php echo $image; ?>" class="wrap-img-loading wrap_img_hotbox">
			<img width="100%" src="" align="absmiddle" />
		</a>
	</p>
	<?php endif; ?>
	<div class="info">
		<p class="title hotbox-detail">
			<a href="<?php echo $hotbox->createUrl() ?>"><?php echo $hotbox->title ?></a>
		</p>
		<ol>
			<li class="active"><ins class="like"></ins> <?php echo Lang::t('hotbox', 'Like') ?> (<span class="like-num"><?php echo $hotbox->getLikeCount() ?></span>)</li>
			<li class="active"><ins class="comment"></ins><span class="txtBlock"><?php echo Lang::t('hotbox', 'Comment') ?></span><span class="comment-num">(<?php echo $hotbox->getCommentCount() ?>)</span></li>
		</ol>
		<div class="poster left">
			<p class="nickname left">
				<a target="_blank" href="<?php echo $hotbox->author->getUserUrl() ?>"><img class="hotbox_ava" src="<?php echo $hotbox->author->getAvatar(false); ?>" align="absmiddle" /> <?php echo $hotbox->author->getDisplayName() ?></a>
			</p>
			<p class="time right"><?php echo date('d/m/Y', $hotbox->created);?></p>
		</div>
	</div>
	<?php if($hotbox->author_id == Yii::app()->user->id && $hotbox->status == Hotbox::PENDING): ?>
	<a data-type="<?php echo $hotbox->type ?>" href="<?php echo $this->createUrl('hotbox/edit', array('id'=>$hotbox->id)) ?>" class="icon_edit"></a>
	<?php endif; ?>
	<span class="<?php echo ($hotbox->type == 1) ? 'icon_event' : 'icon_pic' ?>"></span>
</li>
<?php endforeach; ?>