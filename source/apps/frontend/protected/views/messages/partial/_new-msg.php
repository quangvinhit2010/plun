<div class="form-message">
	<div class="form-contain-wrap">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'msg-form',
				'action'=>$this->user->createUrl('/messages/send'),
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
			<ul>
			    <?php 
			    if(!empty($to)):
			    ?>
			    <?php echo $form->hiddenField($model,'to', array('value'=>$to->alias_name)); ?>
			    <?php 
			    else:
			    ?>
				<li>
					<div class="input-wrap">
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
					</div>
					<?php echo $form->error($model,'to'); ?>
				</li>
				<?php endif;?>
				<li>
					<div class="input-wrap textarea-wrap">
						<?php echo $form->textArea($model,'body', array('placeholder'=>Lang::t('messages', 'Enter text...') , 'class'=>'msgBody')); ?>
					</div>
					<?php echo $form->error($model,'body'); ?>
				</li>
				<li>
					<div class="buttons">
						<button class="btn btn-violet btnSend"><?php echo Lang::t('messages', 'Send');?></button>
						<button class="btn btn-white btnCancel"><?php echo Lang::t('messages', 'Cancel');?></button>
					</div>
				</li>
			</ul>
		<?php echo $form->hiddenField($model,'from',array('value'=>$this->usercurrent->alias_name)); ?>
		<?php $this->endWidget(); ?>
	</div>
</div>