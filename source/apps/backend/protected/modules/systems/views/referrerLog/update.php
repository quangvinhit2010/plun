<?php
/* @var $this ReferrerLogController */
/* @var $model ReferrerLog */

$this->breadcrumbs=array(
	'Referrer Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReferrerLog', 'url'=>array('index')),
	array('label'=>'Create ReferrerLog', 'url'=>array('create')),
	array('label'=>'View ReferrerLog', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ReferrerLog', 'url'=>array('admin')),
);
?>

<h1>Update ReferrerLog <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>