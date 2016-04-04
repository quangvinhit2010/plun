<?php
	$type = Yii::app()->request->getParam('type', 'all');
?>
<div class="menu_hotbox">
	<ul>
		<li class="all">
			<a class="<?php if($type=='all') echo 'active '?>changeBg" href="<?php echo $this->createUrl('/hotbox') ?>"><?php echo Lang::t('hotbox', 'All') ?></a>
			<ol class="sub_menu_hotbox">
				<li><a class="<?php if($type=='photo') echo 'active '?>changeBg" href="<?php echo $this->createUrl('/hotbox/index', array('type'=>'photo')) ?>"><?php echo Lang::t('hotbox', 'Photo') ?></a></li>
				<li><a class="<?php if($type=='event') echo 'active '?>changeBg" href="<?php echo $this->createUrl('/hotbox/index', array('type'=>'event')) ?>"><?php echo Lang::t('hotbox', 'Events') ?></a></li>
			</ol>
		</li>
		<li><a href="<?php echo Yii::app()->createUrl('/hotbox/create') ?>" title="" class="changeBg <?php if(!Yii::app()->user->isGuest) echo 'showPopup_create_hotbox' ?>"><?php echo Lang::t('hotbox', 'Create New');?></a></li>
		<?php if(!Yii::app()->user->isGuest): ?>
		<li><a class="<?php if($type=='me') echo 'active '?>changeBg" href="<?php echo Yii::app()->createUrl('/hotbox/index/type/me') ?>"><?php echo Lang::t('hotbox', 'My Hotbox');?></a></li>
		<?php endif; ?>
		<li class="search_hotbox">
			<div class="container_search slideFalse">
				<div class="wrap_search">
					<form action="<?php echo Yii::app()->createUrl('/hotbox/search') ?>">
						<input type="text" placeholder="Search" name="keyword" class="txt_search_hotbox">
						<input type="submit" name="" class="but_search_hotbox" value="">
					</form>
				</div>
			</div>
		</li>
	</ul>
</div>