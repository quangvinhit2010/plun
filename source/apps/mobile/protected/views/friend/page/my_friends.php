<?php $this->renderPartial('partial/menu');?>

<div class="pad_left_10 pad_top_10">                  
	<div class="left list_friend_request">
		<p class="title"><?php echo sprintf(Lang::t('friend', 'You have %d friends'), $myfriend_list['total_friends']); ?></p>
		<?php if($myfriend_list['total_friends']){ ?>
			<ul class="list_user">
				 <?php foreach($myfriend_list['dbrow'] AS $item) { ?>
	                 <?php $item->invited = (Yii::app()->user->id == $item->invited->id) ? $item->inviter : $item->invited;?>
					<li>
						<a href="<?php echo $item->invited->getUserUrl();?>">
						<img src="<?php echo $item->invited->getAvatar(); ?>?t=<?php echo time();?>" align="absmiddle"/>	
						</a>
						
						<div class="info"><a href="<?php echo $item->invited->getUserUrl();?>"><?php echo $item->invited->getDisplayName(); ?></a></div>
					</li>
				<?php } ?>
			</ul>
			<?php if($show_more_myfriendlist) { ?>
				<input type="hidden" id="offset" value="<?php echo $limit_friendlist; ?>" />
				<input type="hidden" id="limit" value="<?php echo $limit_friendlist; ?>" />
				<div class="block_loading"><a href="javascript:showmore_friendlist();"><span></span></a></div>
			<?php } ?>
		<?php } else { ?>
			<p class="no-friends">
            	<?php echo Lang::t('friend', 'No friend requests pending'); ?>
            </p>
		<?php } ?>   
	</div>
</div>
	
  