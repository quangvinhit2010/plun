<?php
/* @var $this PurpleguyUserVoteController */
/* @var $model PurpleguyUserVote */

$this->breadcrumbs=array(
	'Purpleguy User Votes'=>array('index'),
	$model->user_id=>array('view','id'=>$model->user_id),
	'Update',
);

?>

<h1>Update PurpleguyUserVote <?php echo $model->user_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>