<?php
/* @var $this TransactionController */
/* @var $model CrTransaction */

$this->breadcrumbs=array(
	'Cr Transactions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CrTransaction', 'url'=>array('index')),
	array('label'=>'Create CrTransaction', 'url'=>array('create')),
	array('label'=>'Update CrTransaction', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CrTransaction', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CrTransaction', 'url'=>array('admin')),
);
?>

<h1>View CrTransaction #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'amount',
		'type',
		'status',
		'error_code',
		'error_message',
		'created',
		'updated',
	),
)); ?>
