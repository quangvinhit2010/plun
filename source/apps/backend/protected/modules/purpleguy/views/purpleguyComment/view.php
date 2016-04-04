<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Purpleguy Votes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PurpleguyVote', 'url'=>array('index')),
	array('label'=>'Create PurpleguyVote', 'url'=>array('create')),
	array('label'=>'Update PurpleguyVote', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PurpleguyVote', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PurpleguyVote', 'url'=>array('admin')),
);
?>

<h1>View PurpleguyVote #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'vote_by',
		'user_id',
		'round_id',
	),
)); ?>
