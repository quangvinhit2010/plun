<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body<?php echo VHelper::model()->parseAttributesHtml($this->option_html['body']['attributes'])?>>
    <div <?php echo VHelper::model()->parseAttributesHtml($this->option_html['container']['attributes'])?>>
        <div class="wrapper">
            <?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>
            <div <?php echo VHelper::model()->parseAttributesHtml($this->option_html['main']['attributes'])?>>
                <?php echo $content;?>
            </div>
        </div>
    </div>
</body>
</html>
