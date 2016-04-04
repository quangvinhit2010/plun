<?php
/* @var $this WhitePartyManilaController */
/* @var $model WhitePartyManila */

$this->breadcrumbs=array(
	'White Party Manilas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WhitePartyManila', 'url'=>array('index')),
	array('label'=>'Create WhitePartyManila', 'url'=>array('create')),
	array('label'=>'View WhitePartyManila', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WhitePartyManila', 'url'=>array('admin')),
);
?>

<h1>Update WhitePartyManila <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>