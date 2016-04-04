<?php
/* @var $this TransactionController */
/* @var $model CrTransaction */

$this->breadcrumbs=array(
	'Cr Transactions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CrTransaction', 'url'=>array('index')),
	array('label'=>'Create CrTransaction', 'url'=>array('create')),
	array('label'=>'View CrTransaction', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CrTransaction', 'url'=>array('admin')),
);
?>

<h1>Update CrTransaction <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>