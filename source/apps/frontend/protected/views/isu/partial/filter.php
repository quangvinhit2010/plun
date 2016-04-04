<?php Yii::app()->user->setFlash('msgLogin', Lang::t('login', 'Please sign-in to use this feature!'));?>
<div class="page-filter">
	<div class="filter" id="filter" data-option-key="filter">
		<ul>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//isu/index') ;?>';"><i class="ismall ismall-myisu"></i><span class="inline-text"><?php echo Lang::t('isu', 'ISU');?></span></a>
			</li>
			<?php if(!Yii::app()->user->isGuest) { ?>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//isu/index/type/me') ;?>';"><i class="ismall ismall-myisu"></i><span class="inline-text"><?php echo Lang::t('isu', 'My ISU');?></span></a>
			</li>
			<?php } ?>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//isu/create') ;?>';"><i class="ismall ismall-isu"></i><span class="inline-text"><?php echo Lang::t('isu', 'Create ISU');?></span></a>
			</li>
		</ul>
	</div>
	<!-- filter -->
</div>