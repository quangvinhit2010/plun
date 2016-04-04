<div id="page-login" class="page-form">
    <div class="spr-modal-wrap">

    	<div class="form-contain form-login">
	    	<p class="logo-vp">
	    		<img alt="logo vietpride" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/logo_VP.png">
	    	</p>    	
    		 <div class="form-contain-wrap">
                <!-- close button -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
                 <?php if($login_success === false){ ?>

			         
			          <ul class="w247">
			                        <?php if(Yii::app()->user->hasFlash('msgLogin')): ?>
			                        <li class="thongbaoloi_login">
			                            	<?php echo Yii::app()->user->getFlash('msgLogin'); ?>
			                        </li>
			                        <?php endif; ?>    
			                        <li><p>CHECK IN NGAY ĐỂ NHẬN QUÀ</p></li>
			                        <li>
			                            <div class="input-wrap<?php echo ($model->hasErrors('username')) ? ' error' : '';?>">
			                                <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'input_txt_username')); ?>
			                                <label class="arrow"></label>
			                                <?php echo $form->error($model,'username'); ?>
			                            </div>
			                        </li>
			                        <li>
			                            <div class="input-wrap<?php echo ($model->hasErrors('password')) ? ' error' : '';?>">
			                                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'input_txt_pass')); ?>
			                                <label class="arrow"></label>
			                                <?php echo $form->error($model,'password'); ?>
			                            </div>
			                        </li>
			                        <li class="signin_now">
			                            <?php echo CHtml::submitButton(Lang::t('login', 'Sign in now'), array('class'=>'btnSignIn')); ?>
			                        </li>
			                        <li class="share">
                        			</li>
                        			<li class="link_signup"><a href="<?php echo Yii::app()->createUrl('//register');?>"><?php echo Lang::t('register', 'Sign up now');?></a></li>
			          </ul>
			

			                  <?php }else{ ?>
			          		<?php if($login_success == 1){ ?>
			          		<h4>Chúc mừng, bạn sẽ nhận được một phần quà từ <a class="link-gift-homepage" href="http://plun.asia">PLUN.ASIA</a>!</h4>
			          		<p class="list-price"><a href="<?php echo Yii::app()->createUrl('//vietpride/list');?>">Danh Sách Thành Viên Đã Tham Gia Nhận Quà</a></p>
			          		<p>
			          		<?php }else{ ?>
			          			<h4>Rất tiếc, tài khoản của bạn đã nhận quà</h4>
			          			<p class="list-price"><a href="<?php echo Yii::app()->createUrl('//vietpride/list');?>">Danh Sách Thành Viên Đã Tham Gia Nhận Quà</a></p>
			          		<?php } ?>
			          <?php } ?>
			      <?php $this->endWidget(); ?>
			      </div>
      	</div>
      	<div class="position"></div>
    </div>
</div>
