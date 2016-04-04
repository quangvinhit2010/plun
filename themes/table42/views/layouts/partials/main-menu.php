<?php if(!Yii::app()->user->isGuest){?>
<?php 
	$userCurrent =  Yii::app()->user->data();
	$params = CParams::load ();
?>
<div class="wrapper_main_menu left">
  <div  class="main_menu">
		<ul>
			<li class="main_menu_avatar">
				<a href="<?php echo $params->params->base_url . $userCurrent->getUserUrl();?>" title="<?php echo $userCurrent->getDisplayName();?>">
					<img width="35px" height="35px" src="<?php echo $userCurrent->getAvatar(false) ?>" align="absmiddle" class="nav-username"/>
					<?php if($userCurrent->is_vip): ?>
						<i class="icon_common"></i>
					<?php endif; ?>
				</a>
			</li>
			<li class="main_menu_location">
				<a href="javascript:void(0);"><ins></ins></a>
				<?php $this->widget('frontend.widgets.popup.Checkin', array()); ?>
			</li>
			<li class="main_menu_message"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//messages/index');?>" class="nav-msg"><ins></ins><label class="count"></label></a></li>
			<li class="main_menu_alert"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//alerts/index');?>" class="nav-alert"><ins></ins><label class="count"></label></a></li>
			<li class="main_menu_friend"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//friend/index');?>" class="nav-friend"><ins></ins><label class="count"></label></a></li>
			<li class="main_menu_photo"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//photo/index');?>" class="nav-photo"><ins></ins><label class="count"></label></a></li>
			<li class="main_menu_candy"><a href="javascript:void(0);" class="coming-soon"><ins></ins></a></li>
			<li class="main_menu_bookmark"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//bookmark/index');?>"><ins></ins></a></li>
			<li class="main_menu_setting"><a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//settings/index');?>"><ins></ins></a></li>
		</ul>
	</div>
</div>
<?php }?>