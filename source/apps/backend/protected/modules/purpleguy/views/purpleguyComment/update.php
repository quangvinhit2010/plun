<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Purpleguy Votes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PurpleguyVote', 'url'=>array('index')),
	array('label'=>'Create PurpleguyVote', 'url'=>array('create')),
	array('label'=>'View PurpleguyVote', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PurpleguyVote', 'url'=>array('admin')),
);
?>

<h1>Update PurpleguyVote <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>