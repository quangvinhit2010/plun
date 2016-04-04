<?php
/* @var $this EventController */
/* @var $model HotboxEvent */

$this->breadcrumbs=array(
	'Hotbox Events'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HotboxEvent', 'url'=>array('index')),
	array('label'=>'Create HotboxEvent', 'url'=>array('create')),
	array('label'=>'View HotboxEvent', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage HotboxEvent', 'url'=>array('admin')),
);
?>

<h1>Update HotboxEvent <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>