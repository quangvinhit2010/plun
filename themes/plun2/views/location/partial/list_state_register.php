<?php if(sizeof($list_state) > 0): 
?>
<td style="padding:16px 0 0 0;">
	<div class="select_style w160">
		<?php 
										$list_state = CHtml::listData($list_state, 'id', 'name');
										
										if ($country_id) {
											$top_state = LocationState::model()->getTopStateByCountry($country_id);
											$top_state = CHtml::listData($top_state, 'id', 'name');
											 									
											if (sizeof($top_state)) {
												$list_state_group = array('----------- ' => $top_state, '------------' => $list_state);
											} else {
												$list_state_group = $list_state;
											}
										} else {
											$list_state_group = $list_state;
										}
			echo CHtml::dropDownList('UsrProfileSettings[state_id]',0, $list_state_group, array('onchange' => 'getCityRegister();','name' => 'UsrProfileSettings[state_id]', 'id' => 'UsrProfileSettings_state_id', 'class' => 're-state virtual_form', 'text' => 'state_register_text', 'empty' => Lang::t('search', '--Any--'))); 
		?>	
		<span class="txt_select"><span class="state_register_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span> </div>
	</div>
</td>
<?php endif; ?>