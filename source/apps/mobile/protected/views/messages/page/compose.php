<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'msg-form',
	'action'=>$this->user->createUrl('/messages/send'),
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
			'validateOnSubmit'=>true,
			//'afterValidate'=> 'js:Messages.submit_compose'
	),
)); ?>
<div class="pad_left_10 pad_top_10">                  
	<div class="type_message">
		 <?php 
		 	if($receiver){?>
		 	    <div class="toReceiver">
                    <b><?php echo Lang::t('messages', 'To');?>: </b><?php echo $receiver?>		 	    
		 	    </div>
		 	 <?php echo $form->hiddenField($model,'to', array('value'=>$receiver)); ?>
		 <?php }else{ ?>	
	    	<?php echo $form->textField($model,'to', array('placeholder'=> Lang::t('messages', 'To'), 'id'=>'lstUser', 'style' => 'width: 300px;')); ?>
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
			}
			?>

	    
	    <?php echo $form->textArea($model,'body', array('placeholder'=>Lang::t('messages', 'Enter text...') , 'class'=>'msgBody')); ?>
	    <?php echo $form->error($model,'body'); ?>
    </div>
</div>
<div class="function_message">
	<div class="pad_10">
        <div class="left"><a class="btn btn-violet" href="<?php echo $this->user->createUrl('/messages/index'); ?>"><?php echo Lang::t('messages', 'Cancel');?></a></div>
		<div class="right"><a class="btn btn-violet btnSend" href="javascript:void(0);"><?php echo Lang::t('messages', 'Send');?></a></div>
	</div>
</div>
<?php echo $form->hiddenField($model,'from',array('value'=>$this->usercurrent->alias_name)); ?>
<?php $this->endWidget(); ?>