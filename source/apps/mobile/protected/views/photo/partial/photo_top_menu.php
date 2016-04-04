<div class="tab_myphoto">
  <ul>
	<li class="mar_rig_10 publicphoto <?php echo ($type == Photo::PUBLIC_PHOTO) ? 'active' : '';?>">
		<a class="publicphoto" href="<?php echo $this->usercurrent->createUrl('//photo/index', array('type' => Photo::PUBLIC_PHOTO)); ?>">&nbsp;</a>
	</li>
	<li class="mar_rig_10 vaultphoto <?php echo ($type == Photo::VAULT_PHOTO) ? 'active' : '';?>">
		<a class="vaultphoto" href="<?php echo $this->usercurrent->createUrl('//photo/index', array('type' => Photo::VAULT_PHOTO)); ?>">&nbsp;</a></li>
	<li class="mar_rig_10 privatephoto"><a class="privatephoto"  href="javascript:void(0);">&nbsp;</a></li>
	
	<li <?php echo ($this->action->id == 'myrequest') ? 'class="active"' : '';?>>
		<a href="<?php echo $this->usercurrent->createUrl('//photo/myrequest'); ?>">
			<?php echo Lang::t('photo', 'Request');?>
		</a>
	</li>
  </ul>
</div>