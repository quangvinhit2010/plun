<?php

class VoteController extends Controller
{   
    public function beforeAction($action){
        /*
        if(Yii::app()->user->isGuest){
            $session = Util::getSession();
            $session->add ( 'must_login', true);
            $this->redirect('/');
        } 
        */       
        return parent::beforeAction($action);
    }
    
	public function actionIndex($order_by = 'total_vote', $page = 1, $s = '')
	{
	    $purpleGuyRound = PurpleguyRound::model()->getCurrentRound();
		if($purpleGuyRound) {
		    if(Yii::app()->request->isAjaxRequest){
		        CController::forward('/vote/listProfile/order_by/'.$order_by.'/page/'.$page.'/s/'.$s, false);
		    }else{
			    $this->render('page/index', array('order_by'=>$order_by, 'page'=>$page, 's'=>$s));
		    }
		} else {
			throw new CHttpException(403, 'NO EVENT');
		}
	}
	
	public function actionListProfile($order_by = 'total_vote', $page = 1, $s = '')
	{	     
	    $purpleGuyRound = PurpleguyRound::model()->getCurrentRound();
	
	    if($purpleGuyRound) {
	        	
	        $purpleguy_list = PurpleguyProfile::model()->getUsersInRound($order_by, $page, $purpleGuyRound->id, $s);
	        	
	        $topVoteUsers = PurpleguyProfile::model()->getTopVoteUsers($purpleGuyRound->id);
	        $topVoteUsers = array_map(function($el){ return $el['user_id']; }, $topVoteUsers);
            $this->renderPartial('partial/_list', array('purpleguy_list'=>$purpleguy_list, 'topVoteUsers'=>$topVoteUsers, 'currentRoundId'=>$purpleGuyRound->id));
	    } else {
	        throw new CHttpException(403, 'NO EVENT');
	    }
	}
	
	public function actionView($uid) {
	    $user = PurpleguyProfile::model()->findByAttributes(array('user_id'=>$uid));
	    if(!empty($user)){
    	    Yii::app()->clientScript->registerMetaTag('PURPLE GUY 2014 là cuộc thi tìm kiếm gương mặt đại diện hàng tháng của PLUN.ASIA.', '', null, array('property' => 'og:description'), 'meta_og_description');
    	    Yii::app()->clientScript->registerMetaTag('PURPLE GUY 2014', '', null, array('property' => 'og:site_name'), 'meta_og_site_name');
    	    Yii::app()->clientScript->registerMetaTag('PLUN Asia - '.$user->fullname, '', null, array('property' => 'og:title'), 'meta_og_title');
    	    if(!empty($user->photo)){
    	        Yii::app()->clientScript->registerMetaTag($user->photo->getPath(), '', null, array('property' => 'og:image'), 'meta_og_image');
    	    }
    	    $this->render('page/view', array('uid'=>$uid));
	    }
	}
	
	public function actionRegister()
	{
	    if(Yii::app()->user->isGuest)
	        throw new CHttpException(403, 'Not Login');
		if(!empty($this->usercurrent)){
		    VModel::model()->register($this->usercurrent);
		}
	}
	
	public function actionLoadDetail($user_id=NULL) {
	    $data = array();
		if(!$user_id)
			throw new CHttpException(403, 'Not Profile');
		
		$currentRound = PurpleguyRound::model()->getCurrentRound();
		$data['purpleGuyProfile'] = PurpleguyProfile::model()->getUserInRound($user_id, $currentRound->id);
		$data['user_id'] = $user_id;
		
		if(!Yii::app()->user->isGuest && $user_id != $this->usercurrent->id) {
    		$data['voted']= PurpleguyVote::model()->find('user_id = :user_id AND vote_by = :vote_by AND round_id = :round_id', array(':user_id'=>$user_id, ':vote_by'=>$this->usercurrent->id, ':round_id'=>$currentRound->id));
			
			$purpleGuyView = new PurpleguyView();
			$pgv = $purpleGuyView->find('user_id = :user_id AND view_by = :view_by AND round_id = :round_id', array(':user_id'=>$user_id, ':view_by'=>$this->usercurrent->id, ':round_id'=>$currentRound->id));
			
			if(!$pgv) {
				$purpleGuyView->insertView($user_id, $this->usercurrent->id, $currentRound->id);
				$purpleGuyUserVoteModel = new PurpleguyUserVote();
				$purpleGuyUserVote = $purpleGuyUserVoteModel->find('user_id = :user_id AND round_id = :round_id', array(':user_id'=>$user_id, ':round_id'=>$currentRound->id));
				$purpleGuyUserVote->total_view = $purpleGuyUserVote->total_view + 1;
				$purpleGuyUserVote->save();
			}
		}
		
		$this->renderPartial('partial/_detail', $data);
	}
	
	public function actionVoteFor($user_id=NULL) {
		if(Yii::app()->user->isGuest || !$user_id)
			throw new CHttpException(403, 'Not Login');
	
		if($user_id != $this->usercurrent->id) {
			
			$currentRound = PurpleguyRound::model()->getCurrentRound();
			
			$purpleGuyUserVoteModel = new PurpleguyUserVote();
			$purpleGuyUserVote = $purpleGuyUserVoteModel->find('user_id = :user_id AND round_id = :round_id', array(':user_id'=>$user_id, ':round_id'=>$currentRound->id));
			
			if( ! $purpleGuyUserVote)
				exit;
				
			$purpleGuyVote = new PurpleguyVote();
			$pgv = $purpleGuyVote->find('user_id = :user_id AND vote_by = :vote_by AND round_id = :round_id', array(':user_id'=>$user_id, ':vote_by'=>$this->usercurrent->id, ':round_id'=>$currentRound->id));

			if(!$pgv) {
				$purpleGuyVote->insertVote($user_id, $this->usercurrent->id, $currentRound->id);
				
				$purpleGuyUserVote->total_vote = $purpleGuyUserVote->total_vote + 1;
				$purpleGuyUserVote->save();
				echo json_encode(array('message'=>'Bình chọn thành công.'));
			} else {
				echo json_encode(array('message'=>'Bạn đã bình chọn cho ứng viên này rồi.'));
			}
		} else {
			echo json_encode(array('message'=>'Bạn không thể bình chọn cho chính mình.'));
		}
	}
	
	public function actionFb() {
	    $this->renderPartial('partial/fb');
	}
	
	public function actionAddProfile() {
	    $roles = VHelper::model()->checkAccess('Manager', Yii::app()->user->id);
	    if(!empty($roles)){
	        if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
	            $user = Member::model()->findByAttributes(array('username'=>$_POST['PurpleguyProfile']['username']));
	            if(!empty($user) && $user->id != Yii::app()->user->id){
	                VModel::model()->register($user);
	            }
	        }
            $this->render('page/add-profile');
	    }else{
	        throw new CHttpException(403, 'Not Permission');
	    }
	}

	public function actionContact() {
		$purpleguyContactModel = new PurpleguyContact();
		$purpleguyContactModel->attributes = $_POST['PurpleguyContact'];
		if($purpleguyContactModel->validate()) {
			$purpleguyContactModel->create_time = time();
			$purpleguyContactModel->save(false);
			echo json_encode("1");
		} else {
			echo json_encode($purpleguyContactModel->getErrors());
		}
		exit;
	}
}