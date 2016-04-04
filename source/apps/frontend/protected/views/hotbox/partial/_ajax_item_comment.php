<?php if(isset($comments['data'])) {?>
	<?php foreach ($comments['data'] as $comment) {?>
		<li class="comment-id-<?php echo $comment->id;?>">
			<?php if($comment->isAuthor()) { ?>
				<a class="del_comment" title="<?php echo Lang::t('hotbox', 'Delete');?>" onclick="Hotbox.delete_comment(<?php echo $comment->id;?>);"></a>
			<?php } ?>
			<a class="avatar" href="<?php echo $comment->author->getUserUrl();?>"><img src="<?php echo $comment->author->getAvatar();?>" align="absmiddle" width="40" height="40"></a>
		    <div class="list_comment_hb">
		    <p class="user"><a href="<?php echo $comment->author->getUserUrl();?>"><?php echo $comment->author->getDisplayName();?></a></p>                                                
		    <p><?php echo htmlspecialchars_decode($comment->content) ;?></p>
		    <p class="time"><?php echo Util::getElapsedTime($comment->created_date);?>
			    <?php 
			    	$is_cmt_like = Like::model()->isLike($comment->id, Like::LIKE_COMMENT, Yii::app()->user->id);
			    	$cmt_count = Like::model()->getCount($comment->id, Like::LIKE_COMMENT);
			    ?>
                <a><i class="ismall ismall-like"></i><span class="comment-<? echo $comment->id; ?>"><?php echo $cmt_count;?></span></a>
				<a rel="" href="javascript:void(0);" class="like_comment" onclick="Hotbox.like(<? echo $comment->id; ?>, 'comment');">
				<span id="comment-<? echo $comment->id; ?>"><?php echo ($is_cmt_like != false) ? Lang::t('hotbox', 'Unlike') : Lang::t('hotbox', 'Like');?></span>
				</a>
			</p>
			</div>
		</li>
	<?php } ?>
<?php } ?>
<?php if(isset($comments['next'])) {?>
<div class="next_page" style="display: none;" next_page="<?php echo $comments['next'];?>"></div>
<?php } ?>