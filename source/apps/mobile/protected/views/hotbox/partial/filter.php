<div class="page-filter">
	<div class="search">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'hotbox-search-form',
			'action'=>Yii::app()->createUrl('/hotbox/search'),
			'method'=>'get',
		)); ?>
			<button><i class="imed imed-search"></i></button>
			<input type="text" name="keyword" placeholder="<?php echo Lang::t('hotbox', 'Search');?>..."/>
		<?php $this->endWidget(); ?>
	</div>
	<!-- search -->
	<div class="filter" id="filter" data-option-key="filter">
		<ul>
			<li>
				<a onclick="Hotbox.filter_hotbox('.hotbox-photo');"><?php echo Lang::t('hotbox', 'Photo');?></a>
			</li>
			<li>
				<a onclick="Hotbox.filter_hotbox('.hotbox-event');"><?php echo Lang::t('hotbox', 'Events');?></a>
			</li>
			<li>
				<a onclick="Hotbox.filter_hotbox('*');"><?php echo Lang::t('hotbox', 'All');?></a>
			</li>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/create');?>';" title=""><?php echo Lang::t('hotbox', 'Create HOTBOX');?></a>
			</li>
            <li>
				<a onclick="Hotbox.filter_hotbox('.hotbox-my');"><?php echo Lang::t('hotbox', 'My Hotbox');?></a>
			</li>
		</ul>
	</div>
	<!-- filter -->
</div>