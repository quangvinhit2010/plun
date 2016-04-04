<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Table42 Rounds'=>array('admin'),
	$model->user_id=>array('view','user_id'=>$model->user_id),
	'Update',
);

?>

<h1>Update Table42 Profile <?php echo $model->user_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>