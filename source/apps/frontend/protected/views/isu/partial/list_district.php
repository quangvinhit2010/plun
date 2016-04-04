	<option value=""><?php echo Lang::t('isu', '-- Select District --');?></option>
<?php foreach ($list_district AS $row): ?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php endforeach; ?>