	<option value=""><?php echo Lang::t('isu', '-- Select City --');?></option>
<?php foreach ($list_city AS $row): ?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php endforeach; ?>