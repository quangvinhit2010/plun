<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Purpleguy Votes'=>array('index'),
	'Create',
);

?>

<h1>Create PurpleguyVote</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>