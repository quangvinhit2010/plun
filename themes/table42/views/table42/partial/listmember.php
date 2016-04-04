<ul class="clearfix">
	<?php 
		foreach($members AS $row):
	?>
	<li>
		<a href="javascript:void(0);" onclick="Tablefortwo.showProfiledetail(this);" data-effect-id="popup_show_detail" data-profileid="<?php echo $row->id; ?>">
			<span class="wrap_img"><img src="<?php echo $row->photo->getImageThumb203x204(true); ?>" width="155px" height="155px" /></span>
			<span class="name_user"><?php echo $row->username; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>