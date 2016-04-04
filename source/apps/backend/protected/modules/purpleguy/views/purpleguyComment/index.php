<?php
/* @var $this PurpleguyVoteController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Purpleguy Votes',
);

$this->menu=array(
	array('label'=>'Create PurpleguyVote', 'url'=>array('create')),
	array('label'=>'Manage PurpleguyVote', 'url'=>array('admin')),
);
?>

<h1>Purpleguy Votes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
