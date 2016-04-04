<?php
/* @var $this PhotoController */
/* @var $model HotboxPhoto */

$this->breadcrumbs=array(
	'Hotbox Photos'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List HotboxPhoto', 'url'=>array('index')),
	array('label'=>'Create HotboxPhoto', 'url'=>array('create')),
	array('label'=>'Update HotboxPhoto', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete HotboxPhoto', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HotboxPhoto', 'url'=>array('admin')),
);
?>

<h1>View HotboxPhoto #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'hotbox_id',
		'title',
		'description',
		'name',
		'path',
		'status',
		'sort',
		'created',
	),
)); ?>
