<?php
/* @var $this PurpleguyEventController */
/* @var $model PurpleguyEvent */

$this->breadcrumbs=array(
	'Purpleguy Events'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update PurpleguyEvent <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>