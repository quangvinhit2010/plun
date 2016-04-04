<?php
/* @var $this UserTopController */
/* @var $model UserTop */

$this->breadcrumbs=array(
	'User Tops'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserTop', 'url'=>array('index')),
	array('label'=>'Create UserTop', 'url'=>array('create')),
	array('label'=>'View UserTop', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserTop', 'url'=>array('admin')),
);
?>

<h1>Update UserTop <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>