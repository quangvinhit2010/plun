<?php if(sizeof($list_city) > 0): ?>

	<select class="select-type-1 fh-city virtual_form" onchange="getDistrictFindHim();" id="txt-city" name="city_id" text="city_text">
		<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
		<?php foreach ($list_city AS $row): ?>
		    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
		<?php endforeach; ?>
	</select>
	<span class="txt_select"><span class="city_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
<?php endif; ?>