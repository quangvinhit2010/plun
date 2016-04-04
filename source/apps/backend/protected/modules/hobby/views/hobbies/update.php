<?php
/* @var $this HobbiesController */
/* @var $model SysHobbies */

$this->breadcrumbs=array(
	'Sys Hobbies'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysHobbies', 'url'=>array('index')),
	array('label'=>'Create SysHobbies', 'url'=>array('create')),
	array('label'=>'View SysHobbies', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SysHobbies', 'url'=>array('admin')),
);
?>

<h1>Update SysHobbies <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>