<?php if ($item) {?>
<li>
	<div class="avatar left"><a href="<?php echo $item->author->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $item->cmt->getAvatar(false);?>" /></a></div>
    <div class="left info">
    	<p class="nick"><?php echo $item->cmt->getUserLink(array('class' => 'username')); ?> <?php echo $item->getContent(); ?></p>
        <p class="time"><?php echo Util::getElapsedTime($item->created_date) ?></p>
    </div>
</li>
<?php }?>