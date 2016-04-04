<?php
/* @var $this BalanceController */
/* @var $model CrBalance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Manage Balance', 'url'=>array('admin')),
);
?>

<h1>Create CrBalance</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>