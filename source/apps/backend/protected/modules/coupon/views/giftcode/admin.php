<h1>Giftcodes</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'giftcode-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header' => 'UserName',
			'name' => 'username',
			'value' => '$data->getUserName()',
		),
		array(
			'name' => 'code',
		),
		array(
			'name' => 'event_id',
			'filter' => CHtml::listData(Events::model()->findAll(), 'id', 'title')	,
			'value' => '($data->event) ? $data->event->title : ""',
			'htmlOptions' => array('style' => 'text-align:center;') 	
		),
		array(
			'name' => 'type',
			'filter' => array(1 => Giftcode::TYPE_SYSTEM, 2 => Giftcode::TYPE_MARKETING),
			'value' => '($data->type == 1) ? Giftcode::TYPE_SYSTEM : Giftcode::TYPE_MARKETING',
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		array(
			'name' => 'status',
			'filter' => array(0 => 'Not Yet', 1 => 'Used'),
			'value' => '$data->getStatus()',
			'htmlOptions' => array('style' => 'text-align:center;') 	
		),
	),
)); ?>
