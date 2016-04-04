<?php
/* @var $this HotboxController */
/* @var $model Hotbox */

$this->breadcrumbs=array(
	'Hotboxes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Hotbox', 'url'=>array('index')),
	array('label'=>'Manage Hotbox', 'url'=>array('admin')),
);
?>

<h1>Create Hotbox</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>