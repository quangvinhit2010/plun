<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Purpleguy Rounds'=>array('index'),
	$model->id,
);

?>

<h1>View PurpleguyRound #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'round_name',
		'time_start',
		'time_end',
		'event_id',
	),
)); ?>
