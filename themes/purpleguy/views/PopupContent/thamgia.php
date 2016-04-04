<?php
$model = PurpleguyProfile::model()->findByAttributes(array('user_id'=>$userCurrent->id));
if(empty($model)){
?>
<div class="popup_thamgia popup_general" style="display: none;">
	<h3>Đăng ký tham gia</h3>
    <a href="#" class="but_close"></a>    
    <div class="content">
    <?php 
	$pProfile=new PurpleguyProfile();
	$form=$this->beginWidget('CActiveForm', array(
	        'id'=>'frm-profile',
	        'action'=>Yii::app()->createUrl('//vote/register'),
	        'enableClientValidation'=>true,
	));
	?>
    	<ul>
        	<li>
        	    <label>Tên đầy đủ</label>
        	    <span>
        	        <?php echo $form->textField($pProfile,'fullname', array('placeholder'=> Lang::t('general', 'Full Name'), 'class'=>'w267')); ?>
        	        <?php echo $form->error($pProfile,'fullname'); ?>
    	        </span>
    	    </li>
            <li>
                <label>Số điện thoại <ins>(bảo mật)</ins>:</label>
                <span>
                    <?php echo $form->textField($pProfile,'phone', array('placeholder'=> Lang::t('general', 'Phone'), 'class'=>'w205')); ?>
        	        <?php echo $form->error($pProfile,'phone'); ?>
                </span>
            </li>
            <li>
                <label>Email <ins>(bảo mật)</ins></label>
                <span>                
                    <?php echo $form->textField($pProfile,'email', array('placeholder'=> Lang::t('general', 'Email'), 'class'=>'w205', 'value'=>$userCurrent->profile->email)); ?>
                    <?php echo $form->error($pProfile,'email'); ?>
                </span>
            </li>
            <li>
                <label>Tải ảnh <ins>(tối đa 5 tấm)</ins>:</label><span><a class="but_upload">Tải Ảnh</a></span>
                <div style="display: none;" id="uploadImage" class="errorMessage"></div>
                <input type="file" id="uploadFile" onchange="Register.readURL(this);" style="display: none;" multiple="multiple">
            </li>
            <li class="imgList">
            	
            </li>
            <li><label><input class="checkbox" name="" checked type="checkbox" value="" style="margin-top: 0px;">Tôi đã đọc và đồng ý với các <a href="javascript:void();" class="thamgia_thele">thể lệ</a> của cuộc thi Purple Guy</label></li>
            <li class="but_sub"><a href="#" class="but_submit">Gửi</a><a href="#" class="but_back">Hủy</a></li>
        </ul>
    <?php $this->endWidget(); ?>
    </div>
</div>
<?php }else{?>
<div class="popup_thamgia popup_general" style="display: none;">
	<h3>Đăng ký tham gia</h3>
    <a href="#" class="but_close"></a>    
    <div class="content">
    	<ul>
        	<li>
        	    <p>Tài khoản của bạn đã đăng ký tham gia!</p>
    	    </li>
        </ul>
    </div>
</div>
<?php }?>