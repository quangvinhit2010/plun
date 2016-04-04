<div class="friend_request temp_fr">
  <ul>
	<li <?php echo ($this->action->id == 'request') ? 'class="active"' : '';?>>
		<a href="<?php echo $this->usercurrent->createUrl('//friend/request');?>"><?php echo Lang::t('general', 'Request'); ?></a>
	</li>
	<li class="mar_rig_10 <?php echo ($this->action->id == 'index') ? 'active' : '';?>">
		<a href="<?php echo $this->usercurrent->createUrl('//friend/index');?>"><?php echo Lang::t('general', 'Friends'); ?></a>
	</li>
  </ul>
</div>