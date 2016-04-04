<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Category'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Category', 'url'=>array('admin')),
	array('label'=>'Create Category', 'url'=>array('create')),
);
?>

<h1>Create Category</h1>

<?php $this->renderPartial('_form_create', array('model'=>$model)); ?>