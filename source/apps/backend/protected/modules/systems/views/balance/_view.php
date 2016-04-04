<?php
/* @var $this BalanceController */
/* @var $data CrBalance */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('current_balance')); ?>:</b>
	<?php echo CHtml::encode($data->current_balance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('available_balance')); ?>:</b>
	<?php echo CHtml::encode($data->available_balance); ?>
	<br />


</div>