<?php
$view = Yii::app()->request->getParam('view'); 
?>
<ul>
	<li <?php if($view=='about'):?>class="active"<?php endif;?>><a href="<?php echo Yii::app()->createUrl('//site/page/view/about')?>"><?php echo Lang::t('about', 'About PLUN.ASIA')?></a></li>
    <li <?php if($view=='about-ourcompany'):?>class="active"<?php endif;?>><a href="<?php echo Yii::app()->createUrl('//site/page/view/about-ourcompany')?>"><?php echo Lang::t('about', 'Our Company')?></a></li>
    <li <?php if($view=='about-gallery'):?>class="active"<?php endif;?>><a href="<?php echo Yii::app()->createUrl('//site/page/view/about-gallery')?>"><?php echo Lang::t('about', 'Gallery')?></a></li>
    <li <?php if($view=='about-what-people-say'):?>class="active"<?php endif;?>><a href="<?php echo Yii::app()->createUrl('//site/page/view/about-what-people-say')?>"><?php echo Lang::t('about', 'What People Say')?></a></li>
</ul>