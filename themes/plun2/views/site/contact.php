<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle .= ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>
<div class="container pheader wrap_scroll clearfix contact_page">
    <div class="page_5 page_parallax imgLoading">
        <div id="anchor-contact" class="parallax-anchor"></div>
        <div class="say_hello clearfix items_effect">
            <div class="left txtCenter">
                <div class="line_bg left"></div>
                <div class="line_bg right"></div>
                <span>Contact us</span>
            </div>
        </div>

        <div class="wrap_map">
            <!--<img src="<?php /*echo Yii::app()->theme->baseUrl*/?>/resources/html/images/mapCty.jpg" alt=""/>-->
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1959.7320642514417!2d106.68972373252724!3d10.775720244387463!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3b18a06641%3A0xecddce579c3b6a50!2zNDcgQsOgIEh1eeG7h24gVGhhbmggUXVhbiwgNiwgUXXhuq1uIDMsIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1419821814640" width="100%" height="450" frameborder="0" style="border:0"></iframe>
        </div>
        <div class="frm_contact">
            <div class="el_form">
                <h1><?php echo Lang::t('contact', 'Contact Form')?></h1>
                <div class="infor_cty clearfix">
                    <p><i class="icon_common icon_cty"></i><b><?php echo Lang::t('contact', 'Dream Weavers Online Services JSC');?></b></p>
                    <p><?php echo Lang::t('contact', 'Block');?>, <?php echo Lang::t('contact', 'Address_Office');?></p>
                    <p class="left"><i class="icon_common icon_phone"></i><b><?php echo Lang::t('contact', 'Phone');?>:</b> (848) 5405 1168 </p>
                    <p class="left"><i class="icon_common icon_email"></i><b><?php echo Lang::t('contact', 'Email');?>:</b> support@plun.asia </p>
                </div>
                <?php if(Yii::app()->user->hasFlash('contact')): ?>
                    <div class="flash-success">
                        <?php echo Yii::app()->user->getFlash('contact'); ?>
                    </div>
                <?php else: ?>
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'contact-form',
                        'enableClientValidation'=>true,
                        'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                        ),
                        'htmlOptions'=>array(
                            'class'=>'clearfix',
                            'data-url'=>Yii::app()->createUrl('/contact'),
                        ),
                    )); ?>
                    <div class="left">
                        <div class="wrap_fi">
                            <?php echo $form->textField($model,'name', array('placeholder'=> Lang::t('contact', 'Name') , 'class'=>'input_txt_firstname')); ?>
                            <?php echo $form->error($model,'name'); ?>
                        </div>
                        <div class="wrap_fi">
                            <?php echo $form->textField($model,'email', array('placeholder'=> Lang::t('contact', 'Email'), 'class'=>'input_txt_firstname')); ?>
                            <?php echo $form->error($model,'email'); ?>
                        </div>
                        <div class="wrap_fi">
                            <?php echo $form->textField($model,'phone_number', array('placeholder'=> Lang::t('contact', 'Phone') , 'class'=>'input_txt_firstname')); ?>
                            <?php echo $form->error($model,'phone_number'); ?>
                        </div>
                        <div class="wrap_fi">
                            <?php if(CCaptcha::checkRequirements()): ?>
                                <?php echo $form->textField($model,'verifyCode', array('class'=>'left', 'placeholder'=>Lang::t('register', 'Type text besides'))); ?>
                                <?php $this->widget('CCaptcha', array(
                                    'buttonLabel'=>''
                                )); ?>
                                <?php echo $form->error($model,'verifyCode'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="right">
                        <div class="select_style">
                            <?php echo $form->dropDownList($model,'subject', $subjectOptions, array('placeholder'=> Lang::t('contact', 'Subject'), 'class'=>'select-type-5 virtual_form', 'text'=>"body_text")); ?>
                            <span class="txt_select"><span class="body_text"><?php echo Lang::t('contact', 'Subject');?></span></span> <span class="btn_combo_down"></span>
                            <?php echo $form->error($model,'subject'); ?>
                        </div>
                        <div class="wrap_fi">
                            <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'placeholder'=>Lang::t('contact', 'Content'))); ?>
                            <?php echo $form->error($model,'body'); ?>
                        </div>
                    </div>
                    <div class="btn_center">
                        <input type="submit" class="but_submit" value="<?php echo Lang::t('contact', 'Submit'); ?>" name="">
                        <input class="but_reset" type="reset" value="<?php echo Lang::t('contact', 'Reset'); ?>" name="">
                    </div>
                    <?php $this->endWidget(); ?>
                <?php endif; ?>
            </div>
        </div>


    </div>
