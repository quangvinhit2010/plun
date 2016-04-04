<?php
/* @var $this ReferrerLogController */
/* @var $model ReferrerLog */

$this->breadcrumbs=array(
	'Referrer Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ReferrerLog', 'url'=>array('index')),
	array('label'=>'Create ReferrerLog', 'url'=>array('create')),
	array('label'=>'Update ReferrerLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ReferrerLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReferrerLog', 'url'=>array('admin')),
);
?>

<h1>View ReferrerLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type_referrer',
		'redirect_url',
		'referrer_url',
		'referrer_id',
		'user_id',
		'type_log',
		'created',
	),
)); ?>
