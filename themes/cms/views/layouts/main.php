<?php $this->beginContent('//layouts/layout'); ?>
	<?php $this->beginContent('//layouts/_partials/menu'); ?><?php $this->endContent(); ?>
	<div class="clear"></div>
	<?php $this->beginContent('//layouts/_partials/header'); ?><?php $this->endContent(); ?>
	<?php $this->beginContent('//layouts/_partials/breadcrumb'); ?><?php $this->endContent(); ?>
	<div id="main">
		<?php echo $content; ?>
		<div class="clear"></div>
	</div>

	<div class="clear"></div>
	<?php $this->beginContent('//layouts/_partials/footer'); ?><?php $this->endContent(); ?>
<?php $this->endContent(); ?>
