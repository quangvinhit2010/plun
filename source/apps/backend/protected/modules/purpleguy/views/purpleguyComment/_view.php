<?php
/* @var $this PurpleguyVoteController */
/* @var $data PurpleguyVote */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vote_by')); ?>:</b>
	<?php echo CHtml::encode($data->vote_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('round_id')); ?>:</b>
	<?php echo CHtml::encode($data->round_id); ?>
	<br />


</div>