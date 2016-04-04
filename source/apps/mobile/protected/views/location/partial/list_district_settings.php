<?php if(sizeof($list_district) > 0): ?>
<label class="row-label">&nbsp;</label>
<div class="setting-row-right">
	<select class="select-type-1 ps-district" id="UsrProfileSettings_district_id" name="district_id">
	<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
	<?php foreach ($list_district AS $row): ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	 <?php endforeach; ?>
	 </select>
</div>
<?php endif; ?>