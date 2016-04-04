<?php if(isset($state[0])): ?>
	<?php
		echo CHtml::dropDownList('CmsVenues[state_id]', false, CHtml::listData( $state, 'id', 'name'), array('empty' => '-- Select state --', 'onchange' => 'changeState(this);'));
	?>
<?php endif; ?>