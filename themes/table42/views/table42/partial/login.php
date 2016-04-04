	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
	    'enableClientValidation'=>true,
	    'clientOptions'=>array(
	     	'validateOnSubmit'=>true,
	     ),
	)); ?>    
	<div id="popup_login" class="popup_general" title="Đăng nhập" style="display:none;">	    
	    <div class="content">
	    	<h1>Đăng nhập</h1>
	        <ul>
	            <li>
	            	<?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'txt')); ?>
	            </li>
	            <li>
	                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'txt')); ?>
	            </li>
	            <li>
	            	<?php echo $form->checkBox($model,'rememberMe', array('class' => 'chk','id' => 'LoginForm_rememberMe')); ?>
	            	<label for="LoginForm_rememberMe">Ghi Nhớ </label>
	            	  |  <a href="<?php echo $params->params->base_url . Yii::app()->createUrl('//forgotpass');?>" target="_blank">Quên mật khẩu</a>
	            </li>
	            <li class="center">
	            	<a class="bg_btn" href="javascript:void(0);" title="đăng nhập ngay" onclick="Tablefortwo.Login();">
	            		<span class="bg_btn">đăng nhập ngay</span>
	            	</a>
	            </li>
	            <li class="center">Chưa là thành viên?<a href="#" class="reg link_regist" data-effect-id="popup_signup"> Đăng ký</a></li>
	        </ul>
	    </div>
	</div>
	<?php $this->endWidget(); ?>