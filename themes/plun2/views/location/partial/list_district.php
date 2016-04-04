<?php if(sizeof($list_district) > 0): ?>
	<option value=""><?php echo Lang::t('search', '--Any--'); ?></option>
	<?php foreach ($list_district AS $row): ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	 <?php endforeach; ?><?php endif; ?>