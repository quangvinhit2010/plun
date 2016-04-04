<?php
define ( 'DS', '/' );
$pathroot = dirname ( dirname ( __FILE__ ) );
$frontend = $pathroot . DS . 'source/apps/frontend/protected';
$backend = $pathroot . DS . 'source/apps/backend/protected';
$purpleguy = $pathroot . DS . 'source/apps/purpleguy/protected';
$lib = $pathroot . DIRECTORY_SEPARATOR . 'lib';
$themes = $pathroot . DS . 'themes';

Yii::setPathOfAlias ( 'pathroot', $pathroot );
Yii::setPathOfAlias ( 'frontend', $frontend );
Yii::setPathOfAlias ( 'backend', $backend );
Yii::setPathOfAlias ( 'purpleguy', $purpleguy );
Yii::setPathOfAlias ( 'themes', $themes );
Yii::setPathOfAlias ( 'lib', $lib );

return array (
		'basePath' => $purpleguy,
		'name' => 'Gay Dating & Social Network - Trang Kết bạn dành cho Gay',
		'theme' => 'purpleguy',
		'runtimePath' => $purpleguy . DS . 'runtime',
		'modulePath' => $backend . '/modules',
		'preload' => array (
				'log' 
		),
        'id' => 'plun.asia',
		'language' => 'vi',
		'import' => array (
				'backend.helpers.*',
				'backend.vendors.*',
				'backend.components.*',
				'backend.models.*',
				'backend.extensions.*',
				'backend.extensions.ymds.*',
				'backend.extensions.captchaExtended.*',
				'backend.extensions.image.*',
				'backend.extensions.ymds.extra.*',
				'backend.modules.user.models.*',
				'backend.modules.user.components.*',
				'backend.modules.profile.models.*',
				'backend.modules.friendship.models.*',
				'backend.modules.message.models.*',
				'backend.modules.systems.models.*',
				'backend.modules.systems.extensions.*',
		        'backend.modules.SimpleMailer.components.*',
		        'backend.modules.SimpleMailer.models.*',
		        'backend.modules.purpleguy.models.*',
				'application.helpers.*',
				'application.models.*',
				'application.components.*',
				'lib.vendors.*' ,
		),
		'modules' => array (
				'gii' => array (
						'class' => 'system.gii.GiiModule',
						'password' => '24241324',
						'ipFilters' => array (
								'127.0.0.1',
								'::1' 
						) 
				),
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
				'friendship' => array (
						'friendshipTable' => 'usr_friendship' 
				),
				'message' => array (
						'messageTable' => 'usr_message' 
				),
				'api',
				'invitation',
				'coupon',
		        'registration'=>array(
		            'loginAfterSuccessfulRecovery' => true,        
                ),
		),
		'components' => CMap::mergeArray ( array (
				'user' => array (
						'allowAutoLogin' => true,
						'class' => 'purpleguy.components.YumWebUser' 
				),
				'urlManager' => array (
						'showScriptName' => false,
						'urlFormat' => 'path',
						'rules' => CMap::mergeArray ( array (), require (dirname ( __FILE__ ) . '/_partials/urls_purpleguy.php') ) 
				),
				'errorHandler' => array (
						'errorAction' => 'site/error' 
				),
				'image' => array (
						'class' => 'backend.extensions.image.CImageComponent',
						'driver' => 'GD',
						'params' => array (
								'width' => '900',
								'height' => false 
						) 
				),
				'phpThumb' => array (
						'class' => 'backend.modules.systems.extensions.EPhpThumb.EPhpThumb',
						'options' => array () 
				),
				'log' => array (
						'class' => 'CLogRouter',
						'routes' => array (
								array (
										'class' => 'CFileLogRoute',
										'levels' => 'error, warning' 
								),
// 						        array(
// 						                'class'=>'backend.extensions.yii-debug-toolbar.YiiDebugToolbarRoute',
// 						                'ipFilters'=>array('127.0.0.1','192.168.1.215'),
// 						        ),
						) 
				),
				'assetManager' => array (
						'basePath' => dirname ( __FILE__ ) . '/../source/apps/purpleguy/assets/',
						'baseUrl' => '/source/apps/purpleguy/assets/' 
				),
				'themeManager' => array (
						'basePath' => dirname ( __FILE__ ) . '/../themes/',
						'baseUrl' => '/themes/' 
				),
				// 'cache' => array('class' => 'system.caching.CFileCache'),
				'cache' => array (
						'class' => 'CRedisCache',
						'hostname' => 'localhost',
						'port' => 6379,
						'keyPrefix' => 'plun',
						'database' => 0 
				),
				'mobileDetect' => array(
						'class' => 'backend.extensions.MobileDetect.MobileDetect'
				),
        /* 'redisCache'=>array(
          'class'=>'CRedisCache',
          'hostname'=>'localhost',
          'port'=>6379,
          'database'=>0,
          ), */
        'config' => array (
						'class' => 'backend.extensions.EConfig',
						'configTableName' => 'sys_config',
						'strictMode' => false 
				),
				'authManager' => array (
						'class' => 'backend.modules.srbac.components.SDbAuthManager',
						'connectionID' => 'db',
						'itemTable' => 'auth_item',
						'assignmentTable' => 'auth_assignment',
						'itemChildTable' => 'auth_item_children' 
				),
				'session' => array (
						// 'class' => 'system.web.CDbHttpSession',
						'class' => 'backend.components.PDbHttpSession',
				        'sessionName' => 'PLUNFO',
						'connectionID' => 'db_activity',
						'sessionTableName' => 'activities_sessions',
						'autoCreateSessionTable' => false,
						'autoStart' => true,
						'cookieMode' => 'allow',
						'cookieParams' => array (
								'path' => '/',
						        'domain' => '.plun.asia',
						        'httpOnly' => true,
						),
						'timeout' => 86400 
				),
        /* 'session' => array(
          'class' => 'CCacheHttpSession',
          'cacheID' => 'cache',
          'autoStart' => 'false',
          'cookieMode' => 'allow',
          'cookieParams' => array(
          'path' => '/',
          ),
          'timeout' => 900
          ), */
        'curl' => array (
						'class' => 'backend.extensions.curl.Curl',
						'options' => array (
								'setOptions' => array () 
						) 
				),
				'swiftMailer' => array (
						'class' => 'backend.extensions.swiftMailer.SwiftMailer' 
				),
				'mail' => array (
						'class' => 'backend.extensions.yii-mail.YiiMail',
						'transportType' => 'smtp', // / case sensitive!
						'transportOptions' => array (
								'host' => 'smtp.gmail.com',
								'username' => 'noreply@plun.asia',
								'password' => 'noreply123456',
								'port' => '465',
								'encryption' => 'ssl' 
						),
						'viewPath' => 'application.views.mail',
						'logging' => true,
						'dryRun' => false 
				),
				'facebook' => array (
						'class' => 'backend.extensions.yii-facebook-opengraph.SFacebook',
						'appId' => '348603545273948', // needed for JS SDK, Social Plugins and PHP SDK
						'secret' => '909ac2ad124ee513f5a02978027e7f81', // needed for the PHP SDK
						                                                // 'fileUpload'=>false, // needed to support API POST requests which send files
						                                                // 'trustForwarded'=>false, // trust HTTP_X_FORWARDED_* headers ?
						                                                // 'locale'=>'en_US', // override locale setting (defaults to en_US)
						                                                // 'jsSdk'=>true, // don't include JS SDK
						                                                // 'async'=>true, // load JS SDK asynchronously
						                                                // 'jsCallback'=>false, // declare if you are going to be inserting any JS callbacks to the async JS SDK loader
						                                                // 'status'=>true, // JS SDK - check login status
						                                                // 'cookie'=>true, // JS SDK - enable cookies to allow the server to access the session
						                                                // 'oauth'=>true, // JS SDK - enable OAuth 2.0
						                                                // 'xfbml'=>true, // JS SDK - parse XFBML / html5 Social Plugins
						                                                // 'frictionlessRequests'=>true, // JS SDK - enable frictionless requests for request dialogs
						                                                // 'html5'=>true, // use html5 Social Plugins instead of XFBML
						'ogTags' => array ( // set default OG tags
								'og:title' => 'plun.asia',
								'og:description' => 'plun.asia share',
								'og:image' => 'public/images/ico-share.png?t=' . time () 
						) 
				),
				'eauth' => array (
						'class' => 'backend.extensions.yii-eauth.EAuth',
						'popup' => true, // Use the popup window instead of redirecting.
						'services' => array ( // You can change the providers and their classes.
								'google_oauth' => array (
										// register your app here: https://code.google.com/apis/console/
										'class' => 'backend.extensions.yii-eauth.services.GoogleOAuthService',
										'client_id' => '248132742114.apps.googleusercontent.com',
										'client_secret' => 'ZnI8a2kicgOabWpU8rA3Ycar',
										'title' => 'Google (OAuth)' 
								) 
						) 
				),
				'xmpp' => array (
						'class' => 'backend.extensions.xmpp.XMPP' 
				),
				'twitter' => array (
						'class' => 'backend.extensions.yiitwitteroauth.YiiTwitter',
						'consumer_key' => '6yIKMEb0qfdHV7xAXQM8Aw',
						'consumer_secret' => 'VMHxKk9SJDtd3Hmiwq4joKme3OR4lNuEWC4GCCOtW4Y',
						'callback' => 'http://localhost.plun.asia/invitation/mobile/getFriends/connect_type/twitter' 
				),
				'geoip' => array (
						'class' => 'backend.extensions.geoip.CGeoIP',
						// specify filename location for the corresponding database
						'filename' => dirname ( __FILE__ ) . '/../lib/geoip/GeoIP.dat',
						// Choose MEMORY_CACHE or STANDARD mode
						'mode' => 'STANDARD' 
				) 
		), require (dirname ( __FILE__ ) . '/_partials/config.php') ),
		'behaviors' => array (
				'onBeginRequest' => array (
						'class' => 'application.components.BeginRequestBehavior' 
				) 
		),
		'params' => CMap::mergeArray ( array (
				'adminEmail' => 'admin@plun.asia',
				'noreplyEmail' => 'noreply@plun.asia',
				'supportEmail' => 'support@plun.asia',
				'notRequireLogin' => array (
						'user/user/login',
						'user/auth',
						'user/auth/login' 
				) 
		), require (dirname ( __FILE__ ) . '/_partials/params_purpleguy.php') ) 
);
