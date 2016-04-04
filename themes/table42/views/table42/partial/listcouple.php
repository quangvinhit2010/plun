<ul class="clearfix">
	<?php 
		foreach($members AS $row):
	?>
	<li>
		<a href="javascript:void(0);" onclick="Tablefortwo.showCoupleDetail(this);" data-effect-id="popup_ketdoi" data-requestid="<?php echo $row->id; ?>">
			<span>
				<span class="wrap_img"><img src="<?php echo $row->inviter->photo->getImageThumb203x204(true); ?>" width="155px" height="155px"/></span>
				<span class="name_user"><?php echo $row->inviter->username; ?></span>
			</span>
			<span>
				<span class="wrap_img"><img src="<?php echo $row->friend->photo->getImageThumb203x204(true); ?>" width="155px" height="155px"/></span>
				<span class="name_user"><?php echo $row->friend->username; ?></span>
			</span>
			<div class="icon_table42 icon_heart"></div>
		</a>
	</li>
	<?php endforeach; ?>
</ul>