<?php
	$this->widget ( 'zii.widgets.grid.CGridView', array (
		'dataProvider' => $report,
		'columns'=>array(
			array( 'name'=>'Date', 'header'=>'Date', ),
			array( 'name'=>'Total', 'header'=>'Total User Registered', ),
		),
	));
?>
<div style="margin-bottom: 16px; margin-top: 8px; font-weight: bolder; text-align: center;">Total All: <span><?php echo $total ?></span></div>