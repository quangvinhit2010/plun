<?php
/* @var $this BalanceController */
/* @var $model CrBalance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Create CrBalance', 'url'=>array('create')),
	array('label'=>'View CrBalance', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CrBalance', 'url'=>array('admin')),
);
?>

<h1>Update CrBalance <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>