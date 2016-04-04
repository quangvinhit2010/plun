<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = 'Error'." | ".Yii::app()->name;
/*
 * echo CHtml::encode($message);
 * echo $code;
 */
?>
<div class="content_404">
    <p><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/error.png" align="absmiddle"></p>
    <p><?php echo CHtml::encode($message);?></p>
    <p><a href="<?php echo Yii::app()->homeUrl;?>"><i></i> go back to home</a></p>
</div>