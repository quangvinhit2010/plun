<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = 'Error'." | ".Yii::app()->name;
/*
 * echo CHtml::encode($message);
 * echo $code;
 */
?>
<div class="error_404 clearfix">
	<div class="icon_404 left"></div>
	<div class="left content_error">
		<h1>ERROR</h1>
		<h2><?php echo Lang::t('general', 'Page not found')?></h2>
		<p><i class="icon_common icon_mail"></i>Email: <a href="#">support@plun.asia</a></p>
		<p><i class="icon_common icon_fone"></i><?php echo Lang::t('general', 'Customer support system')?>: (848) 5405 1168</p>
	</div>
</div>
<?php //echo CHtml::encode($message);?>
