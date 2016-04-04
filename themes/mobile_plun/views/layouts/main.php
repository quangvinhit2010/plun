<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body<?php echo VHelper::model()->parseAttributesHtml($this->option_html['body']['attributes'])?>>
    <?php echo $content;?>
</body>
</html>
