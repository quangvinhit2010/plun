<div id="isu-forward-box" class="modal hide fade" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body" style="width: 500px;">
        <div id="pop-signup">
        	<div class="spr-modal-wrap">
        		<div class="form-contain form-message">
        			<a class="btn-close" data-dismiss="modal" aria-hidden="true"></a>
        				<!-- close button -->
        			<div class="pop-title">
        				<h2><i class="i28 i28-message"></i><span class="inline-text"><?php echo Lang::t('isu', 'Forward this ISU to friend\'s');?></span></h2>
        			</div>
        			<div class="form-contain-wrap">
                            <?php $form=$this->beginWidget('CActiveForm', array(
        						'id'=>'forward-isu-form',
        						'action'=>Yii::app()->createUrl('/isu/send', array('type' => 'forward', 'isu_id' => $isu->id)),
        						'enableAjaxValidation'=>false,
								'enableClientValidation'=>true,
        						'clientOptions'=>array(
        							'validateOnSubmit'=>true,
									'afterValidate'=> 'js:ISU.forward_submit'
        						),
        					)); ?>
        					<ul>
        						<li>
        							<div class="input-wrap textarea-wrap">
        								<?php echo $form->textField($model,'to', array('value'=>'','placeholder'=> Lang::t('isu', 'Enter name to forward...'))); ?>
	        							<?php echo $form->error($model,'to'); ?>
        							</div>
        						</li>
        						<li>
        							<div class="input-wrap textarea-wrap">
        								<?php echo $form->textArea($model,'body', array('placeholder'=> Lang::t('isu', 'Enter text...'))); ?>
        							</div>
        							<?php echo $form->error($model,'body'); ?>
        						</li>
        						<li>
        							<div class="buttons">
        								<button type="button" onclick="javascript:ISU.forward_submit('forward-isu-form');" class="btn btn-white btnSend"><?php echo Lang::t('isu', 'Send');?></button>
        								<button onclick="javascript:ISU.cancel('forward-isu-form', event);" class="btn btn-white btnCancel"><?php echo Lang::t('isu', 'Cancel');?></button>
        							</div>
        						</li>
        					</ul>
        					<?php echo $form->hiddenField($model,'from',array('value'=>$this->usercurrent->alias_name)); ?>
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