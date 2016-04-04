<?php if(sizeof($list_city) > 0): ?>
<label class="row-label">&nbsp;</label>
<div class="setting-row-right">
	<select class="select-type-1 sl-city" onchange="getDistrictSettings();" id="UsrProfileSettings_city_id" name="city_id">
		<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
	<?php foreach ($list_city AS $row): ?>
	    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>
