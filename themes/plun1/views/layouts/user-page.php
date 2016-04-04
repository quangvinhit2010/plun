<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<?php 
$class = '';
$ctrlID = strtolower(Yii::app()->getController()->getId());
if(in_array($ctrlID, array('hotbox', 'isu'))){
    $class = ' class="hotbox"';
}
?>
<body<?php echo $class;?>>
    <div class="plun">
    	<div class="wrapper">
    		<?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>
    		<!-- header but display at bottom -->
    		<?php $this->widget('frontend.widgets.UserPage.Navigation', array()); ?>
    		<!-- navigation column -->
    		<div class="main clearfix">
    			<?php echo $content;?>
    			<?php $this->widget('frontend.widgets.Chat.Chat', array()); ?>			
    		</div>
            <?php if((isset(Yii::app()->params->banner) && Yii::app()->params->banner['enabled']) && (Yii::app()->controller->id == 'newsFeed' && Yii::app()->controller->action->id=='feed') ): ?>
            <?php $this->widget('frontend.widgets.UserPage.Banner', array()); ?>
            <?php endif; ?>
    		<?php $this->widget('frontend.widgets.popup.Photodetail', array()); ?>
    		
    		<!-- Popup Purple Guy -->
    		<?php if(isset(Yii::app()->params->purpleguy_popup) && Yii::app()->params->purpleguy_popup): ?>
    		<div class="popup-purpleguy" style="display: none; width: 500px; height: 350px;">
    			<a class="close-purple-guy" href="#" style="position: absolute; right: 12px; top: 12px;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Xbtn.png" width="26px" height="27px" /></a>
    			<a target="_blank" href="http://purpleguy.plun.asia"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/bannerOnPLUN.jpg" width="500px" height="350px" /></a>
    		</div>
    		<script>
    			var removePopup = getCookie('remove-popup');
    			if(removePopup == "") {
    				$('.popup-purpleguy').pdialog({
        				width: '500',
        				height: '397'
                	});
                	$('.popup-purpleguy').css({
    					fontSize: '0px',
    					padding: '0px',
    					overflow: 'hidden'
                    });
        			$('.ui-dialog-titlebar').hide();
        			$('.ui-widget-overlay').addClass('remove-popup');
        			$('.close-purple-guy').click(function(e){
        				$('.popup-purpleguy').pdialog("close");
        				setCookie('remove-popup', '1');
        				e.preventDefault();
            		});
            		$('.remove-popup').click(function(e){
            			setCookie('remove-popup', '1');
            			e.preventDefault();
                	});
        		}
    		</script>
    		<?php endif; ?>
    		<!-- Popup Purple Guy -->
    		
    		<!-- main -->
    	</div>
    	<!-- wrapper -->
    	<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
    	<!-- footer -->
    </div>
    <!-- body -->
    <?php $this->widget('frontend.widgets.UserPage.Notify', array('view'=>'account-activation')); ?>
    <?php $this->widget('frontend.widgets.UserPage.NodeJsApp', array()); ?>
</body>
</html>
