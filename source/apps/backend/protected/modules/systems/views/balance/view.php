<?php
/* @var $this BalanceController */
/* @var $model Balance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Create Balance', 'url'=>array('create')),
	array('label'=>'Update Balance', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Balance', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Balance', 'url'=>array('admin')),
);
?>

<h1>View Balance #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'current_balance',
		'available_balance',
	),
)); ?>
