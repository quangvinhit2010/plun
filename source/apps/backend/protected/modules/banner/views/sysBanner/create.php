<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Sys Banners'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Banner', 'url'=>array('admin')),
	array('label'=>'Create Banner', 'url'=>array('create')),
);
?>

<h1>Create Banner</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>