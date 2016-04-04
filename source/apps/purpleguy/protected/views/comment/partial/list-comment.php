<?php if(!empty($data['data'])){?>
<?php foreach ($data['data'] as $sitem) {
?>
<li>
	<a class="left" href="#"><img src="<?php echo $sitem->cmt->getAvatar();?>" width="31" height="31" align="absmiddle"></a>
    <p class="time"><?php echo Util::getElapsedTime($sitem->created_date) ?></p>
    <div class="left nick_info">
    	<p><a target="_blank" href="<?php echo VPurpleguy::model()->createUrl('/u/'.$sitem->cmt->getAliasName())?>" class="username"><?php echo $sitem->cmt->getAliasName();?></a></p>
        <p><?php echo $sitem->getContent() ?> </p>
    </div>
</li>
<?php }?>
<?php }?>