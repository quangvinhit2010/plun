<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body>
	<?php echo $content;?>
	<?php $this->beginContent('//layouts/partials/scripts'); ?><?php $this->endContent(); ?>
</body>
</html>
