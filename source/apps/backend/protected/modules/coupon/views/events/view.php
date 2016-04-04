<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->title,
);
?>

<h1>View Events #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'item',
		array(
			'name' => 'start',
			'value' => date('d/m/Y',$model->start),
		),
		array(
			'name' => 'end',
			'value' => date('d/m/Y',$model->end),
		),
		array(
			'name' => 'created',
			'value' => date('d/m/Y',$model->created),
		),
	),
)); ?>
