<?php
/* @var $this PhotoController */
/* @var $model HotboxPhoto */

$this->breadcrumbs=array(
	'Hotbox Photos'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HotboxPhoto', 'url'=>array('index')),
	array('label'=>'Create HotboxPhoto', 'url'=>array('create')),
	array('label'=>'View HotboxPhoto', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage HotboxPhoto', 'url'=>array('admin')),
);
?>

<h1>Update HotboxPhoto <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>