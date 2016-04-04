<?php
/* @var $this PurpleguyProfileController */
/* @var $model PurpleguyProfile */

$this->breadcrumbs=array(
	'Purpleguy Profiles'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update PurpleguyProfile <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'photos'=>$photos)); ?>