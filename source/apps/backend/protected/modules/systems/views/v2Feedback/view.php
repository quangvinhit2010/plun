<?php
/* @var $this V2FeedbackController */
/* @var $model V2Feedback */

$this->breadcrumbs=array(
	'V2 Feedbacks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List V2Feedback', 'url'=>array('index')),
	array('label'=>'Create V2Feedback', 'url'=>array('create')),
	array('label'=>'Update V2Feedback', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete V2Feedback', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage V2Feedback', 'url'=>array('admin')),
);
?>

<h1>View V2Feedback #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'username',
		'level',
		'message',
		'created',
		'updated',
	),
)); ?>
