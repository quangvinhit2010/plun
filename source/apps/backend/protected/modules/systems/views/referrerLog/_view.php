<?php
/* @var $this ReferrerLogController */
/* @var $data ReferrerLog */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_referrer')); ?>:</b>
	<?php echo CHtml::encode($data->type_referrer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('redirect_url')); ?>:</b>
	<?php echo CHtml::encode($data->redirect_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referrer_url')); ?>:</b>
	<?php echo CHtml::encode($data->referrer_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referrer_id')); ?>:</b>
	<?php echo CHtml::encode($data->referrer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_log')); ?>:</b>
	<?php echo CHtml::encode($data->type_log); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>