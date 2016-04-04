<?php if(sizeof($list_city) > 0): ?>
<label for="txt-city">
	&nbsp;
</label>
<select class="select-type-1 fh-city" onchange="getDistrictFindHim();" id="txt-city" name="city_id">
	<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
<?php foreach ($list_city AS $row): ?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>