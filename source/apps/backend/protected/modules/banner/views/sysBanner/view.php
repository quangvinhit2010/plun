<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Sys Banners'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage Banner', 'url'=>array('admin')),
	array('label'=>'Create Banner', 'url'=>array('create')),
);
?>

<h1>View Banner #<?php echo $model->id; ?></h1>

<div class="view banner">
	<b>Image</b>
	<img style="vertical-align: middle;" src="<?php echo $model->full_path ?>" />
	<br />
	<br />
	<b><?php echo CHtml::encode($model->getAttributeLabel('url')); ?>:</b>
	<?php echo CHtml::encode($model->url); ?>
	<br />
	<br />
	<b><?php echo CHtml::encode($model->getAttributeLabel('created')); ?>:</b>
	<?php echo date("d-m-Y", $model->created); ?>
	<br />
	<br />
	<b><?php echo CHtml::encode($model->getAttributeLabel('status')); ?>:</b>
	<?php echo $model->mapStatus(); ?>
	<br />


</div>
