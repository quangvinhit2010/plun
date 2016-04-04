<?php
/* @var $this PurpleguyContactController */
/* @var $model PurpleguyContact */

$this->breadcrumbs=array(
	'Purpleguy Contacts'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PurpleguyContact', 'url'=>array('index')),
	array('label'=>'Create PurpleguyContact', 'url'=>array('create')),
	array('label'=>'Update PurpleguyContact', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PurpleguyContact', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PurpleguyContact', 'url'=>array('admin')),
);
?>

<h1>View PurpleguyContact #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'email',
		'phone_number',
		'subject',
		'body',
		'create_time',
	),
)); ?>