</div>

<div class="container pheader min_max_926" style="display: none;">
  <div class="explore left about">
	<div class="left content_contact">
	  <div><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/contact.jpg" align="absmiddle" /></div>
	  <div class="content">
		<div class="left info_contact">
		  	<h3><?php echo Lang::t('contact', 'Address')?></h3>
			<p><?php echo Lang::t('contact', 'Dream Weavers Online Services JSC');?></p>
            <p><?php echo Lang::t('contact', 'Block');?></p>
            <p><?php echo Lang::t('contact', 'Address_Office');?></p>                
			<p><?php echo Lang::t('contact', 'Phone');?>: (848) 5405 1168 </p>
			<p>Email: support@plun.asia</p>
			<p><?php echo Lang::t('contact', 'Marketing Phone');?>: 090876069 </p>
			<p><?php echo Lang::t('contact', 'Marketing Email');?>: chanh@dwm.vn</p>
		</div>
		<div class="left form_contact">
			<?php if(Yii::app()->user->hasFlash('contact')): ?>

            <div class="flash-success">
            	<?php echo Yii::app()->user->getFlash('contact'); ?>
            </div>
            
            <?php else: ?>
		  <h3><?php echo Lang::t('contact', 'Contact Form')?></h3>
			<?php $form=$this->beginWidget('CActiveForm', array(
            	'id'=>'contact-form',
            	'enableClientValidation'=>true,
            	'clientOptions'=>array(
            		'validateOnSubmit'=>true,
            	),
            )); ?>
		  <table>
			<tbody>
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
						    <?php echo $form->textField($model,'phone_number', array('placeholder'=> Lang::t('contact', 'Phone') , 'class'=>'input_txt_firstname')); ?>
                		    <?php echo $form->error($model,'phone_number'); ?>
						</li>
						<li>
						    <?php if(CCaptcha::checkRequirements()): ?>
						    <?php echo $form->textField($model,'verifyCode', array('class'=>'left', 'placeholder'=>Lang::t('register', 'Type text besides'))); ?>
                        	<div class="imgCaptcha left">
                        		<?php $this->widget('CCaptcha', array(
                        		            'buttonLabel'=>''
                        		)); ?>
							</div>
                	        <?php echo $form->error($model,'verifyCode'); ?>
                        	<?php endif; ?>
						</li>
						<li>
						  	<?php echo $form->dropDownList($model,'subject', $subjectOptions, array('placeholder'=> Lang::t('contact', 'Subject'), 'class'=>'input_txt_firstname')); ?>
	        		        <?php echo $form->error($model,'subject'); ?>    
						    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'placeholder'=>Lang::t('contact', 'Content'))); ?>
			                <?php echo $form->error($model,'body'); ?>
						</li>
						<li class="right">
							<input type="submit" class="but_submit" value="<?php echo Lang::t('contact', 'Submit'); ?>" name=""> 
							<input class="but_reset" type="reset" value="<?php echo Lang::t('contact', 'Reset'); ?>" name="">
						</li>
					</ul>
				  </td>
			  </tr>
			</tbody>
		  </table>
		  <?php $this->endWidget(); ?>
		  <?php endif; ?>
		</div>
	  </div>
	</div>
  </div>
</div>

