<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body class="bg_contactus">
<div class="wrapper">
    <?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>
    <div class="main clearfix">
    	<div class="bg_404">
			<div class="w1280">
            	<?php echo $content;?>
            </div>
        </div>
	</div>

</div>
<!-- wrapper -->
<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
<!-- footer -->
	
</body>
</html>
