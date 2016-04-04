<?php
/* @var $this PurpleguyRoundController */
/* @var $data PurpleguyRound */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_start')); ?>:</b>
	<?php echo CHtml::encode(date('d-m-Y',$data->time_start)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_end')); ?>:</b>
	<?php echo CHtml::encode(date('d-m-Y',$data->time_end)); ?>
	<br />
</div>