<?php
/* @var $this BonusController */
/* @var $model InviteBonus */

$this->breadcrumbs=array(
	'Invite Bonuses'=>array('index'),
	'Create',
);
?>

<h1>Create InviteBonus</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>