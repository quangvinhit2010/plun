<?php
/* @var $this TimelineRateController */
/* @var $model InviteTimelineRate */

$this->breadcrumbs=array(
	'Invite Timeline Rates'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update InviteTimelineRate <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>