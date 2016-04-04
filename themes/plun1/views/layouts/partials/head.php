<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="">
<meta name="Keywords" content="">
<meta name="robots" content="index, follow" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="referrer" content="default"/>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link rel="Shortcut Icon" href="<?php echo Yii::app()->request->getHostInfo();?>/public/images/iconweb.png?t=<?php echo time();?>" type="image/x-icon" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/plugins.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style_<?php echo Yii::app()->language;?>.css" rel="stylesheet" type="text/css" />

<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/fix_device.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/demoStyleSheet.css" rel="stylesheet" type="text/css" />

<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style_profile.css" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<?php Yii::app()->clientScript->registerCoreScript('cookie');?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/jquery-plun.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	var update_activity_time	=	'<?php echo Yii::app()->params->Elastic['update_activity_time'];?>';
	var isGuest = '<?php echo Yii::app()->user->isGuest;?>';
</script>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.fancybox.js"></script>

<?php //Yii::app()->clientScript->registerCssFile( Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css' );?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.isotope.min.js"></script>	
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.bxSlider.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/util/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/bookmark/bookmark.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/inview.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.json-2.4.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.poshytip.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/plun.date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.tr.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.ui.button.js"></script>

<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/selectize.default.css" rel="stylesheet" type="text/css" />
<!--[if IE 8]><script src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/es5.js"></script><![endif]-->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/selectize.js"></script>

<!-- Chat -->
<?php if(!Yii::app()->user->isGuest){?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/strophe.js"></script>
<!-- 
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/iso8601_support.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/strophe.rsm.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/strophe.archive.js"></script>
 -->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/plun.cookie.js"></script>

<script type="text/javascript">
    var XMPP_BIND = "<?php echo Yii::app()->params['XMPP']['http-bind'];?>";
	var XMPP_SERVER = "<?php echo Yii::app()->params['XMPP']['server'];?>";
	var XMPP_ROOM = "<?php echo Yii::app()->params['XMPP']['room'];?>";
	var XMPP_AVT = "<?php echo Yii::app()->user->data()->getAvatar();?>";
	var XMPP_JID = "<?php echo Yii::app()->user->data()->username;?>";
	var XMPP_JKEY = "<?php echo Yii::app()->user->data()->chat_key;?>";
	var XMPP_DOMAIN =  "<?php echo Yii::app()->request->getHostInfo(); ?>";
</script>

<script type="text/javascript">
	var img_webroot_url	=	'<?php echo Yii::app()->params['img_webroot_url'];?>';
</script>

<!-- Temp Code -->
<?php if(strlen($_SERVER['SERVER_NAME']) > 16): ?>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/chat-temp.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/chat-new.js"></script>
<?php else: ?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/chat.js?v=3"></script>
<?php endif; ?>
<!-- Temp Code -->

<?php } ?>

<script type="text/javascript">
var current_lang = '<?php echo Yii::app()->language;?>';
<?php if(!Yii::app()->user->isGuest){?>
	var usercurrent = '<?php echo Yii::app()->user->data()->getAliasName();?>';
<?php } ?>
</script>
<?php 
$detect = Yii::app()->mobileDetect;
if($detect->isTablet()){
?>
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style_ipad.css" rel="stylesheet" type="text/css" />
<?php }?>

<?php 
Lang::translationJs();
?>

<?php $this->beginContent('//layouts/partials/ga'); ?><?php $this->endContent(); ?>
