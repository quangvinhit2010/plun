<?php
/* @var $this V2FeedbackController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'V2 Feedbacks',
);

$this->menu=array(
	array('label'=>'Create V2Feedback', 'url'=>array('create')),
	array('label'=>'Manage V2Feedback', 'url'=>array('admin')),
);
?>

<h1>V2 Feedbacks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
