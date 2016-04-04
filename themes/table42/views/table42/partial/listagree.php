<ul class="clearfix">
	<?php 
		foreach($members AS $row):
		if($row->inviter->user_id == Yii::app()->user->id){
			$inviter	=	$row->friend;
		}else{
			$inviter	=	$row->inviter;
		}
		if(!$row->is_dating){
			$class_active	=	'';
		}else{
			$class_active	=	' active';
		}
	?>
	<li class="profile-agree-<?php echo $inviter->id; ?><?php echo $class_active;?>">
		<a href="javascript:void(0);" onclick="Tablefortwo.showProfileChooseDating(this);" data-contain-class="profile-agree-<?php echo $inviter->id; ?>" data-effect-id="popup_chose_ketdoi" data-profileid="<?php echo $inviter->id; ?>">
			<span class="wrap_img"><img src="<?php echo $inviter->photo->getImageThumb203x204(true); ?>" width="155px" height="155px" /></span>
			<span class="name_user"><?php echo $inviter->username; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>