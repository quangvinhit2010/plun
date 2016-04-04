<?php if(isset($city[0])): ?>
	<?php
		echo CHtml::dropDownList('CmsVenues[city_id]', false, CHtml::listData( $city, 'id', 'name'), array('empty' => '-- Select city --', 'onchange' => 'changeCity(this);'));
	?>
<?php endif; ?>