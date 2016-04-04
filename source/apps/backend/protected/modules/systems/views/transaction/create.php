<?php
/* @var $this TransactionController */
/* @var $model CrTransaction */

$this->breadcrumbs=array(
	'Cr Transactions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CrTransaction', 'url'=>array('index')),
	array('label'=>'Manage CrTransaction', 'url'=>array('admin')),
);
?>

<h1>Create CrTransaction</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>