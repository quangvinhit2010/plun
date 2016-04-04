<?php
/* @var $this PurpleguyProfileController */
/* @var $model PurpleguyProfile */

$this->breadcrumbs=array(
	'Purpleguy Profiles'=>array('index'),
	$model->id,
);
?>

<h1>View PurpleguyProfile #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'username',
		'fullname',
		'phone',
		'email',
		'thumbnail_id',
		'status',
	),
)); ?>
