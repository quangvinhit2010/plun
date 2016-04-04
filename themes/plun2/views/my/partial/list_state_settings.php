<?php if(sizeof($list_state) > 0): ?>
<label class="row-label">&nbsp;</label>
<div class="setting-row-right">
	<select class="select-type-1 sl-state" onchange="getCitySettings();" id="UsrProfileSettings_state_id" name="state_id">
		<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
		<?php foreach ($list_state AS $row): ?>
		    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>