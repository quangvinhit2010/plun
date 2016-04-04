<?php
/* @var $this UserTopController */
/* @var $model UserTop */

$this->breadcrumbs=array(
	'User Tops'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserTop', 'url'=>array('index')),
	array('label'=>'Manage UserTop', 'url'=>array('admin')),
);
?>

<h1>Create UserTop</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>