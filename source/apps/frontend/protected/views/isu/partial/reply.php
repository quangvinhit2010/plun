<?php if(!Yii::app()->user->isGuest){?>
<div id="isu-reply-box">
	<div class="modal-body" style="width: 500px;">
        <div id="pop-signup">
        	<div class="spr-modal-wrap">
        		<div class="form-contain form-message">
        			<a class="btn-close" data-dismiss="modal" aria-hidden="true"></a>
        				<!-- close button -->
        			<div class="pop-title">
        				<h2><i class="i28 i28-message"></i><span class="inline-text"><?php echo Lang::t('isu', 'Reply this ISU');?></span></h2>
        			</div>
        			<div class="form-contain-wrap">
                            <?php $form=$this->beginWidget('CActiveForm', array(
        						'id'=>'reply-isu-form',
        						'action'=>Yii::app()->createUrl('/isu/send'),
        						'enableAjaxValidation'=>false,
								'enableClientValidation'=>true,
        						'clientOptions'=>array(
        							'validateOnSubmit'=>true,
									'afterValidate'=> 'js:ISU.reply_submit'
        						),
        					)); ?>
        					<ul>
        						<li>
        							<div class="input-wrap textarea-wrap">
        								<?php echo $form->textField($model,'to', array('disabled'=>'disabled')); ?>
        							</div>
        							<?php echo $form->error($model,'to'); ?>
        						</li>
        						<li>
        							<div class="input-wrap textarea-wrap">
        								<?php echo $form->textArea($model,'body', array('placeholder'=>Lang::t('isu', 'Enter text...'))); ?>
        							</div>
        							<?php echo $form->error($model,'body'); ?>
        						</li>
        						<li>
        							<div class="buttons">
        								<button type="button" onclick="javascript:ISU.reply_submit('reply-isu-form');" class="btn btn-white btnSend"><?php echo Lang::t('isu', 'Send');?></button>
        								<button onclick="javascript:ISU.cancel('reply-isu-form', event);" class="btn btn-white btnCancel"><?php echo Lang::t('isu', 'Cancel');?></button>
        							</div>
        						</li>
        					</ul>
        					<?php echo $form->hiddenField($model,'from',array('value'=>Yii::app()->user->data()->alias_name)); ?>
        					<?php echo $form->hiddenField($model,'to'); ?>
        				<?php $this->endWidget(); ?>
        			</div>
        		</div>
        		<!-- form container -->
        	</div>
        	<!-- wrap -->
        </div>
    </div>
    <div class="modal-position"></div>
</div>
<?php } ?>