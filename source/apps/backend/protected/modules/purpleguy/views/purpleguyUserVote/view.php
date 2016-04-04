<?php
/* @var $this PurpleguyUserVoteController */
/* @var $model PurpleguyUserVote */

$this->breadcrumbs=array(
	'Purpleguy User Votes'=>array('index'),
	$model->user_id,
);

?>

<h1>View PurpleguyUserVote #<?php echo $model->user_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		'round_id',
		'total_vote',
	),
)); ?>
