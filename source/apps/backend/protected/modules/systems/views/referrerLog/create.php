<?php
/* @var $this ReferrerLogController */
/* @var $model ReferrerLog */

$this->breadcrumbs=array(
	'Referrer Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReferrerLog', 'url'=>array('index')),
	array('label'=>'Manage ReferrerLog', 'url'=>array('admin')),
);
?>

<h1>Create ReferrerLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>