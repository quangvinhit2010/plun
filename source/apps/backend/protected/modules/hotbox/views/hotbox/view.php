<?php
/* @var $this HotboxController */
/* @var $model Hotbox */

$this->breadcrumbs=array(
	'Hotboxes'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Hotbox', 'url'=>array('index')),
	array('label'=>'Create Hotbox', 'url'=>array('create')),
	array('label'=>'Update Hotbox', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Hotbox', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Hotbox', 'url'=>array('admin')),
);
?>

<h1>View Hotbox #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'type_id',
		'title',
		'slug',
		'description',
		array(
			'name'=>'body',
			'type'=>'html',
		),
		'meta_description',
		'meta_keywords',
		'author_id',
		'thumbnail_id',
		'public_time',
		'status',
		'view',
		'created',
		'modify',
	),
)); ?>
