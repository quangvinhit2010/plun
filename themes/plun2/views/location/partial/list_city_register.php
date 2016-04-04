<?php if(sizeof($list_city) > 0): 
?>
<td style="padding:16px 0 0 0;">
	<div class="select_style w160">
			<select onchange="getDistrictRegister();" class="re-city virtual_form" id="UsrProfileSettings_city_id" name="UsrProfileSettings[city_id]" text="city_register_text">
				<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
				<?php foreach ($list_city AS $row): ?>
			   	 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				<?php endforeach; ?>
			</select>
			<span class="txt_select"><span class="city_register_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span> </div>
	</div>
</td>
<?php endif; ?>
