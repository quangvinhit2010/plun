<div class="poster_hotbox" data-id="<?php echo $hotbox->id ?>">
	<a target="_blank" class="left avatar" href="<?php echo $hotbox->author->getUserUrl() ?>"><img src="<?php echo $hotbox->author->getAvatar(false) ?>" align="absmiddle" width="50px" height="50px" /></a>
	<div class="left nick">
		<a target="_blank" href="<?php echo $hotbox->author->getUserUrl() ?>"><b><?php echo $hotbox->author->getDisplayName() ?></b></a>
		<?php if($hotbox->author_id == Yii::app()->user->id && $hotbox->status == Hotbox::PENDING) {?>
			<div class="public_hotbox" style="position: absolute; right: 34px; top: 10px; color: red;"><?php echo Lang::t('hotbox', 'This post is pending for approval');?></div>
		<?php } ?>
		<p class="time"><?php echo Util::getElapsedTime($hotbox->created) ?></p>
		<?php if($hotbox->type == Hotbox::EVENT): ?>
		<p class="detail_time"><?php if($hotbox->events): ?><span><b>Location:</b> <?php echo $hotbox->events->getLocation() ?></span> <b>Từ:</b> <?php echo date('d-m-Y H:i', $hotbox->events->start) ?> - <b>Đến:</b> <?php echo date('d-m-Y H:i', $hotbox->events->end) ?><?php endif; ?></p>
		<?php endif; ?>
	</div>
</div>
<div class="wrap_scroll_popup">
	<?php
		$image = $hotbox->getImage(true);
		if($image):
	?>
	<div class="pics_hotbox">
		<img src="<?php echo $image ?>" align="absmiddle" />
	</div>
	<?php endif; ?>
	<div class="detail_hotbox">
		<h3><?php echo $hotbox->title ?></h3>
		<div class="content">
			<?php echo $hotbox->body ?>
		</div>
		<div class="left share"><b>Chia sẻ:</b> <input onfocus="this.select();" onmouseup="return false;" name="" type="text" value="<?php echo $hotbox->createUrl(true) ?>" /></div>
	</div>   
	<div class="clear"></div>                                 
	<div class="comment_hotbox">
		<div class="title">
			<ol class="function">
				<?php if(Yii::app()->user->isGuest): ?>
				<li class="num_like"><a style="cursor: default;"><ins></ins><span id="like-num"><?php echo $hotbox->getLikeCount() ?></span></a></li>
				<li class="num_comment"><a><ins></ins><span id="comment-num"><?php echo $hotbox->getCommentCount() ?></span></a></li>
				<?php else: ?>
				<li class="num_like"><a <?php echo ($is_like) ? 'class="active" ' : ''?>style="cursor: default;" href="javascript:;"><ins></ins><span id="like-num"><?php echo $hotbox->getLikeCount() ?></span></a></li>
				<li class="num_comment"><a><ins></ins><span id="comment-num"><?php echo $hotbox->getCommentCount() ?></span></a></li>
				<li class="link_like">
					<a class="like" data-id="<?php echo $hotbox->id ?>" data-type="hotbox" href="<?php echo $this->createUrl('/hotbox/like') ?>"><?php echo ($is_like) ? Lang::t('hotbox', 'Unlike') : Lang::t('hotbox', 'Like') ?></a>
				</li>
				<?php endif; ?>
				<?php if($comments): ?>
				<li class="right"><a <?php echo ($comments['pages']->pageCount > 1) ? '' : 'style="display: none;" ' ?>data-id="<?php echo $hotbox->id ?>" id="load_more_comment" href="<?php echo $this->createUrl('/hotbox/listComment') ?>" data-next="<?php echo $comments['next'];?>"><?php echo Lang::t('hotbox', 'View previous comments') ?></a></li>
				<?php endif; ?>
			</ol>
		</div>
		<ul class="list_comment left">
			<?php if(isset($comments['data'])): ?>
				<?php $this->renderPartial('partial/comment', array('comments' => $comments));?>
			<?php endif; ?>
		</ul>
		<div class="message-input">
			<form id="replymsg-form" action="<?php echo $this->createUrl('/hotbox/comment') ?>" method="post">
				<?php
					if(!Yii::app()->user->isGuest):
						$userCurrent =  Yii::app()->user->data();
				?>
				<a href="#" title="nickname"><img src="<?php echo $userCurrent->getAvatar(false) ?>" align="absmiddle" width="40" height="40" /></a>
				<?php else: ?>
				<a href="#" title="nickname"><img src="/public/images/no-user.jpg" align="absmiddle" width="40" height="40" /></a>
				<?php endif; ?>
				<textarea data-id="<?php echo $hotbox->id ?>" <?php if(Yii::app()->user->isGuest) echo 'onclick="location.href=\''.Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl('hotbox'))).'\'" readonly="readonly" ' ?>name="body" class="replyMsg" placeholder="<?php echo (Yii::app()->user->isGuest) ? Lang::t('hotbox', 'Please login to leave comment') : Lang::t('hotbox', 'Write a reply...') ?>" id="comment_area" rel="292"></textarea>
			</form>
		</div>
	</div>
</div>