<div class="popup_create_hotbox">
	<div class="title"><?php echo Lang::t('hotbox', 'Create New') ?></div>
	<div class="content">
		<div class="create_step_1">
			<a class="create_hb_photo showcreate_hotbox_event" href="#" data-type="<?php echo Hotbox::PHOTO ?>"><?php echo Lang::t('hotbox', 'Photo') ?></a>
			<a class="create_hb_event showcreate_hotbox_event" href="#" data-type="<?php echo Hotbox::EVENT ?>"><?php echo Lang::t('hotbox', 'Event') ?></a>
		</div>
	</div>
</div>
<?php 
	$this->renderPartial('partial/create_hotbox',array(
		'model'=>$model,
		'list_country' => $list_country,
		'list_city' => $list_city,
		'list_state' => $list_state,
		'list_district' => $list_district,
	), false, true);
?>