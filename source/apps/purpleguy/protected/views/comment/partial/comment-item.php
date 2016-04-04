<?php if ($item) {?>
<li>
	<a class="left" href="#"><img src="<?php echo $item->cmt->getAvatar();?>" width="31" height="31" align="absmiddle"></a>
    <p class="time"><?php echo Util::getElapsedTime($item->created_date) ?></p>
    <div class="left nick_info">
    	<p><a target="_blank" href="<?php echo VPurpleguy::model()->createUrl('/u/'.$item->cmt->getAliasName())?>" class="username"><?php echo $item->cmt->getAliasName();?></a></p>
        <p><?php echo $item->getContent() ?></p>
    </div>
</li>
<?php }?>