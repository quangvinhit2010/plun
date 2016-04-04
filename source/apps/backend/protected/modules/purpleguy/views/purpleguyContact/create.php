<?php
/* @var $this PurpleguyContactController */
/* @var $model PurpleguyContact */

$this->breadcrumbs=array(
	'Purpleguy Contacts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PurpleguyContact', 'url'=>array('index')),
	array('label'=>'Manage PurpleguyContact', 'url'=>array('admin')),
);
?>

<h1>Create PurpleguyContact</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>