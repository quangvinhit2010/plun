<?php
/* @var $this PurpleguyPhotoController */
/* @var $model PurpleguyPhoto */

$this->breadcrumbs=array(
	'Purpleguy Photos'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update PurpleguyPhoto <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>