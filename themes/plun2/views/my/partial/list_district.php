<?php if(sizeof($list_district) > 0): ?>
<label for="txt-city">
	&nbsp;
</label>
<select class="select-type-1 fh-district" id="txt-district" name="district_id">
<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
<?php foreach ($list_district AS $row): ?>
<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
 <?php endforeach; ?>
 </select>
<?php endif; ?>