<?php if(!Yii::app()->user->isGuest){?>
<div class="wrapper_main_menu left">
  <div  class="main_menu">
		<ul>
			<li class="main_menu_avatar">
				<a href="<?php echo $this->usercurrent->getUserUrl();?>" title="<?php echo $this->usercurrent->getDisplayName();?>">
					<img width="35px" height="35px" src="<?php echo $this->usercurrent->getAvatar(false) ?>" align="absmiddle" class="nav-username"/>
					<?php if($this->usercurrent->is_vip): ?>
						<i class="icon_common"></i>
					<?php endif; ?>
				</a>
			</li>
			<li class="main_menu_location">
				<a href="javascript:void(0);"><ins></ins></a>
			</li>
			<li class="main_menu_message<?php echo (VHelper::activeMenu(null, 'messages')) ? ' active' : '' ?>">
				<?php 
					$offlineMessages = OfflineMessages::model()->getOfflineMessages($this->usercurrent->id, $this->usercurrent->username);
					$totalOfflineMessage = count(array_filter(explode(',', $offlineMessages)));
				?>
				<a data-offline-message="<?php echo $offlineMessages ?>" href="<?php echo $this->usercurrent->createUrl('//messages/index', array('t'=>time())) ?>" class="nav-msg"><ins></ins>
					<?php if($totalOfflineMessage):?>
						<label class="count" style="display: inline;"><?php echo $totalOfflineMessage; ?></label>
					<?php else:?>
						<label class="count"></label>
					<?php endif;?>
				</a>
			</li>
			<li class="main_menu_alert<?php echo (VHelper::activeMenu(null, 'alerts')) ? ' active' : '' ?>">
				<a href="<?php echo $this->usercurrent->createUrl('//alerts/index');?>" class="nav-alert"><ins></ins>
					<?php if(!empty($this->e_user['total_alert'])):?>
						<label class="count" style="display: inline;"><?php echo $this->e_user['total_alert']; ?></label>
					<?php else:?>
						<label class="count"></label>
					<?php endif;?>
				</a>
			</li>
			<li class="main_menu_friend<?php echo (VHelper::activeMenu(null, 'friend')) ? ' active' : '' ?>">
				<a href="<?php echo $this->usercurrent->createUrl('//friend/index');?>" class="nav-friend"><ins></ins>
					<?php if(!empty($this->e_user['total_addfriend_request'])):?>
						<label class="count" style="display: inline;"><?php echo $this->e_user['total_addfriend_request']; ?></label>
					<?php else:?>
						<label class="count"></label>
					<?php endif;?>
				</a>
			</li>
			<li class="main_menu_photo<?php echo (VHelper::activeMenu(null, 'photo')) ? ' active' : '' ?>">
				<a href="<?php echo $this->usercurrent->createUrl('//photo/index');?>" class="nav-photo"><ins></ins>
					<?php if(!empty($this->e_user['total_photo_request'])):?>
						<label class="count" style="display: inline;"><?php echo $this->e_user['total_photo_request']; ?></label>
					<?php else:?>
						<label class="count"></label>
					<?php endif;?>
				</a>
			</li>
			<?php if(isset(Yii::app()->params->candy)): ?>
			<li class="main_menu_candy">
				<a href="<?php echo $this->usercurrent->createUrl('//candy/index');?>">
					<ins></ins>
					<?php if($this->usercurrent->balance && $this->usercurrent->balance->new_transaction): ?>
						<label class="count" style="display: inline;"><?php echo $this->usercurrent->balance->new_transaction ?></label>
					<?php endif; ?>
				</a>
			</li>
			<?php else: ?>
			<li class="main_menu_candy"><a href="javascript:;" class="coming-soon"><ins></ins></a></li>
			<?php endif; ?>
			<li class="main_menu_bookmark<?php echo (VHelper::activeMenu(null, 'bookmark')) ? ' active' : '' ?>"><a href="<?php echo $this->usercurrent->createUrl('//bookmark/index');?>"><ins></ins></a></li>
			<?php if(!empty(CParams::load()->params->visitor) && !empty(CParams::load()->params->visitor->menu)): ?>
			<li class="main_menu_visitor"><a class="coming-soon" href="javascript:;<?php //echo $this->usercurrent->createUrl('//visitor/index');?>"><ins></ins><span class="icon_new_fun icon_common"></span></a></li>
			<?php endif;?>
			<li class="main_menu_setting<?php echo (VHelper::activeMenu(null, 'settings')) ? ' active' : '' ?>"><a href="<?php echo $this->usercurrent->createUrl('//settings/index');?>"><ins></ins></a></li>
		</ul>
	</div>
</div>
<?php }?>