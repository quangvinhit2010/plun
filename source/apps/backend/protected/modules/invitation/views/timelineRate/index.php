<?php
/* @var $this TimelineRateController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Invite Timeline Rates',
);

$this->menu=array(
	array('label'=>'Create InviteTimelineRate', 'url'=>array('create')),
	array('label'=>'Manage InviteTimelineRate', 'url'=>array('admin')),
);
?>

<h1>Invite Timeline Rates</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
