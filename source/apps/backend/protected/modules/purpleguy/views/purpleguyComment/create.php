<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Purpleguy Votes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PurpleguyVote', 'url'=>array('index')),
	array('label'=>'Manage PurpleguyVote', 'url'=>array('admin')),
);
?>

<h1>Create PurpleguyVote</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>