<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Sys Banners'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage Venues', 'url'=>array('admin')),
	array('label'=>'Create Venues', 'url'=>array('create')),
);
?>

<h1>View Venues #<?php echo $model->id; ?></h1>

<div class="view banner">
	<br />
	<b><?php echo CHtml::encode($model->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($model->title); ?>
	<br />
	<br />
	<b><?php echo CHtml::encode($model->getAttributeLabel('date_created')); ?>:</b>
	<?php echo date("d-m-Y", $model->date_created); ?>
	<br />


</div>
