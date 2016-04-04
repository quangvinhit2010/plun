<?php
/* @var $this EventController */
/* @var $model HotboxEvent */

$this->breadcrumbs=array(
	'Hotbox Events'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List HotboxEvent', 'url'=>array('index')),
	array('label'=>'Create HotboxEvent', 'url'=>array('create')),
	array('label'=>'Update HotboxEvent', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete HotboxEvent', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HotboxEvent', 'url'=>array('admin')),
);
?>

<h1>View HotboxEvent #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'is_always',
		'start',
		'end',
		'thumbnail',
		'status',
	),
)); ?>
