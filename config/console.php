<?php
define ( 'DS', '/' );
$pathroot = dirname ( dirname ( __FILE__ ) );
$backend = $pathroot . DS . 'source/apps/backend/protected';
$frontend = $pathroot . DS . 'source/apps/frontend/protected';
$lib = $pathroot . DS . 'lib';
$themes = $pathroot . DS . 'themes';

Yii::setPathOfAlias ( 'pathroot', $pathroot );
Yii::setPathOfAlias ( 'backend', $backend );
Yii::setPathOfAlias ( 'frontend', $frontend );
Yii::setPathOfAlias ( 'lib', $lib );
Yii::setPathOfAlias ( 'themes', $themes );

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array (
		'basePath' => $backend,
		'name' => 'My Console Application',
		'language' => 'en',
		'modulePath' => $backend . '/modules',
		'import' => array (
				'frontend.models.*',
				'backend.models.*',
				'backend.components.*',
				'backend.helpers.*',
				'backend.modules.user.models.*',
				'backend.modules.user.components.*',
				'backend.modules.search.components.*',
				'backend.modules.search.models.*',
				'backend.modules.friendship.models.*',
				'backend.modules.profile.models.*',
				'backend.modules.systems.models.*',
		        'backend.modules.SimpleMailer.components.*',
		        'backend.modules.SimpleMailer.models.*',
				'backend.modules.venues.components.*',
				'backend.modules.venues.models.*',
				'lib.vendors.*',
		),
		'modules' => array (
				
				'user' => array (
						'debug' => false,
						'userTable' => 'usr_user',
						'translationTable' => 'usr_translation',
						'baseLayout' => '//layouts/main',
						'layout' => '//layouts/yum',
						'loginLayout' => '//layouts/yum',
						'adminLayout' => '//layouts/yum',
						'passwordRequirements' => array (
								'minLen' => 6,
								'maxLen' => 128,
								'minLowerCase' => 0,
								'minUpperCase' => 0,
								'minDigits' => 0,
								'maxRepetition' => 3 
						),
						'usernameRequirements' => array (
								'minLen' => 3,
								'maxLen' => 20,
								// 'match' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
								'match' => '/^[a-z0-9]+$/u',
								'dontMatchMessage' => 'Incorrect username\'s. (A-Za-z0-9)' 
						) 
				),
				'profile' => array (
						'privacySettingTable' => 'usr_privacysetting',
						'profileFieldTable' => 'usr_profile_field',
						'profileTable' => 'usr_profile',
						'profileCommentTable' => 'usr_profile_comment',
						'profileVisitTable' => 'usr_profile_visit' 
				),
				'SimpleMailer' => array(
				        'attachImages' => false, // This is the default value, for attaching the images used into the emails.
				        'sendEmailLimit'=> 500, // Also the default value, how much emails should be sent when calling yiic mailer
				),
		),
		'commandMap'=>array(
		        'migrate'=>array(
		                'class'=>'backend.commands.MigrateCommand',
		                'migrationPath'=>'backend.migrations',
		                'migrationTable'=>'tbl_migration',
		                'connectionID'=>'db',
// 		                'templateFile'=>'backend.modules.SimpleMailer.migrations.template',
		        ),
		        'mailer'=>array(
		                'class'=>'backend.modules.SimpleMailer.commands.MailerCommand',
		        ),
		),
		'components' => CMap::mergeArray ( array (
				'curl' => array (
						'class' => 'backend.extensions.curl.Curl',
						'options' => array (
								'setOptions' => array () 
						) 
				),
				'urlManager' => array (
						'showScriptName' => false,
						'urlFormat' => 'path',
						'rules' => CMap::mergeArray ( array (), require (dirname ( __FILE__ ) . '/_partials/urls.php') ) 
				),
				'image' => array (
						'class' => 'backend.extensions.image.CImageComponent',
						'driver' => 'GD',
						'params' => array (
								'width' => '900',
								'height' => false
						)
				),				
				'request' => array (
						'hostInfo' => 'http://plun.asia',
						'baseUrl' => '/',
						'scriptUrl' => '' 
				),
				'mail' => array (
						'class' => 'backend.extensions.yii-mail.YiiMail',
						'transportType' => 'smtp', // / case sensitive!
						'transportOptions' => array (
								'host' => 'email-smtp.us-east-1.amazonaws.com',
								'username' => 'AKIAIPZHTCRPCABXUUCA',
								'password' => 'Ascf07t9zzkXcSdYBpmlDxilLfaj5kAZoz/DBdeLmCp+',
								'port' => '465',
								'encryption' => 'tls' 
						),
						'viewPath' => 'application.views.mail',
						'logging' => true,
						'dryRun' => false 
				),
				'cache' => array (
						'class' => 'CRedisCache',
						'hostname' => 'localhost',
						'port' => 6379,
						'keyPrefix' => 'plun',
						'database' => 0 
				) 
		), 

		require (dirname ( __FILE__ ) . '/_partials/config.php') ),
		'params' => CMap::mergeArray ( array (
				'adminEmail' => 'admin@plun.asia',
				'noreplyEmail' => 'noreply@plun.asia',
				'notRequireLogin' => array (
						'user/user/login',
						'user/auth',
						'user/auth/login' 
				) 
		), require (dirname ( __FILE__ ) . '/_partials/params.php') ) 
);