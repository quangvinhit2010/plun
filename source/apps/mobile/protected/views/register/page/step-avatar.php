<?php
$usercurrent = Yii::app()->user->data();
$redirect = $usercurrent->getUserFeedUrl();
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');

$urlAvatar = Yii::app()->createUrl('/public/images/no-image.jpg');
?>

<div class="pad_10">
	<div class="setting_gen step_one">
    	<div class="titles">
        	<h2><?php echo Lang::t('register', 'Upload Your Profile Picture');?></h2>
        	<p>Your profile picture will appear around the site and all guests can see it.</p>
            <a href="#" class="move_left"></a>
            <a href="#" class="move_right"></a>
        </div>
        <div class="left w100_per drag_crop_avatar">
        	<div class="w100_per choose_avatar">
            	<img src="<?php echo $urlAvatar;?>" align="absmiddle" width="221" height="306">
            	<?php echo CHtml::hiddenField('photo_id', null, array('id'=>'photo_id'));?>
            </div>
            <div class="upload_avatar" style="display: none;">
    	        <input type="file" id="uploadImage">
    	    </div>
            <div class="left w100_per but_upload">
            	<a class="but upload"><?php echo Lang::t('register', 'Upload');?></a>
                <a class="but save_change"><?php echo Lang::t('general', 'Save');?></a>
            </div>
        </div>
    </div>
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
                    	url: "<?php echo $usercurrent->createUrl('//my/UploadAvatar')?>",
                        type: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                        	jQuery('body').unloading();
                        	$('.choose_avatar').html(res);
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

    $(".but_upload .upload").click(function () {
        if(checkSupportUpload()){
            $('#uploadImage').trigger( 'click' );
        }
    	return false;
	});
    $(".choose_avatar").click(function () {
    	if(checkSupportUpload()){
            $('#uploadImage').trigger( 'click' );
        }
    	return false;
	});
    $(".save_change").click(function () {
    	if(Util.isSupportUpload() == true){
            if($('#photo_id').val()){
            	$.ajax({
                	url: "<?php echo $usercurrent->createUrl('//my/UploadAvatar')?>",
                    type: "POST",
                    data: {photo_id: $('#photo_id').val()},
                    success: function (res) {
                    	window.location.href = '<?php echo $redirect;?>';
                    }
                });
            }
        }else{
        	$.ajax({
            	url: "<?php echo $usercurrent->createUrl('//register/stepSkip')?>",
                type: "POST",
                success: function (res) {
                	window.location.href = '<?php echo $redirect;?>';
                }
            });
        }
		return false;
	});

	function checkSupportUpload(){
		if(Util.isSupportUpload() == true){
        	return true;
    	}
    	alert(tr('Your device does not support'));
		return false;
	}

});
</script>
