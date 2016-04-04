<?php
/* @var $this HobbiesController */
/* @var $model SysHobbies */

$this->breadcrumbs=array(
	'Sys Hobbies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysHobbies', 'url'=>array('index')),
	array('label'=>'Manage SysHobbies', 'url'=>array('admin')),
);
?>

<h1>Create SysHobbies</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>