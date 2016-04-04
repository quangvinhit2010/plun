<?php foreach ($comments['data'] as $comment): ?>
<li>
	<a href="<?php echo $comment->author->getUserUrl() ?>" class="avatar" target="_blank">
		<img width="40" height="40" align="absmiddle" src="<?php echo $comment->author->getAvatar() ?>">
	</a>
	<div class="list_comment_hb">
		<p class="user"><a target="_blank" href="<?php echo $comment->author->getUserUrl() ?>"><?php echo $comment->author->getDisplayName() ?></a></p>
		<p><?php echo htmlspecialchars_decode($comment->content) ?></p>
		<p class="time"><?php echo Util::getElapsedTime($comment->created_date) ?>
			<?php $isLike = Like::model()->isLike($comment->id, Like::LIKE_COMMENT, Yii::app()->user->id) ?>
			<a>
				<i class="ismall ismall-like-unactive<?php if($isLike) echo ' active' ?>"></i>
				<span class="comment-like-num"><?php echo $comment->getLikeHotboxCount() ?></span>
			</a>
			<a class="like_comment like" data-id="<?php echo $comment->id ?>" data-type="comment"  href="<?php echo $this->createUrl('/hotbox/like') ?>" rel=""> <span><?php echo ($isLike) ? Lang::t('hotbox', 'Unlike') : Lang::t('hotbox', 'Like');?></span></a>
		</p>
	</div>
</li>
<?php endforeach; ?>