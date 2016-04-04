<?php
/* @var $this EventController */
/* @var $model HotboxEvent */

$this->breadcrumbs=array(
	'Hotbox Events'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HotboxEvent', 'url'=>array('index')),
	array('label'=>'Manage HotboxEvent', 'url'=>array('admin')),
);
?>

<h1>Create HotboxEvent</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>