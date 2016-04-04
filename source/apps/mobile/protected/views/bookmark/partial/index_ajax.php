			<?php foreach ($bookmarks as $bookmark ) {?>
				<li class="bookmark_item" id="<?php echo $bookmark->target_id;?>">
					<a href="<?php echo $bookmark->user->getUserUrl();?>"><img align="absmiddle" src="<?php echo $bookmark->user->getAvatar(); ?>?t=<?php echo time();?>"></a>
					<div class="info"><a href="<?php echo $bookmark->user->getUserUrl();?>"><?php echo $bookmark->user->getDisplayName(); ?></a></div>
				</li>
			<?php } ?>
			<div style="display: none;" id="next_page" page="<?php echo $next_page;?>"></div>
