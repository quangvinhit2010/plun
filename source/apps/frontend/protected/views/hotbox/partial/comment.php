<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'delete_hotbox', 'content'=>''));?>
<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'delete_comment', 'content'=>''));?>
<div class="comment_hb_detail">
	<div class="title_cm">
    	<div class="nav_left">							
             <ul>
             	<li>
					<a><i class="ismall ismall-like<?php echo (!$is_like) ? '-unactive' : null;?>"></i><span class="inline-text hotbox-<? echo $hotbox->id; ?>"><?php echo $hotbox->getLikeCount();?></span></a>
				</li>
				<li>
					<a><i class="ismall ismall-comment"></i><span class="inline-text comment-hotbox-<? echo $hotbox->id; ?>"><?php echo $hotbox->getCommentCount();?></span></a>
				</li>
				<li>
					<?php if(Yii::app()->user->isGuest) {?>
						<a href="javascript:void(0);" class="like_comment">
					<?php } else { ?>
						<a class="like_comment" onclick="Hotbox.like(<? echo $hotbox->id; ?>, 'hotbox');" href="javascript:void(0);" rel="">
							<span id="hotbox-<? echo $hotbox->id; ?>" class="inline-text">
							<?php 
		                		if(!empty($is_like)){
		                			echo Lang::t('hotbox', 'Unlike');
		                		} else {
		                			echo Lang::t('hotbox', 'Like');
		                		}
		                	?>
							</span>
						</a>
					<?php } ?>
				</li>
				<li>
					<?php if(Yii::app()->user->isGuest) {?>
						<a class="btn-comment" href="javascript:void(0);"><span class="inline-text"><?php echo Lang::t('hotbox', 'Comment');?></span></a>
					<?php } else { ?>
						<a class="btn-comment" href="javascript:void(0);" onclick="Hotbox.focus_comment(this);" ><span class="inline-text"><?php echo Lang::t('hotbox', 'Comment');?></span></a>
					<?php } ?>
				</li>
			</ul>
		</div>
		<?php if(isset($comments['next']) && $comments['next'] > 1) {?>
			<div class="nav_right">
	        	<a onclick="Hotbox.load_more_comment(<?php echo $hotbox->id;?>);" next_page="<?php echo $comments['next'];?>"><?php echo Lang::t('hotbox', 'View previous comments')?></a>
			</div>
		<?php } ?>
	</div>
	<div class="content">
		<ul id="comment_list_hotbox">
			<?php if(isset($comments['data'])){?>
				<?php foreach ($comments['data'] as $comment) {?>
					<li class="comment-id-<?php echo $comment->id;?>">
						<?php if($comment->isAuthor()) { ?>
							<a class="del_comment" title="<?php echo Lang::t('hotbox', 'Delete');?>" onclick="Hotbox.delete_comment(<?php echo $comment->id;?>);"></a>
						<?php } ?>
			            <a class="avatar" href="<?php echo $comment->author->getUserUrl();?>"><img src="<?php echo $comment->author->getAvatar();?>" align="absmiddle" width="40" height="40"></a>
			            <div class="list_comment_hb">
			            	<p class="user"><a href="<?php echo $comment->author->getUserUrl();?>"><?php echo $comment->author->getDisplayName();?></a></p>                                                
			                <p><?php echo htmlspecialchars_decode($comment->content);?></p>
			                <p class="time">
			                <?php echo Util::getElapsedTime($comment->created_date);?>
							<?php $is_cmt_like = Like::model()->isLike($comment->id, Like::LIKE_COMMENT, Yii::app()->user->id);?>
			                <a><i class="ismall ismall-like<?php echo (!$is_cmt_like) ? '-unactive' : null;?>"></i><span class="comment-<? echo $comment->id; ?>"><?php echo $comment->getLikeHotboxCount();?></span></a>
							<a rel="" href="javascript:void(0);" class="like_comment" onclick="Hotbox.like(<? echo $comment->id; ?>, 'comment');">
								<span id="comment-<? echo $comment->id; ?>"><?php echo ($is_cmt_like != false) ? Lang::t('hotbox', 'Unlike') : Lang::t('hotbox', 'Like');?></span>
							</a>
							</p>
		            	</div>
					</li>
				
				<?php } ?>
			<?php } ?>
		</ul>
		<div class="message-input">
			<form method="post" action="" id="replymsg-form">
	        	<div class="textarea-wrap">
	            	<textarea rel="<?php echo $hotbox->id;?>"  id="comment_area" placeholder="<?php echo (Yii::app()->user->isGuest) ? Lang::t('hotbox', 'Please login to leave comment') : Lang::t('hotbox', 'Write a reply...') ;?>" class="replyMsg<?php echo $this->clsAccNotActived;?>" name="body"></textarea>
				</div>
			</form>
		</div>
	</div>                                
</div>