<?php
/* @var $this TimelineRateController */
/* @var $model InviteTimelineRate */

$this->breadcrumbs=array(
	'Invite Timeline Rates'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List InviteTimelineRate', 'url'=>array('index')),
	array('label'=>'Create InviteTimelineRate', 'url'=>array('create')),
	array('label'=>'Update InviteTimelineRate', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InviteTimelineRate', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InviteTimelineRate', 'url'=>array('admin')),
);
?>

<h1>View InviteTimelineRate #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'rate',
		'fromdate',
		'todate',
		'active',
	),
)); ?>
