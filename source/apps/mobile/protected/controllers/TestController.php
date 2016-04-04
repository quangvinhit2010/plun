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
        $to = Yii::app()->request->getParam('email');
        if(empty($email)){
            $to  = $user->profile->email;
        }
        
        $subject  = 'Test mail';
        $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/password-changed',array('user'=>$user, 'recovery_url'=>$user->getActivationUrl()), true);
        //         $sent = Mailer::send ( $to,  $subject, $body);
        echo '<pre>';
        print_r($body);
        echo '</pre>';
        exit();
        $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/register-welcome',array('user'=>$user, 'activation_url'=>$user->getActivationUrl()), true);
        $sent = Mailer::send ( $to,  $subject, $body);
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
    
    public function actionUpload(){
        $this->render('upload');
    }
    
    public function actionPopup(){
        $this->render('popup');
    }

}