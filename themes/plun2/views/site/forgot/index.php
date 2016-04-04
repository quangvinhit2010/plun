<?php $this->beginContent('//site/forgot/_main_forgot', array()); ?>
	<div class="sign_form forgot_pass">
		<div class="inner_wrap">
		<div class="title"><?php echo Lang::t('forgot', 'Forgot your password')?></div>
        <?php echo Lang::t('forgot', 'Forgot_des1')?>
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        )); ?>
		<div class="content">
			<ul>
				<li>
					<div class="<?php echo ($model->hasErrors('email')) ? 'error' : '';?>">
						
						<?php echo $form->textField($model,'email', array('placeholder'=>Lang::t('forgot', 'Enter Your Email'))); ?>
						<div class="error_block">
							<label class="arrow"></label>
							<?php echo $form->error($model,'email'); ?>                  		        
						</div>
					</div>
				</li>
				<?php if(CCaptcha::checkRequirements()): ?>
				<li>
					<div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
						<?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'w121 left')); ?>
                        <?php $this->widget('CCaptcha', array(
        		            	'buttonLabel'=>'',
                        		'imageOptions' => array(
                        				'style'=>'height: 28px;',
                        				'class'=>'left',
                        				'id'=>'yw0',
                        		),
                        		'buttonOptions' => array(
                        				'class'=>'reload',
                        				'id'=>'yw0_button',
                        		)                        		
        		        )); ?>
						<div class="error_block">
							<label class="arrow"></label>
							<?php echo $form->error($model,'verifyCode'); ?>                   		        
						 </div>
					 </div>
				</li>
				<?php endif; ?>
			</ul>
			<div class="but_sign">
				<?php echo CHtml::submitButton(Lang::t('forgot', 'Submit'), array('class'=>'btn_submit')); ?>
			</div>
		</div>
		<?php $this->endWidget(); ?>
		</div>
	</div>                    
<?php $this->endContent(); ?>

