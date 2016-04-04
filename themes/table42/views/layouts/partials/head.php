<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="">
<meta name="Keywords" content="">
<meta name="robots" content="index, follow" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="referrer" content="default"/>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link rel="Shortcut Icon" href="<?php echo Yii::app()->request->getHostInfo();?>/public/images/iconweb.png?t=<?php echo time();?>" type="image/x-icon" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/jquery-ui-table42.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/table42.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/scalew.css" rel="stylesheet" type="text/css" />

<?php 
// Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->scriptMap = array(
	'jquery.js' => Yii::app()->theme->baseUrl . '/resources/html/js/jquery-1.11.0.min.js',
);
Yii::app()->clientScript->registerCoreScript('cookie');
// Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/jquery-ui.min.js');
// Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/html/css/jquery-plun.css');
?>

<?php //Yii::app()->clientScript->registerCssFile( Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css' );?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.tr.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/scripts/util.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.tr.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.bxslider.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/table42.js"></script>
<script type="text/javascript">
	var img_webroot_url	=	'<?php echo Yii::app()->params['img_webroot_url'];?>';
	var update_activity_time	=	'<?php echo Yii::app()->params->Elastic['update_activity_time'];?>';
	var isGuest = '<?php echo Yii::app()->user->isGuest;?>';
	var current_lang = '<?php echo Yii::app()->language;?>';
	<?php if(!Yii::app()->user->isGuest){?>
		var usercurrent = '<?php echo Yii::app()->user->data()->getAliasName();?>';
	<?php } ?>
</script>
<?php 
Lang::translationJs();
$detect = Yii::app()->mobileDetect;
if($detect->isTablet()){
?>
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style_ipad.css" rel="stylesheet" type="text/css" />
<?php }?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mCustomScrollbar.min.js"></script>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/util.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/location.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/tablefortwo.js'); ?>

<?php $this->beginContent('//layouts/partials/ga'); ?><?php $this->endContent(); ?>
