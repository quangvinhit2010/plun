<?php 
$clsHeader = 'header padding_no';
$menuExists = '';
if(!Yii::app()->user->isGuest){
	$clsHeader = 'header scroll_overlow';
	$menuExists = ' menuExists';
}
?>
<?php $this->beginContent('//layouts/layout'); ?>
<div class="wrapper_body">
	<?php $this->beginContent('//layouts/partials/main-menu'); ?><?php $this->endContent(); ?>				
	<?php $this->beginContent('//layouts/partials/header', array('attributes'=>array('class'=>$clsHeader))); ?><?php $this->endContent(); ?>
	<div class="wrapper_container left<?php echo $menuExists;?>">
		<?php echo $content;?>
		<div class="clear"></div>
		<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
	</div>
</div>
<?php $this->endContent(); ?>
<?php $this->widget('frontend.widgets.popup.Checkin', array()); ?>
<?php $this->widget('frontend.widgets.UserPage.NodeJsApp', array()); ?>