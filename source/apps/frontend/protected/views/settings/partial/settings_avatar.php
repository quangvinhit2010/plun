<?php 
$usercurrent = Yii::app()->user->data();
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
$urlAvatar = Yii::app()->createUrl(Yii::app()->theme->baseUrl.'/resources/html/css/images/avatar_acount.jpg');
if(!empty($usercurrent->avatar)){
    $urlAvatar = $usercurrent->getAvatar();
}
?>
<div class="left setting_col1 acount_avatar">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'frmSettingsAvatar',
        'action' => $usercurrent->createUrl('//my/UploadAvatar'),
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data', 
            'onsubmit' => 'return updateAvatar();',
            'class'=>'frmFillOut'
            ),
        ));
    ?>
	<table width="" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="left">
        	<div class="left account_avatar" style="width: 425px; height: 320px; border: 0px;">
        	    <div class="wrap-upload-image">
            	    <img src="<?php echo $urlAvatar;?>" align="absmiddle" id="uploadPreview">
            	</div>
            	<input type="hidden" name="photo_id" />
            </div>
        	<div class="upload_avatar" style="display: none;">
    	        <input type="file" id="uploadImage">
    	    </div>
        </td>
      </tr>                                
      <tr>
        <td align="center">                    	                        
            <a class="btn btn-violet" href="javascript:void(0);" title="" onclick="$('#uploadImage').trigger( 'click' ); return false;"><?php echo Lang::t('settings', 'Upload Photo'); ?></a>
            <a onclick="$('#frmSettingsAvatar').submit();" title="" href="javascript:void(0);" class="btn btn-white"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
        </td>
      </tr>
    </table>
    <input type="hidden" id="x1" name="x" />
	<input type="hidden" id="y1" name="y" />
	<input type="hidden" id="w1" name="w" />
	<input type="hidden" id="h1" name="h" />
    <?php $this->endWidget(); ?>
</div>

<script type="text/javascript">

jQuery('document').ready(function(){
    var input = document.getElementById("uploadImage");
    var formdata = false;
    if (window.FormData) {
        formdata = new FormData();
    }
    input.addEventListener("change", function (evt) {
        var i = 0, len = this.files.length, img, reader, file;
        for ( ; i < len; i++ ) {
            file = this.files[i];
            if (!!file.type.match(/image.*/)) {
                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.onloadend = function (e) { 
//                     	$('.imgAvatar').attr('src', e.target.result)
                    };
                    reader.readAsDataURL(file);
                }
                if (formdata) {
                    formdata.append("image", file);
                    jQuery('body').loading();

                    jQuery.ajax({
                    	url: "<?php echo $usercurrent->createUrl('//my/UploadAvatar', array('v'=>'upload-avatar-settings'))?>",
                        type: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                        	jQuery('body').unloading();
                        	$('.account_avatar').html(res);
                        }
                    });
                }
            }
            else
            {
                alert('Not a vaild image!');
            }   
        }

    }, false);

    $(".account_avatar img").click(function () {
    	$('#uploadImage').trigger( 'click' );
		return false;
	});

});

function updateAvatar(){
	$('body').loading();
	$.ajax({
		type: "POST",
		url: $('#frmSettingsAvatar').attr('action'),
		data: $('#frmSettingsAvatar').serialize(),
		dataType: 'json',
		success: function( response ) {
			location.reload(true);
			$('body').unloading();
		},
		error: function () {
        	$("body").unloading();
        }
	});
	return false;
}

$(function(){
	$( "#uploadPreview" ).load(function() {
    	var _w_jcrop = $(this).width();
    	var _h_jcrop = $(this).height();
    	var _width_frame = 425;
    	var _height_frame = 320;
    	$('.wrap-upload-image').css({
            position: 'relative'
        });      
    	$('.wrap-upload-image').animate({
    	    left: ((_width_frame/2) - (_w_jcrop/2)) + "px",
    	    top: ((_height_frame/2) - (_h_jcrop/2)) + "px",
    	  }, "slow");
		});
});
</script>