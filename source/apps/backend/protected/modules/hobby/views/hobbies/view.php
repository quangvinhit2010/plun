<?php
/* @var $this HobbiesController */
/* @var $model SysHobbies */

$this->breadcrumbs=array(
	'Sys Hobbies'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SysHobbies', 'url'=>array('index')),
	array('label'=>'Create SysHobbies', 'url'=>array('create')),
	array('label'=>'Update SysHobbies', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SysHobbies', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysHobbies', 'url'=>array('admin')),
);
?>

<h1>View SysHobbies #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'content',
		'date_created',
		'date_updated',
		'published',
	),
)); ?>
