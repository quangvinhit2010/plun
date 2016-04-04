<?php
/* @var $this HistoryController */
/* @var $model InviteHistory */

$this->breadcrumbs=array(
	'Invite Histories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InviteHistory', 'url'=>array('index')),
	array('label'=>'Create InviteHistory', 'url'=>array('create')),
	array('label'=>'Update InviteHistory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InviteHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InviteHistory', 'url'=>array('admin')),
);
?>

<h1>View InviteHistory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'invited_email',
		'invited_id',
		'message',
		'type',
		'status',
		'created',
	),
)); ?>
