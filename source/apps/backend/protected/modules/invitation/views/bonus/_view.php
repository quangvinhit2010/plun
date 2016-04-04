<?php
/* @var $this BonusController */
/* @var $data InviteBonus */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('history_invited_id')); ?>:</b>
	<?php echo CHtml::encode($data->history_invited_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invited_realcash')); ?>:</b>
	<?php echo CHtml::encode($data->invited_realcash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rate')); ?>:</b>
	<?php echo CHtml::encode($data->rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bonus')); ?>:</b>
	<?php echo CHtml::encode($data->bonus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('execute')); ?>:</b>
	<?php echo CHtml::encode($data->execute); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timeline_rate_id')); ?>:</b>
	<?php echo CHtml::encode($data->timeline_rate_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fromdate')); ?>:</b>
	<?php echo CHtml::encode($data->fromdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('todate')); ?>:</b>
	<?php echo CHtml::encode($data->todate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>