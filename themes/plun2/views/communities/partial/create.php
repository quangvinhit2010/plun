<div class="communityCreatePop" style="display: none;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'community-form',
	'action'=>Yii::app()->createUrl('/communities/create'),
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	),
)); ?>
	<ul>
		<li><?php echo CHtml::activeTextField($model, 'community_name', array('placeholder'=> Lang::t('community', 'Name')));?></li>
		<li><?php echo CHtml::activeTextArea($model, 'about', array('placeholder'=> Lang::t('community', 'About')));?></li>
		<li><?php echo CHtml::activeRadioButtonList($model, 'privacy', array(1=>'Public', 0=>'Private'));?></li>
	
		<li><?php echo CHtml::textField('', null, array('placeholder'=> Lang::t('community', 'Invite Friend')));?></li>	
		<li>
			<div class="btnCom loadingItem">
				<?php echo CHtml::submitButton('Submit', array('class'=>'btnCommunityCreate'))?>
			</div>
		</li>
	</ul>
	
<?php $this->endWidget(); ?>
</div>
	