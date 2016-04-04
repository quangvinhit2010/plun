<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Purpleguy Rounds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update PurpleguyRound <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>