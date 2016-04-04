<?php
/* @var $this HistoryController */
/* @var $model InviteHistory */

$this->breadcrumbs=array(
	'Invite Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update InviteHistory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>