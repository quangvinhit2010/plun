<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<?php
?>
<body<?php echo VHelper::model()->parseAttributesHtml($this->option_html['body']['attributes'])?>>
    <div <?php echo VHelper::model()->parseAttributesHtml($this->option_html['divplun']['attributes'])?>>
    	<div class="wrapper">
    		<?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>
    		<!-- header but display at bottom -->
    		<?php $this->widget('frontend.widgets.UserPage.Navigation', array()); ?>
    		<!-- navigation column -->
    		<div<?php echo VHelper::model()->parseAttributesHtml($this->option_html['main']['attributes'])?>>
    			<?php echo $content;?>
    		</div>
    		<!-- main -->
    	</div>
    	<!-- wrapper -->
    	<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>		
    	<!-- footer -->
    </div>
	<!-- body -->
</body>
</html>
