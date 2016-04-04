<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
$usercurrent = Yii::app()->user->data();

?>
<div class="step-cont crop-avatar">	
	<div class="block-step">
    	<?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmCropAvatar',
            'action' => $usercurrent->createUrl('//my/cropAvatar'),
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data', 
                'onsubmit' => 'return scropAvatar2();',
                'class'=>'frmFillOut'
                ),
            ));
        ?>
            <div class="frame">
            </div>
    	    <button type="submit" class="btnSelectImg" onclick="$( '.popup-alert.cropAvatar' ).dialog('close'); return false;"><?php echo Lang::t('general', 'Cancel');?></button>
            <button type="submit" class="cropAvatar"><?php echo Lang::t('register', 'Save photo');?></button>
            <input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
        <?php $this->endWidget(); ?>
    </div>
</div>    
<script type="text/javascript">
    function scropAvatar2(){
    	checkCoords();
    	$('body').loading();
    	var srcParse = $(".imgAvatar").attr('src').split("?");
    	var tt = new Date().getTime();		
    	$.ajax({
    		type: "POST",
    		url: $('#frmCropAvatar').attr('action'),
    		data: $('#frmCropAvatar').serialize(),
    		dataType: 'json',
    		success: function( response ) {
    			$('body').unloading();    
				if(response.file){    						
	    			$(".imgAvatar").removeAttr("src").attr('src', response.file);
	    			$(".nav-username").find('img').removeAttr("src").attr('src', response.file);
				}
    			$( ".popup-alert.cropAvatar" ).dialog('close');
    		},
    		error: function () {
            	$("body").unloading();
            }
    	});
    	return false;
    }

</script>
