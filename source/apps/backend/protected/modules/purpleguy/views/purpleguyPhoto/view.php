<?php
/* @var $this PurpleguyPhotoController */
/* @var $model PurpleguyPhoto */

$this->breadcrumbs=array(
	'Purpleguy Photos'=>array('index'),
	$model->name,
);

?>

<h1>View PurpleguyPhoto #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'title',
		'name',
		'path',
		'status',
		'order',
		'created',
		'updated',
	),
)); ?>
