<?php 
	$cs = Yii::app()->clientScript;
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/masonry.pkgd.min.js', CClientScript::POS_END);
	/*$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/imagesloaded.pkgd.min.js', CClientScript::POS_END);*/
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/hotbox.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/tinymce/tiny_mce.js', CClientScript::POS_BEGIN);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
?>
<div class="container pheader">
	<div class="hotbox">
		<?php $this->renderPartial('partial/menu');?>
		<div class="list_hotbox">
			<?php if($hotboxs) : ?>
				<?php $this->renderPartial('partial/items', array('hotboxs' => $hotboxs, 'pages'=>$pages));?>
			<?php endif; ?>
		</div>
	</div>
	<div class="popup_general popup_detail_hotbox" title="Hotbox" style="display: none;">
		<?php if(isset($detail)) echo $detail ?>
	</div>
</div>
<div id="create-hotbox-holder" style="display: none;"></div>
<div id="edit-hotbox-holder" style="display: none;"></div>