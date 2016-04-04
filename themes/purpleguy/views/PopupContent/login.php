<div class="popup_login popup_general" style="display: none;">
	<h3>Đăng nhập</h3>
    <a class="but_close" href="#"></a>    
    <div class="content">
    <?php 
	$model=new LoginForm();
	$form=$this->beginWidget('CActiveForm', array(
	        'id'=>'frm-login',
	        'action'=>Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl(Yii::app()->request->requestUri))),
	        'enableClientValidation'=>true,
// 	        'enableAjaxValidation'=>true,
// 	        'clientOptions'=>array(
// 	                'validateOnSubmit'=>true,
// 	        ),
	));
	?>
    	<ul>
        	<li><p>Bạn cần đăng nhập tài khoản <b>PLUN</b> để tham gia và bình chọn cho <b>PURPLE GUY.</b></p></li>
        	<li>
        	    <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('general', 'Username'), 'class'=>'w205 username')); ?>
        	    <?php echo $form->error($model,'username'); ?>
        	</li>
            <li>
                <?php echo $form->passwordField($model,'password', array('placeholder'=> Lang::t('general', 'Password'), 'class'=>'w205 password')); ?>
                <?php echo $form->error($model,'password'); ?>
            </li>
            <li>
                <?php echo $form->checkBox($model,'rememberMe', array('id'=>'check-rem', 'class'=>'checkbox')); ?> <label>Ghi Nhớ   | <a href="#">Quên Mật Khẩu?</a></label>
            </li>
            <li class="but_sub">
                <?php 
                echo CHtml::ajaxSubmitButton(
                    'đăng nhập', 
                    Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl(Yii::app()->request->requestUri))), 
                    array(
                        'type' => 'POST',
                        'dataType' => 'json',
                        'beforeSend' => 'function() {
                            $("body").loading();
                        }',
                        'success' => 'js:function(data){
                            if(data.status && data.status == true){
                                parent.location.href = parent.location.href;
                            }else{
                                $.each(data, function( index, value ) {
                                    $("#" + index + "_em_").html(value);
                                    $("#" + index + "_em_").show();
                                });
                            }
                            $("body").unloading();
                            return false;
                        }',
                    ),
                    array('class'=>'but_dn')
                );
                ?>
                <a class="but_create" href="<?php echo CParams::load ()->params->url->plun;?>/register?redirect_url=<?php echo Yii::app()->createAbsoluteUrl('/')?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/icon_create.png" align="absmiddle"> Tạo Tài Khoản PLUN</a>
            </li>
        </ul>
    <?php $this->endWidget(); ?>
    </div>
</div>