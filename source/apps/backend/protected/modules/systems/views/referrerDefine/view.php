<?php
/* @var $this ReferrerDefineController */
/* @var $model ReferrerDefine */

$this->breadcrumbs=array(
	'Referrer Defines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ReferrerDefine', 'url'=>array('index')),
	array('label'=>'Create ReferrerDefine', 'url'=>array('create')),
	array('label'=>'Update ReferrerDefine', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ReferrerDefine', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReferrerDefine', 'url'=>array('admin')),
);
?>

<h1>View ReferrerDefine #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'domain',
		'register_count',
		'login_count',
	),
)); ?>
