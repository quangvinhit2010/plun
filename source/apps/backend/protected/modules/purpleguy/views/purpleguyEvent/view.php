<?php
/* @var $this PurpleguyEventController */
/* @var $model PurpleguyEvent */

$this->breadcrumbs=array(
	'Purpleguy Events'=>array('index'),
	$model->id,
);

?>

<h1>View PurpleguyEvent #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_name',
		'description',
		'enable',
		'created',
	),
)); ?>
