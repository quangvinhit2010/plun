<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="">
<meta name="Keywords" content="">
<meta name="robots" content="index, follow" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta property="og:title" content="PLUN Asia"/>
<meta property="og:site_name" content="PLUN Asia - Dating system for gay!"/>
<meta property="og:image" content="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg"/>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">

<!-- iPad icons -->
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg" sizes="72x72">
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg" sizes="144x144">
<!-- iPhone and iPod touch icons -->
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg" sizes="57x57">
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg" sizes="114x114">
<!-- Nokia Symbian -->
<link rel="nokia-touch-icon" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg">
<!-- Android icon precomposed so it takes precedence -->
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->getHostInfo(); ?>/public/images/fb_logo_plun.jpg" sizes="1x1">

<link rel="Shortcut Icon" href="<?php echo Yii::app()->request->getHostInfo();?>/public/images/iconweb.png?t=<?php echo time();?>" type="image/x-icon" />


<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/mobile.css" media="all" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/mobile_custom.css" media="all" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/fix_device.css" media="all" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/jquery-plun.css" media="all" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/mobile_<?php echo Yii::app()->language;?>.css" media="all" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/chat.css?v=7" media="all" />
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<?php Yii::app()->clientScript->registerCoreScript('cookie');?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<script type="text/javascript">
	var update_activity_time	=	'<?php echo Yii::app()->params->Elastic['update_activity_time'];?>';
</script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/jquery.touchSwipe.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/jquery.tr.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/topbar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/util/common.js"></script>


<script type="text/javascript">
var GLOBAL_DOMAIN =  "<?php echo Yii::app()->request->getHostInfo(); ?>";
var link_url = '<?php echo Yii::app()->theme->baseUrl; ?>';
var isWindowPhone = /Windows/i.test(navigator.userAgent.toLowerCase());
if(isWindowPhone){
	$('head').append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/mobile_win_phone.css" type="text/css" />');
	<?php if(Yii::app()->language == VLang::LANG_VI):?>
    $('head').append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/mobile_win_phone_vi.css" type="text/css" />');
	<?php endif;?>
}

</script>
	
<?php Lang::translationJs();?>
<?php $this->beginContent('//layouts/partials/ga'); ?><?php $this->endContent(); ?>