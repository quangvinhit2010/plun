<?php
/* @var $this V2FeedbackController */
/* @var $model V2Feedback */

$this->breadcrumbs=array(
	'V2 Feedbacks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List V2Feedback', 'url'=>array('index')),
	array('label'=>'Create V2Feedback', 'url'=>array('create')),
	array('label'=>'View V2Feedback', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage V2Feedback', 'url'=>array('admin')),
);
?>

<h1>Update V2Feedback <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>