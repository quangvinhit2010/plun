<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>

<body>
	<div class="plun">
		<div class="wrapper">
			<!-- header but display at bottom -->
			<!-- navigation column -->
			<div class="main clearfix"> 
				<?php echo $content;?>
			</div>
			<!-- main -->
		</div>
		<!-- wrapper -->
	</div>
	<!-- body -->
</body>
</html>