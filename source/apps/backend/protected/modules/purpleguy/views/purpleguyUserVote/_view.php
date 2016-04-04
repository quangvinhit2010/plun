<?php
/* @var $this PurpleguyUserVoteController */
/* @var $data PurpleguyUserVote */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->user_id), array('view', 'id'=>$data->user_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('round_id')); ?>:</b>
	<?php echo CHtml::encode($data->round_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_vote')); ?>:</b>
	<?php echo CHtml::encode($data->total_vote); ?>
	<br />


</div>