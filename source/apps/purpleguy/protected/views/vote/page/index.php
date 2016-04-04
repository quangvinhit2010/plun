<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/purpleguy/vote.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScript('VoteRegister', "Vote.init();", CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/purpleguy/register.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScript('RegisterJoin', "Register.join();", CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/purpleguy/comment.js', CClientScript::POS_BEGIN);
?>
<div class="col-right">
	<div class="members bg_black">
		<div class="list clearfix">
			<?php CController::forward('/vote/listProfile/order_by/'.$order_by.'/page/'.$page.'/s/'.$s, false); ?>
		</div>
	</div>
</div>



