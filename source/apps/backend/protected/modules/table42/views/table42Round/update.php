<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Table42 Rounds'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update Table42 Round <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>