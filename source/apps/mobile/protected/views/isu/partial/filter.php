<div class="page-filter">
	<div class="filter" id="filter" data-option-key="filter">
		<ul>
			<li>
				<a onclick="ISU.filter('*');"><i class="ismall ismall-myisu"></i><span class="inline-text"><?php echo Lang::t('isu', 'ISU');?></span></a>
			</li>
			<li>
				<a onclick="ISU.filter('.isu-my');"><i class="ismall ismall-myisu"></i><span class="inline-text"><?php echo Lang::t('isu', 'My ISU');?></span></a>
			</li>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//isu/create');?>';"><i class="ismall ismall-isu"></i><span class="inline-text"><?php echo Lang::t('isu', 'Create ISU');?></span></a>
			</li>
		</ul>
	</div>
	<!-- filter -->
</div>