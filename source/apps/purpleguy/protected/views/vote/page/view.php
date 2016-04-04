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
			<?php CController::forward('/vote/listProfile', false); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	if(true) {
		$('body').loading();
		$.get('/vote/LoadDetail', {user_id: <?php echo $uid;?>}, function(html){
			$('body').unloading();
			$(".popup_vongloai").find('.content').html($(html).html());
			$('.bxslider').bxSlider({
				mode: 'fade',
				captions: true
			});
			$(".popup_vongloai").pdialog({
				width: 840,
			});
			$(".ui-dialog-titlebar").hide();
			sprScroll('.list_comment ul.list_item');
		}).fail(function() {
			$('body').unloading();
		});
	}
});

</script>
