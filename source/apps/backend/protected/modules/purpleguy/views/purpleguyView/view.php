<?php
/* @var $this PurpleguyViewController */
/* @var $model PurpleguyView */

$this->breadcrumbs=array(
	'Purpleguy Views'=>array('index'),
	$model->id,
);

?>

<h1>View PurpleguyView #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'view_by',
		'user_id',
		'round_id',
		'created',
		'ip',
	),
)); ?>
