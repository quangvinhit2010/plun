<?php
/* @var $this PurpleguyViewController */
/* @var $model PurpleguyView */

$this->breadcrumbs=array(
	'Purpleguy Views'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update PurpleguyView <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>