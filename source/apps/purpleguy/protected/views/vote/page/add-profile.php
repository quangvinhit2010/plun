<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/purpleguy/addProfile.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScript('addProfileJoin', "addProfile.join();", CClientScript::POS_END);
if(!Yii::app()->user->isGuest):
    $userCurrent =  Yii::app()->user->data();
?>
<div class="col-right">
	<div class="members bg_black">
		<div class="list clearfix">
			<?php CController::forward('/vote/listProfile', false); ?>
		</div>
	</div>
</div>


<div class="popup_thamgia popup_addProfile popup_general" style="display: none;">
	<h3>Đăng ký tham gia</h3>
    <a href="#" class="but_close"></a>    
    <div class="content">
    <?php 
	$pProfile=new PurpleguyProfile();
	$form=$this->beginWidget('CActiveForm', array(
	        'id'=>'frm-addprofile',
	        'action'=>Yii::app()->createUrl('//vote/addProfile'),
	        'enableClientValidation'=>true,
	));
	?>
    	<ul>
        	<li>
        	    <label>Username</label>
        	    <span>
        	        <?php echo $form->textField($pProfile,'username', array('placeholder'=> Lang::t('general', 'Username'), 'class'=>'w267', 'id'=>'txtUsername')); ?>
        	        <?php echo $form->error($pProfile,'username'); ?>
    	        </span>
    	    </li>
        	<li>
        	    <label>Tên đầy đủ</label>
        	    <span>
        	        <?php echo $form->textField($pProfile,'fullname', array('placeholder'=> Lang::t('general', 'Full Name'), 'class'=>'w267', 'id'=>'txtFullname')); ?>
        	        <?php echo $form->error($pProfile,'fullname'); ?>
    	        </span>
    	    </li>
            <li>
                <label>Số điện thoại <ins>(bảo mật)</ins>:</label>
                <span>
                    <?php echo $form->textField($pProfile,'phone', array('placeholder'=> Lang::t('general', 'Phone'), 'class'=>'w205', 'id'=>'txtPhone')); ?>
        	        <?php echo $form->error($pProfile,'phone'); ?>
                </span>
            </li>
            <li>
                <label>Email <ins>(bảo mật)</ins></label>
                <span>                
                    <?php echo $form->textField($pProfile,'email', array('placeholder'=> Lang::t('general', 'Email'), 'class'=>'w205', 'id'=>'txtEmail')); ?>
                    <?php echo $form->error($pProfile,'email'); ?>
                </span>
            </li>
            <li>
                <label>Tải ảnh <ins>(tối đa 5 tấm)</ins>:</label><span><a class="but_upload">Tải Ảnh</a></span>
                <div style="display: none;" id="uploadImage2" class="errorMessage"></div>
                <input type="file" id="uploadFile2" onchange="addProfile.readURL(this);" style="display: none;" multiple="multiple">
            </li>
            <li class="imgList">
            	
            </li>
            <li><label><input class="checkbox" name="" checked type="checkbox" value="" style="margin-top: 0px;">Tôi đã đọc và đồng ý với các <a href="javascript:void();" class="thamgia_thele">thể lệ</a> của cuộc thi Purple Guy</label></li>
            <li class="but_sub"><a href="#" class="but_submit">Gửi</a><a href="#" class="but_back">Hủy</a></li>
        </ul>
    <?php $this->endWidget(); ?>
    </div>
</div>
<?php 
endif;
?>

<script type="text/javascript">
$(document).ready(function(){
	if(true) {
		if($('#frm-profile').length > 0)
			$('#frm-profile')[0].reset();
		if($( ".popup_addProfile" ).length > 0){
			$( ".popup_addProfile" ).pdialog({
				width: 481,
//			position: [230,0],
				open: function(){
					var posiParent = $(this).parent().position().top;
					$(this).parent().css({
						'margin-top': (posiParent*2 + 20)+'px',
					});
				}
			});	
			$(".ui-dialog-titlebar").hide();
		}

	}
});

</script>
