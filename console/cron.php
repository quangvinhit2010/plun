<?php
$yii=dirname(__FILE__).'/../source/framework/yiic.php';
$config=dirname(__FILE__).'/../config/console.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createConsoleApplication($config)->run();
