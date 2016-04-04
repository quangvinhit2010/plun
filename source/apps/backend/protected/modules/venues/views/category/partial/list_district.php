<?php if(isset($district[0])): ?>
	<?php
		echo CHtml::dropDownList('CmsVenues[district_id]', false, CHtml::listData( $district, 'id', 'name'), array('empty' => '-- Select district --'));
	?>
<?php endif; ?>