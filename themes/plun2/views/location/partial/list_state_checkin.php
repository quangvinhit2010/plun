<?php if(sizeof($list_state) > 0): 
		$first_state	=	current($list_state);
	$list_state	=	CHtml::listData($list_state, 'id', 'name');
	$top_state	=	LocationState::model()->getTopStateByCountry($first_state['country_id']);
	$top_state	=	CHtml::listData($top_state, 'id', 'name');
	if(sizeof($top_state)){
		$list_state_group	=	array(' ' => $top_state, '------------' => $list_state);
	}else{
		$list_state_group	=	$list_state;
	}
			echo CHtml::dropDownList('state_id',0, $list_state_group, array('onchange' => 'getCityCheckIn();', 'id' => 'Checkin_state_id', 'class' => 'select-type-1 ci-state virtual_form', 'text' => 'state_register_text', 'empty' => Lang::t('search', '--Any--'))); 
	?>
	<span class="txt_select"><span class="state_checkin_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
	<?php endif; ?>