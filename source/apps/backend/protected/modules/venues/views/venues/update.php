<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Venues'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Venues', 'url'=>array('admin')),
	array('label'=>'Create Venues', 'url'=>array('create')),
);
?>

<h1>Update Venues "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form_edit', array('model'=>$model)); ?>