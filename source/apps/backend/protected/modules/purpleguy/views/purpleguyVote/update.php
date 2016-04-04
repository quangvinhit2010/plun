<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Purpleguy Votes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update PurpleguyVote <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>