<?php 
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/settings.js');
	Yii::app()->clientScript->registerScript('settings',"
		$(document).ready(function(){
			objCommon.hw_common();
			objCommon.list_event();
			objCommon.sprScroll(\".setting_page .content_setting .content\");
		});
		$(window).resize(function(){
			
			objCommon.hw_common();			
		});
		",
	CClientScript::POS_END);
?>
<!-- left column -->
<div class="col-right">
    <?php CController::forward('/settings/settings', false); ?>
    <!-- members list -->
</div>
<!-- right column -->
