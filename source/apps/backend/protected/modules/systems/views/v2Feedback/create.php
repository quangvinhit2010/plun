<?php
/* @var $this V2FeedbackController */
/* @var $model V2Feedback */

$this->breadcrumbs=array(
	'V2 Feedbacks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List V2Feedback', 'url'=>array('index')),
	array('label'=>'Manage V2Feedback', 'url'=>array('admin')),
);
?>

<h1>Create V2Feedback</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>