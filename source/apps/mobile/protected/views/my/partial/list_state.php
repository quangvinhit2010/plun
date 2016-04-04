<?php if(sizeof($list_state) > 0): ?>
<label for="txt-city">
	&nbsp;
</label>
<select class="select-type-1 fh-state" onchange="getCityFindHim();" id="txt-state" name="state_id">
	<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
	<?php foreach ($list_state AS $row): ?>
	    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php endforeach; ?>
</select>
<?php endif; ?>