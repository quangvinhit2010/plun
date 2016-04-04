<?php
/* @var $this PhotoController */
/* @var $model HotboxPhoto */

$this->breadcrumbs=array(
	'Hotbox Photos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HotboxPhoto', 'url'=>array('index')),
	array('label'=>'Manage HotboxPhoto', 'url'=>array('admin')),
);
?>

<h1>Create HotboxPhoto</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>