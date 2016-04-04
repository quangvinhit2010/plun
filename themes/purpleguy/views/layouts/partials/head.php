<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="">
<meta name="Keywords" content="">
<meta name="robots" content="index, follow" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">


<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/plugins.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/fix_device.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/jquery-plun.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<?php Yii::app()->clientScript->registerCoreScript('cookie');?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.isotope.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.bxSlider.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/inview.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/jquery/jquery.resizecrop-1.0.3.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/util/common.js"></script>
<script type="text/javascript" src="/themes/plun1/resources/html/js/jquery.tr.js"></script>

<?php  Lang::translationJs();?>
<?php $this->beginContent('//layouts/partials/ga'); ?><?php $this->endContent(); ?>