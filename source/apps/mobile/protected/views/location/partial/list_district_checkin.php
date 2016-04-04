<?php if(sizeof($list_district) > 0):
$first_district	=	 current($list_district);
?>

	<select class="select-type-1 ci-district" id="Checkin_district_id" name="district_id">
	<?php foreach ($list_district AS $row): ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	 <?php endforeach; ?>
	 </select>
<span class="txt_select"><span><?php echo (isset($first_district['name'])) ? $first_district['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>

<?php endif; ?>