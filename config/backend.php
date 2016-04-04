<?php
define('DS', '/');
$pathroot = dirname(dirname(__FILE__));
$backend = $pathroot . DIRECTORY_SEPARATOR . 'source/apps/backend/protected';
$frontend = $pathroot . DIRECTORY_SEPARATOR . 'source/apps/frontend/protected';
$lib 	= $pathroot . DIRECTORY_SEPARATOR . 'lib';
$themes = $pathroot . DS . 'themes';

Yii::setPathOfAlias('pathroot', $pathroot);
Yii::setPathOfAlias('backend', $backend);
Yii::setPathOfAlias('frontend', $frontend);
Yii::setPathOfAlias('themes', $themes );
Yii::setPathOfAlias('lib', $lib);

return array(
	'basePath'=> $backend,
	'name'=>'Admin',
	'theme' => 'cms',
	'runtimePath' => $backend . DIRECTORY_SEPARATOR . 'runtime',
	'preload'=>array('log'),
	'language' => 'en',
	'import'=>array(
		'application.helpers.*',
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'application.extensions.image.*',
		'application.modules.ads.models.*',
		'application.modules.cms.models.*',
		'application.modules.media.models.*',
		'application.modules.product.models.*',
		'application.modules.user.models.*',
		'backend.modules.message.models.*',
		'application.modules.gallerymanager.models.*',
        'application.modules.search.models.*',
        'application.modules.systems.models.*',
        'application.modules.search.components.*',
        'application.modules.SimpleMailer.components.*',
        'application.modules.SimpleMailer.models.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
		'lib.vendors.*',
	),
	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'24241324',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'user' => array(
			'debug' => false,
			'userTable' => 'usr_user',
			'translationTable' => 'usr_translation',
			'baseLayout' => '//layouts/main',
			'layout' => '//layouts/yum',
			'loginlayout' => '//layouts/login',
			'adminlayout' => '//layouts/yum',
		),
		'usergroup' => array(
			'usergroupTable' => 'usr_usergroup',
			'usergroupMessageTable' => 'usr_user_group_message',
		),
// 		'membership' => array(
// 			'membershipTable' => 'usr_membership',
// 			'paymentTable' => 'usr_payment',
// 		),
		'friendship' => array(
			'friendshipTable' => 'usr_friendship',
		),
		'profile' => array(
			'privacySettingTable' => 'usr_privacysetting',
			'profileFieldTable' => 'usr_profile_field',
			'profileTable' => 'usr_profile',
			'profileCommentTable' => 'usr_profile_comment',
			'profileVisitTable' => 'usr_profile_visit',
		),
// 		'role' => array(
// 			'roleTable' => 'usr_role',
// 			'userRoleTable' => 'usr_user_role',
// 			'actionTable' => 'usr_action',
// 			'permissionTable' => 'usr_permission',
// 		),
		'message' => array(
			'messageTable' => 'usr_message',
		),
		'TranslatePhpMessage',
		'srbac' => array(
			'userclass'=>'YumUser', //default: User
			'userid'=>'id', //default: userid
			'username'=>'username', //default:username
			'delimeter'=>'@', //default:-
			'debug'=>true, //default :false
			'pageSize'=>10, // default : 15
			'superUser' =>'Administrator', //default: Authorizer
			'css'=>'srbac.css', //default: srbac.css
			'layout'=>
			'application.views.layouts.main', //default: application.views.layouts.main,
			//must be an existing alias
			'notAuthorizedView'=> 'srbac.views.authitem.unauthorized', // default:
			//srbac.views.authitem.unauthorized, must be an existing alias
			'alwaysAllowed'=>array( //default: array()
					'SiteLogin','SiteLogout','SiteIndex','SiteAdmin',
					'SiteError', 'SiteContact'),
			'userActions'=>array('Show','View','List'), //default: array()
			'listBoxNumberOfLines' => 15, //default : 10 'imagesPath' => 'srbac.images', // default: srbac.images 'imagesPack'=>'noia', //default: noia 'iconText'=>true, // default : false 'header'=>'srbac.views.authitem.header', //default : srbac.views.authitem.header,
			//must be an existing alias 'footer'=>'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
			//must be an existing alias 'showHeader'=>true, // default: false 'showFooter'=>true, // default: false
			'alwaysAllowedPath'=>'srbac.components', // default: srbac.components
			'layout' => '//layouts/column1'
		),
		'payment',
		'currency',
		'chat',
		'global',
		'gallerymanager',
		'systems',
        'search',
        'coupon',
		'hobby',
		'hotbox',
		'registration',
        'SimpleMailer' => array(
            'attachImages' => true, // This is the default value, for attaching the images used into the emails.
            'sendEmailLimit'=> 500, // Also the default value, how much emails should be sent when calling yiic mailer
        ),
        'rights'=>array(
            'userClass'=>'YumUser', // Enables the installer.
            'superuserName'=>'Admin', // Name of the role with super user privileges.
            'authenticatedName'=>'Authenticated', // Name of the authenticated user role.
            'userIdColumn'=>'id', // Name of the user id column in the database.
            'userNameColumn'=>'username', // Name of the user name column in the database.
            'enableBizRule'=>true, // Whether to enable authorization item business rules.
            'enableBizRuleData'=>false, // Whether to enable data for business rules.
            'displayDescription'=>true, // Whether to use item description instead of name.
            'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages.
            'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages.
            'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested.
            'layout'=>'rights.views.layouts.main', // Layout to use for displaying Rights.
            'appLayout'=>'application.views.layouts.main', // Application layout.
            // 					'cssFile'=>'rights.css', // Style sheet file to use for Rights.
            'install'=>false, // Whether to enable installer.
            'debug'=>false,
        ),
		'background',
		'ReportingTool',
		'purpleguy',
		'banner',
		'venues',
		'notification',
		'table42',
	),
	'components'=> CMap::mergeArray(
		array(
			'user'=>array(
				'class' => 'application.components.YumWebUser',
		      	'allowAutoLogin'=>true,
		      	'loginUrl' => array('//user/user/login'),
			),
			// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
				'urlFormat'=>'path',
				'showScriptName' => false,
				'rules'=>array(
					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
			'image'=>array(     
				'class'=>'application.extensions.image.CImageComponent',            
				'driver'=>'GD', 
				'params'=>array(
						'width' => '900',
						'height' => false,
					)
			), 
			'phpThumb' => array (
				'class' => 'backend.modules.systems.extensions.EPhpThumb.EPhpThumb',
				'options' => array ()
			),
			'db'=>array(
				'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
			),
			// uncomment the following to use a MySQL database
			'errorHandler'=>array(
				// use 'site/error' action to display errors
				'errorAction'=>'site/error',
			),
			//'cache' => array('class' => 'system.caching.CFileCache'),
			'cache'=>array(
					'class'=>'CRedisCache',
					'hostname'=>'localhost',
					'port'=>6379,
					'database'=>0,
			),
			'config'=>array(
					'class'=>'backend.extensions.EConfig',
					'configTableName'=>'sys_config',
					'strictMode'=>false,
			),
			'authManager'=>array(
// 				'class'=>'application.modules.srbac.components.SDbAuthManager',
				'class'=>'RDbAuthManager',
				'connectionID'=>'db',
				'itemTable' => 'auth_item',
				'itemChildTable' => 'auth_item_child',
				'assignmentTable' => 'auth_assignment',
			),
	        'session' => array(
                //'class' => 'system.web.CDbHttpSession',
                'class' => 'backend.components.PDbHttpSession',
                'sessionName' => 'PLUNBO',
                'connectionID' => 'db_activity',
                'sessionTableName' => 'activities_sessions',
                'autoCreateSessionTable' => false,
                'autoStart' => true,
                'cookieMode' => 'allow',
                'cookieParams' => array(
                        'path' => '/',
                ),
                'timeout' => 86400
	        ),
			'themeManager' => array(
				'basePath'=> dirname(__FILE__).'/../themes/',
	        	'baseUrl'=>'/themes/'
			),
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
					// uncomment the following to show log messages on web pages
					/*
					array(
						'class'=>'CWebLogRoute',
					),
					*/
				),
			),
			'curl' =>array(
				'class' => 'application.extensions.curl.Curl',
				'options'=>array(
					'setOptions'=>array(
			        ),
		        ),
			),			
			'ganon' =>array(
				'class' => 'application.extensions.ganon.ganon',
			),
			'xmpp' =>array(
				'class' => 'backend.extensions.xmpp.XMPP',
			),
			'geoip' => array(
					'class' => 'application.extensions.geoip.CGeoIP',
					// specify filename location for the corresponding database
					'filename' => dirname(__FILE__).'/../lib/GeoIP/GeoIP.dat',
					// Choose MEMORY_CACHE or STANDARD mode
					'mode' => 'STANDARD',
			),
			'ftp'=>array(
					'class'=>'backend.extensions.ftp.EFtpComponent',
					'autoConnect'=>true,
			),				
		),
		require(dirname(__FILE__).'/_partials/config.php')
	),
	'behaviors' => array(
			'RequireLogin',
	
   	),
	'params'=> CMap::mergeArray(
		array(
			'adminEmail'=>'webmaster@example.com',
			'notRequireLogin' => array(
				'user/user/login', 
				'user/auth', 
				'user/auth/login', 
				'api/activeServers',
				'api/rankingList'
			),
		),
		
		
		require(dirname(__FILE__).'/_partials/params.php')
	)
);