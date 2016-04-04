<?php
/* @var $this PurpleguyContactController */
/* @var $model PurpleguyContact */

$this->breadcrumbs=array(
	'Purpleguy Contacts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PurpleguyContact', 'url'=>array('index')),
	array('label'=>'Create PurpleguyContact', 'url'=>array('create')),
	array('label'=>'View PurpleguyContact', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PurpleguyContact', 'url'=>array('admin')),
);
?>

<h1>Update PurpleguyContact <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>