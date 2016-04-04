<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<?php 
$class = '';
$ctrlID = strtolower(Yii::app()->getController()->getId());
if(in_array($ctrlID, array('hotbox', 'isu'))){
    $class = ' class="hotbox"';
}
?>
<body> 
<div id="page" >
    <?php $this->beginContent('//layouts/partials/left'); ?><?php $this->endContent(); ?>
    <?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>    
  <div id="block_data_center"> 
    <div class="scroll_info" >
        <div id="main_page" class="box_width_common">
          <div class="main_page_site">
              <div id="col2">
                <div id="container">
                	<?php echo $content;?>
                </div>
              </div>            
          </div>
        </div>
      </div>    
    <div class="clear"></div>
  </div>
  <?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
</div>
<!-- /page -->
<?php $this->widget('mobile.widgets.UserPage.NodeJsApp', array()); ?>
<?php $this->widget('mobile.widgets.UserPage.Notify', array('view'=>'account-activation')); ?>
</body>
</html>
