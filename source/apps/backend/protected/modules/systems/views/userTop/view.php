<?php
/* @var $this UserTopController */
/* @var $model UserTop */

$this->breadcrumbs=array(
	'User Tops'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserTop', 'url'=>array('index')),
	array('label'=>'Create UserTop', 'url'=>array('create')),
	array('label'=>'Update UserTop', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserTop', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserTop', 'url'=>array('admin')),
);
?>

<h1>View UserTop #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'username',
		'order',
	),
)); ?>
