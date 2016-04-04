<?php $this->beginContent('//layouts/layout'); ?>
	<div class="LoginPage">
		<h1><?php echo Yii::app()->name; ?></h1>
		<div class="LoginForm">
			<h2 class="User">Login</h2>
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>
	</div>	
<?php $this->endContent(); ?>
