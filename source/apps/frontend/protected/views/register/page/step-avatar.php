<?php
$usercurrent = Yii::app()->user->data();
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');

$urlAvatar = Yii::app()->createUrl('/public/images/no-image.jpg');
?>
<div class="step-search">
	<?php $this->renderPartial('page/_tab');?>
    <div class="step-cont">
    	<h2><?php echo Lang::t('register', 'Set Avatar')?></h2>
        <p>&nbsp</p>
		<div class="block-step">
        	<?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'frmCropAvatar',
                'action' => $usercurrent->createUrl('//my/UploadAvatar'),
                'enableClientValidation' => true,
                'enableAjaxValidation' => true,
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data', 
                    'onsubmit' => 'return scropAvatar();',
                    'class'=>'frmFillOut'
                    ),
                ));
            ?>
                <div class="frame">
                    <div class="wrap-upload-image">
                        <img id="uploadPreview" src="<?php echo $urlAvatar;?>?time=<?php echo time();?>"/>
                    </div>
                </div>
                <div class="upload_avatar" style="display: none;">
        	        <input type="file" id="uploadImage">
        	    </div>
        	    <button type="submit" class="btnSelectImg" onclick="$('#uploadImage').trigger( 'click' ); return false;"><?php echo Lang::t('register', 'Upload photo');?></button>
                <button type="submit" class="cropAvatar"><?php echo Lang::t('register', 'Save photo');?></button>
                <input type="hidden" id="x" name="x" />
    			<input type="hidden" id="y" name="y" />
    			<input type="hidden" id="w" name="w" />
    			<input type="hidden" id="h" name="h" />
            <?php $this->endWidget(); ?>
        </div>
    </div>
	<a class="back-step" href="<?php echo Yii::app()->createUrl('/register/stepUpdateProfile');?>" title=""><i></i><?php echo Lang::t('general', 'Back'); ?></a>
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
                    	url: "<?php echo Yii::app()->createUrl('//my/UploadAvatar', array('f'=>$usercurrent->alias_name))?>",
                        type: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                        	jQuery('body').unloading();
                        	$('.frame').html(res);
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

    $(".frame img").click(function () {
    	$('#uploadImage').trigger( 'click' );
		return false;
	});

});
</script>
