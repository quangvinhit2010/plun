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
					<div class="<?php echo ($passform->hasErrors('password')) ? 'error' : '';?>">
						
						<?php echo $form->textField($passform,'password', array('placeholder'=>Lang::t('forgot', 'New Password'))); ?>
						<div class="error_block">
							<label class="arrow"></label>
							<?php echo $form->error($passform,'password'); ?>                  		        
						</div>
					</div>
				</li>
				<li>
					<div class="<?php echo ($passform->hasErrors('verifyPassword')) ? 'error' : '';?>">
						
						<?php echo $form->textField($passform,'verifyPassword', array('placeholder'=>Lang::t('forgot', 'Verify Password'))); ?>
						<div class="error_block">
							<label class="arrow"></label>
							<?php echo $form->error($passform,'verifyPassword'); ?>                  		        
						</div>
					</div>
				</li>
			</ul>
			<div class="but_sign">
				<?php echo CHtml::submitButton(Lang::t('forgot', 'Reset'), array('class'=>'btn_submit')); ?>
			</div>
		</div>
		<?php $this->endWidget(); ?>
		</div>
	</div>                    
<?php $this->endContent(); ?>






