<?php
/* @var $this BonusController */
/* @var $model InviteBonus */

$this->breadcrumbs=array(
	'Invite Bonuses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update InviteBonus <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>