<?php
/* @var $this ManageController */
/* @var $model InviteModel */
/* @var $form CActiveForm */
$this->breadcrumbs=array(
	'Gamer nhận phần thưởng'=>array('index'),
	'Manage',
);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'themes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Search history invite friend.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'from_date'); ?>
		<?php echo $form->textField($model,'from_date', array("id"=>"from_date")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'from_date',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'from_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'to_date'); ?>
		<?php echo $form->textField($model,'to_date', array("id"=>"to_date")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'to_date',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'to_date'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'album-grid',
	'dataProvider'=>$InviteBonus->search(),
	'filter'=>$InviteBonus,
	'columns'=>array(
		array(
			'header' => 'ID',
			'name' => 'id',
			'filter' => false,
		),
		array(
			'header' => 'Inviter',
			'name' => 'history_invited_id',
			'value' => '(!empty($data->historyInvited->inviter)) ? $data->historyInvited->inviter->username : ""',
		),
		array(
			'header' => 'Invited',
			'value' => '(!empty($data->historyInvited->invited)) ? $data->historyInvited->invited->username : ""',
		),
		array(
			'header' => 'Invited Realcash',
			'name' => 'invited_realcash',
			'filter' => false,
		),
		array(
			'header' => 'Rate',
			'name' => 'rate',
			'filter' => false,
		),
		array(
			'header' => 'Bonus',
			'name' => 'bonus',
			'filter' => false,
		),
		array(
			'name' => 'execute',
			'value' => '(isset($data->execute)) ? InviteModel::model()->getExecuteStatus($data->execute) : ""',
			'filter'=>InviteModel::model()->getExecuteStatus(),
		),
		/*
		array(
			'name' => 'timeline_rate_id',
			'value' => '(!empty($data->timelineRate)) ? $data->timelineRate->title : ""',
			'filter'=>CHtml::listData(InviteTimelineRate::model()->findAll(), 'id', 'title'),
		),
		*/
		'fromdate',
		'todate',
		array(
			'header' => 'created',
			'value' => 'date("d-m-Y", $data->created)',			
		),
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>