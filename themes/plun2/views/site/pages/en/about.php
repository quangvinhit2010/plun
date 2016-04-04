<?php
/* @var $this SiteController */

$this->pageTitle .= ' - About';
$this->breadcrumbs=array(
	'About',
);
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/jquery.bxslider.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/parallaxJS/parallax_plun.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/jquery.easing.1.3.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/about.js', CClientScript::POS_END);
?>
<script type="text/javascript">
	var current_lang = '<?php echo Yii::app()->language;?>',
		urlInfoTeam = '<?php echo Yii::app()->createUrl(Yii::app()->theme->baseUrl.'/resources/html/js/parallaxJS/ourteam.json');?>' ;
</script>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl . '/resources/html/css/parallax.css';?>" />

<div class="parallaxPlun"> 
	<div class="wrapper_body">
		<div class="wrapper_container left"> 
			<div class="heightDemo"></div>
			<div class="loadingFirst">
		    	<a href="#"><img class="imgLog" src="<?php echo Yii::app()->theme->baseUrl.'/resources/html'?>/images/parallax/logo_Plun.png" /></a>
		        <div class="loading_process">
		        	<div class="loaded"></div>
		        </div>
		    </div>
		    <div id="fixed-content">
		    	<div class="wrapScrollEffect">
		        <div class="page_intro page_bgFull page_parallax imgLoading">
		        	<div id="anchor-top" class="parallax-anchor"></div>
		            <img class="imgIntro" data-original="<?php echo Yii::app()->theme->baseUrl.'/resources/html'?>/images/parallax/bg_page_1_1.jpg" style="display:none;" />
		            <div class="txt_intro">
		                <div class="say_hello clearfix items_effect">
		                <div class="left txtCenter">
		                    <div class="line_bg left"></div>
		                    <div class="line_bg right"></div>
		                    <span>Hello</span>
		                </div>
		            </div>
		                <h1 class="items_effect">We are Plun.asia</h1>
		                <p class="items_effect">A dating & social networking site for LGBT.</p>
		            </div>
		            <a class="scroll_next items_effect" href="#about">
		                    <div class="wrap_scroll_intro"><div class="wheel"></div></div>
		                    <span>Scroll down</span>
		                </a>
		        </div>
		        <div class="list_menu">
		        	<ul class="clearfix">
                        <li class="logo_about"><a href="<?php echo Yii::app()->homeUrl;?>"></a></li>
                        <li><a href="#about" class="scroll_page">About us </a></li>
		                <li><a href="#ourCompany" class="scroll_page">Our Company</a></li>
		                <li><a href="#gallary" class="scroll_page">Gallery</a></li>
		                <li><a href="#whatPeople" class="scroll_page"> What People Say</a></li>
		                <li><a href="#contact" class="scroll_page">Contact Us</a></li>
		            </ul>
		        </div>
		        <div class="page_1 page_parallax imgLoading">
					<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/about-me')?>		        		
	            </div>
		        <div class="page_2 page_parallax">
					<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/about-ourcompany')?>		        				        	
		        </div>
		        <div class="page_3 page_parallax">
		        	<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/about-gallery')?>
		        </div>
		        <div class="page_4 page_parallax bgInsert imgLoading" data-bg-full="imgBgWhatSay">
		        	<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/about-what-people-say')?>
		        </div>
		        <div class="page_5 page_parallax imgLoading">		        	
		        	<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/about-contact')?>		        	
		        </div>
		        <div class="clear"></div>
		    </div>
		    </div>
		</div>
	</div>
</div>
<div id="popup_news" class="popup_parallax" style="display:none;"></div>
