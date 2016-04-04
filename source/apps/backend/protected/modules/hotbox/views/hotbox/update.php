<?php
/* @var $this HotboxController */
/* @var $model Hotbox */

$this->breadcrumbs=array(
	'Hotboxes'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Hotbox', 'url'=>array('index')),
	array('label'=>'Create Hotbox', 'url'=>array('create')),
	array('label'=>'View Hotbox', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Hotbox', 'url'=>array('admin')),
);
?>

<h1>Update Hotbox <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>