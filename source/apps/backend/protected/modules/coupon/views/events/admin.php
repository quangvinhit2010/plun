
<h1>Manage Events</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'events-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		array(
			'name' => 'start',
			'value' => 'date(\'d/m/Y\',$data->start)',
			'htmlOptions' => array('style' => 'text-align:center;')  
		),
		array(
			'name' => 'end',
			'value' => 'date(\'d/m/Y\',$data->end)' ,
			'htmlOptions' => array('style' => 'text-align:center;') 
		),
		array(
				'name' => 'enabled',
				'value' => '($data->enabled == 1) ? "Enabled" : "Disabled"',
				'htmlOptions' => array('style' => 'text-align:center;')
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
