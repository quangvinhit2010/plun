<?php $type = Yii::app()->request->getParam('type', 'all') ?>
<div class="menu_hotbox">
	<ul>
		<li><a href="<?php echo Yii::app()->createUrl('//isu') ;?>" class="<?php if($type == 'all') echo 'active '?>"><?php echo Lang::t('isu', 'ISU') ?></a></li>
		<li><a href="<?php echo Yii::app()->createUrl('//isu/index/type/me') ?>" class="<?php if($type=='me') echo 'active '?>"><?php echo Lang::t('isu', 'My ISU') ?></a></li>
		<li><a href="<?php echo Yii::app()->createUrl('//isu/create') ;?>" class="<?php if(!Yii::app()->user->isGuest) echo 'showPopup_create_isu' ?>"><?php echo Lang::t('isu', 'Create ISU') ?></a></li>
	</ul>
</div>