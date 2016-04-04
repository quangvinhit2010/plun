<div id="col2">
	<div id="container">
		<div class="alert_page">
			<p class="title left"><span><?php echo Lang::t('general', 'Bookmark');?></span></p>
			<div class="bookmark_list">
				<p class="number_bookmark"><?php echo Lang::t('bookmark', 'You have <b>{number}</b> bookmarked profiles', array('{number}'=>$pages->getItemCount()));?></p>
				<?php if(isset($bookmarks)) { ?>
					<ul class="list_user" id="bookmarks" page="<?php echo $next_page;?>">
						<?php foreach ($bookmarks as $bookmark ) {?>
						<li class="bookmark_item" id="<?php echo $bookmark->target_id;?>">
							<a href="<?php echo $bookmark->user->getUserUrl();?>"><img align="absmiddle" src="<?php echo $bookmark->user->getAvatar(); ?>?t=<?php echo time();?>"></a>
							<div class="info"><a href="<?php echo $bookmark->user->getUserUrl();?>"><?php echo $bookmark->user->getDisplayName(); ?></a></div>
						</li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if($pages->pageCount > 1) {?>
		<div class="block_loading"><a onclick="Bookmark.show_more();" href="javascript:void(0);"><span></span></a></div>
	<?php } ?>
</div> 

