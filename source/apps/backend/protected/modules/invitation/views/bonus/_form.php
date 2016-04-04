<?php
/* @var $this BonusController */
/* @var $model InviteBonus */
/* @var $form CActiveForm */
$inviter_name = $invited_name = '';
if(!empty($model->historyInvited)){
	$inviter = $model->historyInvited->inviter;
	if(!empty($inviter)){
		$inviter_name = $inviter->username;
	}
	$invited = $model->historyInvited->invited;
	if(!empty($invited)){
		$invited_name = $invited->username;
	}
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invite-bonus-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<p>
			<strong><?php echo $invited_name;?></strong> đã nạp <strong><?php echo number_format($model->invited_realcash);?> (VND)</strong> trong khoảng thời gian từ <strong><?php echo date("d-m-Y", $model->fromdate);?></strong> đến <strong><?php echo date("d-m-Y", $model->todate);?></strong>.<br/>
			Vì vậy <strong><?php echo $inviter_name;?></strong> đã nhận được <strong><?php echo number_format($model->bonus);?> (VND)</strong> từ việc invite <strong><?php echo $invited_name;?></strong> trong thời gian này. 
		</p>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'execute'); ?>
		<?php echo $form->dropDownList($model, 'execute', array(0 => Yii::t(null,'Not'),1 => Yii::t(null,'Done'))); ?>
		<?php echo $form->error($model,'execute'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->