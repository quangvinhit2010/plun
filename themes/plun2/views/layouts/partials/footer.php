<div class="pop-mess-succ" style="display: none;">
    <div class="popcont">
        <p>
	        <span class="icon-check"></span>
        	<label></label>
        </p>
    </div>
</div>
<div class="pop-mess-fail" style="display: none;">
    <div class="popcont">
        <p>
	        <span class="icon-check"></span>
        	<label></label>
        </p>
    </div>
</div>
<?php
if(!Yii::app()->user->isGuest) {
	Yii::app()->controller->renderPartial('//chat/chat');
}
?>
<!--<script type="text/javascript" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/resources/html/js/jquery.lazyload.min.js"></script>-->
<?php $this->widget('frontend.widgets.UserPage.Notify', array('view'=>'from_plun')); ?>
<?php $this->beginContent('//layouts/partials/ga'); ?><?php $this->endContent(); ?>