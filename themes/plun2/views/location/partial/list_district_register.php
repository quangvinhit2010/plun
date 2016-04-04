<?php if(sizeof($list_district) > 0): ?>
<td style="padding:16px 0 0 0;">
	<div class="select_style w160">
		<?php 
			$list_district = CHtml::listData($list_district, 'id', 'name');
			echo CHtml::dropDownList('UsrProfileSettings[district_id]',0, $list_district, array('name' => 'UsrProfileSettings[district_id]', 'id' => 'UsrProfileSettings_district_id', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
		?>   
		<span class="txt_select"><span class="district_register_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
	</div>
</td>
<?php endif; ?>