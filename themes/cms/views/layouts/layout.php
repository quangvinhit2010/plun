<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery.ui.min');?>
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css' );?>	
	<?php $this->beginContent('//layouts/_partials/css'); ?><?php $this->endContent(); ?>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script type="text/javascript">
		var domain =  '<?php echo Yii::app()->request->getHostInfo(); ?>';
	</script>
</head>
<body>

<div class="container" id="page">
	<?php echo $content; ?>
</div>
<!-- page -->

</body>
</html>
