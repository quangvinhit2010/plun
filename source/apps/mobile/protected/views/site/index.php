<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);
setcookie("PHPSESSID", "", time() - 6400);
?>
<div class="addAlertApp" id="page" >
  <div class="box_width_common">
    <div class="homepage">
        <div class="content-wrap language_page">
        	<div class="logo_plun">
            	<a href="#"><img align="absmiddle" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/images/graphics/logo_470_235.png" width="235" height="62"></a>
            </div>
            <div class="language_form">
                <ul class="choose_link_language">
                    <?php 
            		if(!empty(Yii::app()->language)){
                        $langs = SysLanguage::model()->cache(500)->findAllByAttributes(array('enable'=>1));
            		?>
            		    <?php foreach ($langs as $lang){
            		        ?>
                        	<li class="choseLangOpt <?php echo $lang->code;?>">
                        	    <a href="<?php echo Yii::app()->createAbsoluteUrl('//site/lang', array('_lang'=>$lang->code, '_type'=>'home'));?>">
                        	        <?php echo $lang->title;?>
                        	    </a>
                        	</li>
                        <?php }?>
                    <?php }?>
                </ul>
            </div>
        </div> 
    </div>
    <div class="clear"></div>
  </div>
</div>
<div id="downApp" class="clearfix">
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="left logo_google_app"></a>
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="right btn_setup">Cài đặt</a>
</div>