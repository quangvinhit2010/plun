<?php
/* @var $this ReferrerDefineController */
/* @var $model ReferrerDefine */

$this->breadcrumbs=array(
	'Referrer Defines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReferrerDefine', 'url'=>array('index')),
	array('label'=>'Manage ReferrerDefine', 'url'=>array('admin')),
);
?>

<h1>Create ReferrerDefine</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>