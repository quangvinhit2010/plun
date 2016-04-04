<?php
	$cs = Yii::app ()->clientScript;
	$cs->registerScriptFile ( Yii::app ()->theme->baseUrl . '/resources/html/js/masonry.pkgd.min.js', CClientScript::POS_END );
	/*$cs->registerScriptFile ( Yii::app ()->theme->baseUrl . '/resources/html/js/imagesloaded.pkgd.min.js', CClientScript::POS_END );*/
	$cs->registerScriptFile ( Yii::app ()->theme->baseUrl . '/resources/js/scripts/isu.js', CClientScript::POS_END );
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/tinymce/tiny_mce.js', CClientScript::POS_BEGIN);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
?>
<div class="container pheader">
	<div class="hotbox">
		<?php $this->renderPartial('partial/menu');?>
		<div class="list_isu">
			<?php if($isus): ?>
			<?php $this->renderPartial('partial/items', array('isus' => $isus, 'pages'=>$pages));?>
			<?php endif; ?>
		</div>
	</div>
	<div class="popup_isu_detail" title="ISU" style="display: none;">
		<?php if(isset($detail)) echo $detail ?>
	</div>
</div>
<div id="create-isu-holder" style="display: none;"></div>

    <div id="popupUserCheckIn" class="popup_general" style="display:none;">
	</div> 
                        <?php 
                        Yii::app()->clientScript->registerScript('checkin_newsfeed',"
							$(document).on('click','.popupListCheckIn',function(){
								objCommon.loading();
								$.ajax({
									type: 'POST',
									url: $(this).attr('href'),
									dataType: 'html',
									success: function(res){
										$('#popupUserCheckIn').html('');
										$('#popupUserCheckIn').html(res);
										if($('#popupUserCheckIn .scrollPopup ul li').length > 5)
										    objCommon.sprScroll('#popupUserCheckIn .scrollPopup');
										else
										    $('#popupUserCheckIn .scrollPopup').css('height','auto');
										$('#popupUserCheckIn').pdialog({
											open: function(){
												objCommon.outSiteDialogCommon(this);
												objCommon.no_title(this);
											},
											width: 370
										});
										objCommon.unloading();
									}
								});
								return false;	
							});
							     ",
                        CClientScript::POS_END);
                        ?>