<?php
/* @var $this HistoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Invite Histories',
);

$this->menu=array(
	array('label'=>'Create InviteHistory', 'url'=>array('create')),
	array('label'=>'Manage InviteHistory', 'url'=>array('admin')),
);
?>

<h1>Invite Histories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
