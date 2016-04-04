<?php if ($item) {?>
<li class="sub-item">
	<div class="wrap clearfix">
		<a href="javascript:void(0);" title="" class="sub-ava"><?php echo $item->cmt->getAvatar(true);?></a>
		<span class="time"><?php echo Util::getElapsedTime($item->created_date) ?></span>
		<div class="sub-info">
			<h5><?php echo $item->cmt->getUserLink(array('class' => 'username')); ?></h5>
			<p class="text"><?php echo $item->getContent() ?></p>
		</div>
	</div>
</li>
<?php }?>