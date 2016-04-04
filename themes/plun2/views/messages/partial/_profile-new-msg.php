<div class="popup_message_user" style="display: none;">
    <div class="title">Send a message</div>
	<div class="content">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'msg-form',
			'action'=>$this->user->createUrl('/messages/send'),
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>false,
			),
		)); ?>
			<?php 
			if(!empty($to)):
			?>
			<?php echo $form->hiddenField($model,'to', array('value'=>$to->alias_name)); ?>
			<?php 
			else:
			?>
			<?php echo $form->textField($model,'to', array('placeholder'=> Lang::t('messages', 'To'), 'class'=>'msgTo', 'id'=>'lstUser', 'style'=>'width: 400px;')); ?>
			<?php 
				$this->widget('backend.extensions.select2.ESelect2',array(
				  'selector'=>"#lstUser",
				  'options'=>array(
					'allowClear'=>true,
					'minimumInputLength' => 2,
					'multiple'=>true,                            
					'ajax'=>array(
						'url'=>$this->user->createUrl('my/GetUsersSuggest'),
						'dataType'=>'json',
						'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
						'results'=>'js:function(data,page) { return {results: data}; }',
					),
				  ),
				));
				?>
			<?php echo $form->error($model,'to'); ?>
			<?php endif;?>
			<?php echo $form->textArea($model,'body', array('placeholder'=>Lang::t('messages', 'Enter text...') , 'class'=>'msgBody')); ?>
			<?php echo $form->error($model,'body'); ?>
			<?php echo $form->hiddenField($model,'from',array('value'=>$this->usercurrent->alias_name)); ?>
		<?php $this->endWidget(); ?>
	</div>
</div>
