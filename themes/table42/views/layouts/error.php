<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body class="error_404page">
<?php $this->beginContent('//layouts/layout'); ?>
<div class="wrapper_body">
	<div class="template_2">
		<?php $this->beginContent('//layouts/partials/header', array('attributes'=>array('class'=>'header padding_no'))); ?><?php $this->endContent(); ?>		
		<?php echo $content;?>
		<div class="clear"></div>
		<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
	</div>
</div>
<?php $this->endContent(); ?>
</body>
</html>
