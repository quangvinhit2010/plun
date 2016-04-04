<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle .= ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>
<div class="bg_contactus">
	<div class="contactus">
		<div class="header_contact"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/bg_contact.jpg"></div>
	</div>
	<div class="w960 content_contactus">
		<div class="left w369 add_cont">
			<h2><?php echo Lang::t('contact', 'Address')?></h2>
			<p><b><?php echo Lang::t('contact', 'Dream Weavers Online Services JSC');?></b></p>
            <p><?php echo Lang::t('contact', 'Ground Fl, Blk C, Phuc Thinh Bldg');?></p>
            <p><?php echo Lang::t('contact', '341 Cao Dat, District 5, Ho Chi Minh City, Vietnam');?></p>                
			<p><b><?php echo Lang::t('contact', 'Phone');?></b>: (848) 5405 1168 </p>
			<p><b>Email</b>: Info@dwm.vn</p>
            
		</div>
		<div class="left w530 form_cont">
		    <?php if(Yii::app()->user->hasFlash('contact')): ?>

            <div class="flash-success">
            	<?php echo Yii::app()->user->getFlash('contact'); ?>
            </div>
            
            <?php else: ?>
			<h2><?php echo Lang::t('contact', 'Contact Form')?></h2>
			<?php $form=$this->beginWidget('CActiveForm', array(
            	'id'=>'contact-form',
            	'enableClientValidation'=>true,
            	'clientOptions'=>array(
            		'validateOnSubmit'=>true,
            	),
            )); ?>
			<table>
				<tr>
					<td>
						<ul>
							<li>
                        		<?php echo $form->textField($model,'name', array('placeholder'=> Lang::t('contact', 'Name') , 'class'=>'input_txt_firstname')); ?>
                        		<?php echo $form->error($model,'name'); ?>
							</li>
							<li>
							    <?php echo $form->textField($model,'email', array('placeholder'=> Lang::t('contact', 'Email'), 'class'=>'input_txt_firstname')); ?>
                        		<?php echo $form->error($model,'email'); ?>
							</li>
							<li>
							    <?php if(CCaptcha::checkRequirements()): ?>
                        		<div class="imgCaptcha">
                        		    <?php $this->widget('CCaptcha', array(
                        		            'buttonLabel'=>''
                        		    )); ?>
                        		</div>
                        	    <?php endif; ?>
							    <?php echo $form->textField($model,'verifyCode'); ?>
                	            <?php echo $form->error($model,'verifyCode'); ?>
							</li>
						</ul>
					</td>
					<td>
					    <?php echo $form->textField($model,'subject', array('placeholder'=> Lang::t('contact', 'Subject'), 'class'=>'input_txt_firstname')); ?>
        		        <?php echo $form->error($model,'subject'); ?>    
					    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'placeholder'=>Lang::t('contact', 'Content'))); ?>
		                <?php echo $form->error($model,'body'); ?>
					</td>
				</tr>
				<tr>
					<td><input type="submit" class="but_submit" value="<?php echo Lang::t('contact', 'Submit'); ?>" name=""> <input class="but_reset" type="reset" value="<?php echo Lang::t('contact', 'Reset'); ?>" name=""></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<?php $this->endWidget(); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

