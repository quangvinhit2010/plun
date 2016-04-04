<?php
/* @var $this ReferrerDefineController */
/* @var $model ReferrerDefine */

$this->breadcrumbs=array(
	'Referrer Defines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReferrerDefine', 'url'=>array('index')),
	array('label'=>'Create ReferrerDefine', 'url'=>array('create')),
	array('label'=>'View ReferrerDefine', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ReferrerDefine', 'url'=>array('admin')),
);
?>

<h1>Update ReferrerDefine <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>