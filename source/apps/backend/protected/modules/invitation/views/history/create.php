<?php
/* @var $this HistoryController */
/* @var $model InviteHistory */

$this->breadcrumbs=array(
	'Invite Histories'=>array('index'),
	'Create',
);
?>

<h1>Create InviteHistory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>