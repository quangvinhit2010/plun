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
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/index/type/photo') ;?>';"><?php echo Lang::t('hotbox', 'Photo');?></a>
			</li>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/index/type/event') ;?>';"><?php echo Lang::t('hotbox', 'Events');?></a>
			</li>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/index') ;?>';"><?php echo Lang::t('hotbox', 'All');?></a>
			</li>
			<li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/create') ;?>';" title=""><?php echo Lang::t('hotbox', 'Create New');?></a>
			</li>
			<?php if(!Yii::app()->user->isGuest) { ?>
            <li>
				<a onclick="location.href='<?php echo Yii::app()->createUrl('//hotbox/index/type/me') ;?>';"><?php echo Lang::t('hotbox', 'My Hotbox');?></a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<!-- filter -->
</div>