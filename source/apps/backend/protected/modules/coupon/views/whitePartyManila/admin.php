<h1>Manage White Party Manila</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'white-party-manila-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'user_name',
		'full_name',
		'gift_code',
		'phone',
		'id_no',
		array(
			'name' => 'createtime',
			'header' => 'Create Date',
			'value' => 'date("d-m-Y",$data->createtime)',   //where name is Client model attribute
		),
		'ip',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>
	$(function() {
		$(document).on('focus', '.filters td:eq(5) input', function(){
			$(this).datepicker({dateFormat: 'dd-mm-yy'});
		});
	});
</script>
