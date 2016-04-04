<div class="home-left">
  <div id="triquiback" class="wrap"> <img border="" onload="pagespeed.CriticalImages.checkImageForCriticality(this);" pagespeed_url_hash="401160010" src="/uploads/background/newBG.jpg" alt="" id="triquibackimg" style="display: block; left: -5px; width: 100%; height: 607px;">
    <div class="content-wrap">
                <!-- close button -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
                 <?php if($login_success === false && !Yii::app()->user->id){ ?>
        <div class="sign_in_up">

          
          
         
          <ul>
                        <?php if(Yii::app()->user->hasFlash('msgLogin')): ?>
                        <li class="thongbaoloi_login">
                            	<?php echo Yii::app()->user->getFlash('msgLogin'); ?>
                        </li>
                        <?php endif; ?>          
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
          </ul>

        </div>
        <div class="buttons">
          <input type="submit" value="Đăng Nhập" name="yt0" class="btn btn-gray signin">
          <button onclick="window.location.href='/register'" class="btn btn-gray signup" type="reset">Đăng Ký Ngay</button>
        </div>
                  <?php }else{ ?>
          <div class="get_gift">
          		<?php if($login_success == 1){ ?>
          		<h2>Chúc mừng bạn, bạn sẽ nhận được phần thưởng từ <a href="http://plun.asia">PLUN.ASIA</a></h2>
          		<?php }else{ ?>
          			<h2>Rất tiếc, tài khoản của bạn đã nhận quà</h2>
          		<?php } ?>
          </div>
          <?php } ?>
      <?php $this->endWidget(); ?>
    </div>
  </div>
</div>
