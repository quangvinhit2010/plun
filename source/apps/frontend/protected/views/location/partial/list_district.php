<?php if(sizeof($list_district) > 0): ?>

	<select class="select-type-1 fh-district virtual_form" id="txt-district" name="district_id" text="district_text">
	<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
	<?php foreach ($list_district AS $row): ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	 <?php endforeach; ?>
	 </select>
	 <span class="txt_select"><span class="district_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
<?php endif; ?>