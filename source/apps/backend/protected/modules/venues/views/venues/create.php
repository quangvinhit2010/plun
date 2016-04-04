<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Venues'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Venues', 'url'=>array('admin')),
	array('label'=>'Create Venues', 'url'=>array('create')),
);
?>

<h1>Create Ventues</h1>

<?php $this->renderPartial('_form_create', array('model'=>$model)); ?>