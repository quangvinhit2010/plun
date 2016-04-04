<?php
/**
 * @author vinhnguyen
 * @desc Test Controller
 */
class TestController extends Controller {
    /**
     * profile user
     */
    public function actionMail() {
        $user = Yii::app()->user->data();        
        $subject  = 'Test mail';
        
        $from = Member::model()->findByPk(2);
        $to = Member::model()->findByPk(2);
        
        $hour = time();
        $cri = new CDbCriteria();
        $cri->addCondition("(created BETWEEN :time1 AND :time2) AND to_user_id = :to");
        $cri->params = array(':time1'=>strtotime("-1 hours", $hour), ':time2'=>$hour, ':to'=>$to->id);
        $cri->limit = 10;
        $messages = Message::model()->findAll($cri);
        $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/new-messagesent',array('from'=>$from, 'to'=>$to, 'messages'=>$messages), true);
        $sent = Mailer::send ( $to->profile->email,  $subject, $body);
        echo '<pre>';
        print_r($sent.$body);
        echo '</pre>';
        exit();
    }
    
    public function actionDemo() {
        $user = Yii::app()->user->data();
        $to = Yii::app()->request->getParam('email');
        if(empty($email)){
            $to  = $user->profile->email;
        }
        $subject  = 'Test mail';
        $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/request-private-photos',
        		array('user'=>$user, 'activation_url'=>$user->getActivationUrl()), true);
        //$sent = Mailer::send ( $to,  $subject, $body);
        echo '<pre>';
        print_r($body);
        echo '</pre>';
        exit();
    }
    
    public function device(){
    	$app = Yii::app();
    	$detect = Yii::app()->mobileDetect;
    	$request = trim($app->urlManager->parseUrl($app->request), '/');
    	$allowed = array('site/detectDevice');
    	echo '<pre>';
    	print_r($detect);
    	echo '</pre>';
    	exit;
    }
    
    public function actionLang(){
        $language=Yii::app()->request->getPreferredLanguage();
        echo '<pre>';
        print_r($language);
        echo '</pre>';
        exit;
    }
    
    public function actionPopup(){
        $this->render('popup');
    }
    
    public function actionTicket(){
    	$data = array(
    			'name'      =>      'John Doe',
    			'topicId'  	=>      '2',
    			'email'     =>      'mailbox@host.com',
    			'phone'     =>      '1212121211212',
    			'subject'   =>      date('H:i:s d-m-Y').' test API message',
    			'message'   =>      'This is a test of the osTicket API',
    			'ip'        =>      $_SERVER['REMOTE_ADDR'],
    			'attachments' => array(),
    	);
    	$return = Yii::app()->osTicket->createTicket($data);
    	echo '<pre>';
    	print_r($return);
    	echo '</pre>';
    	exit;
    }
    public function actionVenues(){
    	require 'Elasticsearch/autoload.php';
    	$params	=	array();
		$params['hosts'] = array (
			Yii::app()->params->Elastic['host_http']
		);
		$search_engine	=	new Elasticsearch\Client($params);

		//get index informations
			$search_params	=	array();
			$search_params['index']	=	'venues';
			$search_params['type']	=	'venues';
			
			$query	=	array(); 
			$query['multi_match']	=	array(
					'query'	=>	'xá',
					'fields'	=>	array('name'),
					'minimum_should_match'	=>	'50%',
			);
			
			$search_params['body']['query']['filtered']['query']	=	$query;	
		//start search with index
		
		

		$index_data	=	$search_engine->search($search_params);
			
		print_r($index_data);
    	exit;
    }
    
    public function actionBuildVenues(){
    	require 'Elasticsearch/autoload.php';
    	$params	=	array();
    	$params['hosts'] = array (
    			Yii::app()->params->Elastic['host_http']
    	);
    	$search_engine	=	new Elasticsearch\Client($params);    	
    	$indexParams	=	array();
    	$indexParams['index'] = 'venues';
    	$indexParams['type']  = 'venues';
    	$indexParams['body']['settings']['number_of_shards']   = 10;
    	$indexParams['body']['settings']['number_of_replicas'] = 2;
    	$indexParams['body']['settings']['refresh_interval'] = -1;
    	
    	$search_engine->create($indexParams);
    	
    	
    	$myTypeMapping = 				array(
    			'properties'	=>	array(
    					'name' => array(
    							'type' => 'string',
    					),
    					'city_id' => array(
    							'type' => 'integer',
    					),
    					'looking_for' => array(
    							'type' => 'string',
    					),
    					'user_id' => array(
    							'type' => 'double',
    					),
    					'current_country_id' => array(
    							'type' => 'integer',
    					),
    			)
    	); 
    	$indexParams	=	array();
    	$indexParams['index'] = 'venues';
    	$indexParams['type']  = 'venues';
    	$indexParams['body']['venues'] = $myTypeMapping;
    	$search_engine->indices()->putMapping($indexParams);   

    	exit;
    }
    public function actionInsertVenues(){
    	require 'Elasticsearch/autoload.php';
    	$params	=	array();
    	$params['hosts'] = array (
    			Yii::app()->params->Elastic['host_http']
    	);
    	$search_engine	=	new Elasticsearch\Client($params);
    	    	
    	$body	=	array(
    			'name'	=>	'Trung Tâm Thương Mại Thương Xá Tax',
    			'city_id'	=>	'23',
    			'looking_for'	=>	'My Love',
    			'city_id'	=>	'23',
    	);
    	
    	$params = array();
    	$params['body']  = $body;
    	$params['index']     = 'venues';
    	$params['type']     = 'venues';
    	$params['id']    = 1;
    	$params['ignore']	=	array('404');
    	$params['refresh']	=	false;
    	
    	$check	=	$search_engine->index($params);    	
    }
    public function actionFtp(){
        $ftp = Yii::app()->ftp;
        $chk = $ftp->put('1.png', 'C:\Users\Public\Pictures\Sample Pictures\1.png', FTP_IMAGE);
        var_dump($chk);
//         $ftp->rmdir('exampledir');
//         $ftp->chdir('aaa');
//         $ftp->currentDir();
//         $ftp->delete('remote.txt');
    }
    
    public function actionNodejs(){
    	    	
    	$this->render('nodejs');
    }
    
    public function actionCheckonline($ids = ''){
    	$elasticsearch	=	new Elasticsearch();
    	$arr	=	$elasticsearch->checkOnlineStatus(explode(',', $ids));
    	print_r($arr);
    	exit;
    }
    
    public function actionVtn(){
    	$data = array(
				'username' => 'vinhnguyen085',
			    'password' => 'vinhnguyen085',
			    'email' => 'vinhnguyen085@abc.com',
			    'birthday' => 852051600,
		);
		$return = VTN::model()->createUser($data);
    	echo '<pre>';
    	print_r($return);
    	echo '</pre>';
    	exit;
    }
    
    public function actionElastic($uid){
    	$Elasticsearch	=	new Elasticsearch();
    	$e_user			=	$Elasticsearch->load($uid);
    	if(!empty($e_user)){
    		echo '<pre>';
    		print_r($e_user);
    		echo '</pre>';
    		exit;
    	}
    }
    
    public function actionThumb(){
    	Yii::import('backend.extensions.easyphpthumbnail');
    	$img = Yii::getPathOfAlias('pathroot').DS.'themes'.DS.'plun2'.DS.'resources'.DS.'html'.DS.'images'.DS.'pics_1x_c.jpg';    	
    	$thumb = new easyphpthumbnail();
    	$thumb -> Thumbsize = 70;
    	$thumb -> Pixelate = array(1,20);
    	$thumb -> Createthumb($img);
    	
    }
    
	public function actionViewProfile($u1, $u2){
    	$from = Member::model()->findByAttributes(array('username'=>$u1));
    	$to = Member::model()->findByAttributes(array('username'=>$u2));
    	$c = new EMongoCriteria;
    	$c->view_id('==', $from->id);
    	$c->viewed_id('==', $to->id);
    	$userViewUserInToDay = VstUserViewUser::model()->find($c);
    	$txt = array();
    	if(!empty($userViewUserInToDay)){
    		$txt[] = "$u1 view $u2 at ".date('H:i:s d-m-Y', $userViewUserInToDay->timestamp);
    	}
    	$fromView = VstUser::model()->getUser($from);
    	if(!empty($fromView)){
    		$limitViewProfile = $fromView->getLimitRightToViewProfile();
    		$txt[] = "$u1 limit right to view profile: $limitViewProfile";
    	}
    	$toView = VstUser::model()->getUser($to);
    	if(!empty($toView)){
    		$limitViewProfile = $toView->getLimitRightToViewProfile();
    		$txt[] = "$u2 limit right to view profile: $limitViewProfile";
    	}
    	
    	
    	$return = implode('<br>', $txt);
    	echo '<pre>';
    	print_r($return);
    	echo '</pre>';
    	exit;
    }

}