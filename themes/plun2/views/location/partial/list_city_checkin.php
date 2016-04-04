<?php if(sizeof($list_city) > 0): 
		$list_city = CHtml::listData($list_city, 'id', 'name');
		echo CHtml::dropDownList('city_id',0, $list_city, array('onchange' => 'getDistrictCheckIn();', 'id' => 'Checkin_city_id', 'class' => 'select-type-1 ci-city virtual_form', 'text' => 'city_checkin_text', 'empty' => Lang::t('search', '--Any--'))); 
?>
	<span class="txt_select"><span class="city_checkin_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
	
<?php endif; ?>
