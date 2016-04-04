<?php
class Table42Controller extends Controller {
	/**
	 * Declares class-based actions.
	 */
    public function actions()
	{
		return array(
		// captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'backend.extensions.captchaExtended.CaptchaExtendedAction',
                'mode'=> CaptchaExtendedAction::MODE_NUM,
                'offset'=>'4',
                'density'=>'0',
                'lines'=>'0',
                'fillSections'=>'0',
                'foreColor'=>'0x000000',
                'minLength'=>'6',
                'maxLength'=>'6',
                'fontSize'=>'24',
                'angle'=>false,
		    ),
		);
	}
	public function actionLogin(){
		$model=new LoginForm();
		$status	=	array();
		if(isset($_POST['LoginForm']) && Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['LoginForm'];
			$model->validate();		
			if(!$model->hasErrors() && $model->login()){
				$status['status']	=	true;
			}else{
				$status['status']	=	false;
				
				if(isset($model->errors['password'][0])){
					$status['msg'][]	=	$model->errors['password'][0];
				}
				if(isset($model->errors['username'][0])){
					$status['msg'][]	=	$model->errors['username'][0];
				}				
			}
		}else{
			$status['status']	=	false;
		}
		echo json_encode($status);
		Yii::app()->end();
	}
	public function actionRegister(){
		$model = new RegisterForm();
		$status	=	array(
			'status'	=>	true
		);
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('RegisterForm');
			
			$model->attributes = $data;
			$model->lastname = $data['username'];
			$model->firstname = $data['username'];
			$model->validate();
		
			if(!$model->hasErrors()){
				if($model->save()){
					$status['status']	=	true;
					$frlogin=new LoginForm();
					$frlogin->loginByUsernamePass($model->username, $model->password, false);
				}
			}else{
				$status['status']	=	false;
				$status['msg']		=	$model->errors;
			}
		}
		echo json_encode($status);
		Yii::app()->end();
	}
	public function actionSignUp(){
		$status		=	array();
		$input_info = Yii::app()->request->getPost('signup_info', false);
		$sex_role = Yii::app()->request->getPost('sex_role', false);
		$nextround	=	Table42Round::model()->getNextRound();
		

		if(isset(Yii::app()->user->id)){		
			$data			=	Yii::app()->user->data();
			//get profile settings
			$profile_setting_model = UsrProfileSettings::model()->findByAttributes(array('user_id' =>$data['id']));
			
			if($sex_role){
				//update sex role
				if(!$profile_setting_model){
					$profile_setting_model				= 	new UsrProfileSettings();
					$profile_setting_model->user_id 	=	Yii::app()->user->id;
				}
				$profile_setting_model->sex_role	=	$sex_role;
				$profile_setting_model->save();
			}
			
			if($profile_setting_model->sex_role == ProfileSettingsConst::SEXROLE_TOP || $profile_setting_model->sex_role== ProfileSettingsConst::SEXROLE_BOTTOM){
							
			if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
				
				
				//check profile exist ??
				$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'published' => 1));
				//end check
				
				if($checkprofile){
					if($checkprofile->round_id != $this->currentround->id && $checkprofile->round_id != $nextround->id){
						$can_signup = true;
					}else{
						$can_signup = false;
					}
				}else{
					$can_signup = true;
				}
				
				if(!$can_signup){
					//if you did signup before
					$status['status']	=	2; //ready for signup before
				}else{
					//create new profile
					
					if($input_info){
						
						$time	=	time();
						$time_expire	=	$this->currentround->time_start + Yii::app()->params->time_configs['signup_time'] * (3600 * 24);
						
						//check signup time
						if($this->currentround->time_start <= $time && $time <= $time_expire){
							
							$round	=	$this->currentround;
						}else{
							$round	=	$nextround;
						}
						
						//disabled other profile
						Table42Profile::model()->updateAll(array('published' => 0), 'user_id =' . Yii::app()->user->id);
						
						
						//check input info confirm
						$model     	=   new Table42Profile();
						
						$model->user_id	=	$data['id'];
						$model->round_id	=	$round->id;
						$model->sex_role	=	$profile_setting_model->sex_role;
						$model->username	=	$data['username'];
						$model->email	=	$data['email'];
						$model->step	=	Table42Profile::STEP_SIGNUP;
						$model->status	=	Table42Profile::STATUS_PENDING;
						$model->date_created	=	time();
						
						$check	=	preg_match('@facebook@i', $input_info, $matchs);
						if($check){
							$model->facebook_id	=	$input_info;
						}else{
							$model->phone	=	$input_info;
						}
						$model->save();
						$status['status']	=	1; //signup ok
						
						/*
						//begin send message
						Yii::app()->setBasePath(Yii::getPathOfAlias ( 'frontend' ));
						Yii::import('frontend.models.*');
						
						$msg = new MessageForm();
						$msg->from = 'plunasia';
						$msg->to = $data['username'];
						$link_confirm	=	Yii::app()->getBaseUrl(true) . Yii::app()->createUrl('//table42/active', array('alias' => md5($data['id'])));
						$msg->subject = 'Xác Nhận Đăng Ký Sự Kiện Table For Two';
						$msg->body = "Chào {$data['username']}, </br> - Chúng Tôi Vừa Nhận Được Thông Tin Đăng Ký Tham Gia Event 'Table For Two', Vui Lòng Click Vào Link 
							Bên Dưới Để Xác Nhận Việc Đăng Ký: </br><a href='{$link_confirm}'>{$link_confirm}</a>";
						$msg->is_system = 1;
						$msg->validate();
						$msg->send();
						
						DataNodejs::updateTotalMessage(Yii::app()->user->id);
						*/
						
					}
				}
			}		
			//
			}else{
				$status['status']	=	4; //signup for next round
			}
		}else{
			$status['status']	=	0; //no login
		}
		echo json_encode($status);
		Yii::app()->end();
	}
	/*
	public function actionActive(){
		
		
		$nextround	=	Table42Round::model()->getNextRound();
		//if you did signup before
		$signup	=	false;
		
		//check profile exist ??
		$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'published' => 1));
		//end check				
		
		$profile_setting_model = UsrProfileSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
				
		if($checkprofile){
			if($checkprofile->step >= Table42Profile::STEP_CONFIRM){
				$this->redirect('/site/index');
			}else{
				//update step 
				$checkprofile->step	=	Table42Profile::STEP_CONFIRM;
				$checkprofile->save();
			}
			//end update step - complete upload
			
			if(($checkprofile->round_id == $this->currentround->id || $checkprofile->round_id == $nextround->id) && $checkprofile->step == Table42Profile::STEP_THUMBNAIL){
				$signup	=	true;
			}else{
				//repeat signup
				$signup	=	false;
			}
		}else{
			$signup	=	false;
		}
		if($profile_setting_model){
			if($profile_setting_model->sex_role == ProfileSettingsConst::SEXROLE_TOP || $profile_setting_model->sex_role== ProfileSettingsConst::SEXROLE_BOTTOM){
				$sex_role	=	1;
			}else{
				if(is_null($profile_setting_model->sex_role)){
					$sex_role	=	0;
				}else{
					$sex_role	=	2;
				}
			}
		}else{
			$sex_role	=	0;
		}		
		
		$model=new LoginForm();
		$register_model = new RegisterForm();
		
		$this->render('active', array(
			'model'	=>	$model,
			'signup_ok'	=>	$this->signup_ok,
			'currentround'	=>	$this->currentround,
			'sex_role'	=>	$sex_role,
			'upload'	=>	($checkprofile->step	==	Table42Profile::STEP_CONFIRM)	?	true	:	false,
			'signup'	=>	$signup,
			'profile'	=>	$checkprofile
		));		
	}
	*/
	public function actionUpload(){
		$params = CParams::load ();
		$thumb203x204 = $params->params->uploads->photo->thumb203x204;
		$thumb768x1024 = $params->params->uploads->photo->thumb768x1024;
		
		$origin = $params->params->uploads->photo->origin;
		
		Yii::import ( "backend.extensions.plunUploader.upload");
		$uploader = new upload($params->params->uploads->upload_method);
		$uploader->allowedExtensions = array (
				'jpg',
				'jpeg',
				'png'
		);
		$uploader->inputName	=	'table42file';
		$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
			
		$thumb203x204_folder = $uploader->setPath ( $thumb203x204->p , false );
		$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
		
		$origin_folder = $uploader->setPath ( $origin->p, false );
		$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
		$result = $uploader->upload ( $origin_folder );	
			
		//check profile exist ??
		$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'published' => 1));
		//end check	
		
		if($checkprofile){
			if ($result ['success']) {
				// get origin path after file upload successfully
				$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
				//load images resource
				$uploader->loadImageResource($origin_uploaded_path);
				
				// resize & crop for thumbnail (width 203 height 204)
				$uploader->resizeImage($thumb203x204->w, $thumb203x204->h);
				$uploader->sharpen(20);
				$uploader->crop($thumb203x204->w, $thumb203x204->h, 'top');
				$uploader->saveImg($thumb203x204_folder . DS . $uploader->getUploadName ());
				
				// resize & crop for thumbnail (width 768 height 1024)
				$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
				$uploader->sharpen(20);
				$uploader->crop($thumb768x1024->w, $thumb768x1024->h, 'top');
				$uploader->saveImg($thumb768x1024_folder . DS . $uploader->getUploadName ());		
	
				// save to database
				$model = new Table42Photo ();
				$model->profile_id = $checkprofile->id;
				$model->title = pathinfo ( $uploader->getUploadName (), PATHINFO_FILENAME );
				$model->name = $uploader->getUploadName ();
				$model->path = ltrim($path_folder,'\/');
				$model->status = Table42Photo::STATUS_INACTIVE;
				$model->date_created = time ();
				$model->save ();			
				
				$uploader->detroyResource();
				
		        $result['html'] = $this->renderPartial('//table42/partial/upload_photo_result', array(
		        		'model'	=>	$model
		        ), true);
	
			}
		}
		echo json_encode($result);
		exit;
	}
	public function actionImportpublicphoto(){
		$result	=	array();
		$type		 = Photo::PUBLIC_PHOTO;
		if(isset(Yii::app()->user->id)){
			$user_id	=	Yii::app()->user->id;
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->addCondition("user_id = {$user_id}");
			$CDbCriteria->addCondition("type = {$type}");
			$CDbCriteria->addCondition("status = 1");
			
			$photos	=	Photo::model()->findAll($CDbCriteria);
			if(sizeof($photos)){
				$result['status'] = true;
				$result['html'] = $this->renderPartial('//table42/partial/import_publicphoto_result', array(
						'photos'	=>	$photos
				), true);
			}else{
				$result['status'] = false;
			}
		}
		echo json_encode($result);
		exit;
	}
	public function actionChoosephoto(){
		$result	=	array();
		
		//check profile exist ??
		$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'published' => 1));
		//end check
						
		$type = Yii::app()->request->getPost('type', 1);
		$photoids = Yii::app()->request->getPost('photoids', false);
		//upload from my computer
		if($type == 1){		
			$thumbnail_id	=	isset($photoids[0])	?	$photoids[0]	:	0;
			$check	=	Table42Photo::model()->updateAll(array('status' => Table42Photo::STATUS_ACTIVE), "id IN (" . implode(',', $photoids) . ')');
			$result['status']	=	$check > 0	?	1 : 0;
			
		//import from public photos	
		}else{
			$params = CParams::load ();
			$thumb203x204 = $params->params->uploads->photo->thumb203x204;
			$thumb768x1024 = $params->params->uploads->photo->thumb768x1024;
			
			
			Yii::import ( "backend.extensions.plunUploader.upload");
			$uploader = new upload($params->params->uploads->upload_method);
			$uploader->allowedExtensions = array (
					'jpg',
					'jpeg',
					'png'
			);
			$thumb203x204_folder = $uploader->setPath ( $thumb203x204->p , false );
			$thumb768x1024_folder = $uploader->setPath ( $thumb768x1024->p , false );
			$path_folder = $uploader->setPath ( $params->params->uploads->photo->path, false );
			
			//copy images
			foreach($photoids AS $pid){
				$photo	=	Photo::model()->findByAttributes(array('id' => $pid));
				
				//load images resource
				$src = DS . $photo->path .'/origin/'. $photo->name;
			
				//get unique name
				if($params->params->uploads->upload_method == 'ftp'){
					$path = $thumb203x204_folder;
				}else{
					$path = $params->params->upload_path . DS . $thumb203x204_folder;
				}
				$check_thumb203x204	=	 $uploader->getUniqueTargetPath($path, $photo->name);
				$pathinfo = pathinfo($check_thumb203x204);
				$basename = $pathinfo['basename'];

				
				$uploader->loadImageResource($src);

				$uploader->resizeImage($thumb768x1024->w, $thumb768x1024->h);
				$uploader->sharpen(20);
				$uploader->crop($thumb768x1024->w, $thumb768x1024->h, 'top');
				$uploader->saveImg($thumb768x1024_folder . DS . $basename);
				
				$uploader->resizeImage($thumb203x204->w, $thumb203x204->h);
				$uploader->sharpen(20);
				$uploader->crop($thumb203x204->w, $thumb203x204->h, 'top');
				$uploader->saveImg($thumb203x204_folder . DS . $basename);
				
				
				// save to database
				if($checkprofile){
					$model = new Table42Photo ();
					$model->profile_id = $checkprofile->id;
					$model->title = $pathinfo['filename'];
					$model->name = $basename;
					$model->path = ltrim($path_folder,'\/');
					$model->status = Table42Photo::STATUS_INACTIVE;
					$model->date_created = time ();
					$model->save ();	
				}
				
				
				//set thumbnail id
				if(!isset($thumbnail_id)){
					$thumbnail_id	=	$model->id;
					$result['status']	=	1;
				}
				
			}

		}
		//check profile
		if($checkprofile){
			$checkprofile->thumbnail_id	=	$thumbnail_id;
			$checkprofile->step	=	Table42Profile::STEP_THUMBNAIL;
			$checkprofile->save();
		}
		if($checkprofile->round_id != $this->currentround->id){
			$result['status']	=	2;
		}else{
			$result['status']	=	1;
		}
		
		echo json_encode($result);
		exit;
	}
	public function actionListmember(){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			
			$limit = Yii::app()->request->getPost('limit', 10);
			$paging = Yii::app()->request->getPost('paging', 1);
			$type = Yii::app()->request->getPost('type', ProfileSettingsConst::SEXROLE_TOP);			
			
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->addCondition('sex_role = :sex_role');
			$CDbCriteria->order	=	'date_created DESC';
			$CDbCriteria->offset	=	($paging - 1) * $limit;
			$CDbCriteria->limit		=	$limit;
			$CDbCriteria->params = array(':status' => Table42Profile::STATUS_APPROVED, 'round_id' => $this->currentround->id, 'sex_role' => $type);
			
			$members	=	Table42Profile::model()->findAll($CDbCriteria);
			
			
			//get total
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->select	=	'COUNT(user_id) AS total';
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->addCondition('sex_role = :sex_role');
			$CDbCriteria->params = array(':status' => Table42Profile::STATUS_APPROVED, 'round_id' => $this->currentround->id, 'sex_role' => $type);
			$row	=	Table42Profile::model()->find($CDbCriteria);

			if(sizeof($members) > 0){
				$result['status']	=	true;
				$result['html']	= 	$this->renderPartial('//table42/partial/listmember', array(
						'members'	=>	$members
				), true);
				if($row->total > ($paging - 1) * $limit + $limit){
					$result['next_page']	=	true;
				}else{
					$result['next_page']	=	false;
				}
			}else{
				$result['status']	=	false;
			}

		}else{
			$this->render('listmember', array(
				'signup_ok'	=>	$this->signup_ok,
			));		
		}

		echo json_encode($result);
		exit;		
	}
	public function actionGetcoupledetail($requestid){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
							
				//check request dating status
				$request	=	Table42DatingRequest::model()->findByAttributes(array('id' => $requestid));
				if($request){
					//check profile
					$friend_profile	=	Table42Profile::model()->findByAttributes(array('id' => $request->friend_id));
					
					$inviter_profile	=	Table42Profile::model()->findByAttributes(array('id' => $request->inviter_id));
					
					$result['html']	= 	$this->renderPartial('//table42/partial/showcoupledetail', array(
							'friend_profile'		=>	$friend_profile,
							'request'				=>	$request,
							'inviter_profile'		=>	$inviter_profile,
							'friend_profile_full'	=>	$friend_profile->getFullprofiledetail(),
							'inviter_profile_full'	=>	$inviter_profile->getFullprofiledetail(),
					), true);
				}
		}
		echo json_encode($result);
		exit;
	}	
	public function actionGetprofiledetail($profileid){
		$result	=	array();
		$request = false;
		
		if (Yii::app()->request->isAjaxRequest) {
			//check profile
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
			
			
			
			if($checkprofile){
				//check request dating status
								
				$accept_dating	=	false;
				if($my_profile){
					$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $profileid);
					
					if($my_profile->sex_role != $checkprofile->sex_role && $my_profile->id != $profileid){
						$accept_dating	=	true;
					}else{
						$accept_dating	=	false;
					}
				}
				
				//get list photo
				$photos	=	Table42Photo::model()->findAllByAttributes(array('profile_id' => $profileid, 'status' => 1));

				$list_photo	=	array();
				$c = 1;
				foreach($photos AS $row){
					if($row->id != $checkprofile->thumbnail_id){
						$list_photo[]	=	$row;
						if($c >= 2){
							break;
						}
						$c++;
					}
					
				}
				//load profile detail
				
				$result['html']	= 	$this->renderPartial('//table42/partial/profiledetail', array(
						'profile'	=>		$checkprofile,
						'list_photo'	=>	$list_photo,
						'detail_profile'	=>	$checkprofile->getFullprofiledetail(),
						'my_profile'	=>	$my_profile,
						'request'		=>	$request,
						'accept_dating'	=>	$accept_dating
				), true);			
			}
		}
		echo json_encode($result);
		exit;
	}
	public function actionShowProfileChooseDating($profileid){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			//check profile
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
								
			if($checkprofile){
				//check request dating status
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $profileid);
				
				//$check_makedating	=	Table42DatingRequest::model()->getUserMakeDating($my_profile->id);
				
				$result['html']	= 	$this->renderPartial('//table42/partial/showprofilechoosedating', array(
						'profile'	=>		$checkprofile,
						'my_profile'	=>	$my_profile,
						'detail_profile'	=>	$checkprofile->getFullprofiledetail(),
						'my_detail_profile'	=>	$my_profile->getFullprofiledetail(),
						'my_profile'	=>	$my_profile,
						//'check_makedating'	=>	$check_makedating,
						'request'		=>	$request,
				), true);
			}
		}
		echo json_encode($result);
		exit;		
	}
	public function actionGetprofileagree($profileid){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			//check profile
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
				
				
				
			if($checkprofile){
				//check request dating status
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $profileid);
					
				$accept_dating	=	false;
				if($my_profile){
					
					if($my_profile->id != $profileid && $request->status == Table42DatingRequest::STATUS_REQUEST){
						$accept_dating	=	true;
					}else{
						$accept_dating	=	false;
					}
				}
	
				//get list photo
				$photos	=	Table42Photo::model()->findAllByAttributes(array('profile_id' => $profileid, 'status' => 1));
	
				$list_photo	=	array();
				$c = 1;
				foreach($photos AS $row){
					if($row->id != $checkprofile->thumbnail_id){
						$list_photo[]	=	$row;
						if($c >= 2){
							break;
						}
						$c++;
					}
						
				}
				//load profile detail
	
				$result['html']	= 	$this->renderPartial('//table42/partial/profiledetailagree', array(
						'profile'	=>		$checkprofile,
						'list_photo'	=>	$list_photo,
						'detail_profile'	=>	$checkprofile->getFullprofiledetail(),
						'my_profile'	=>	$my_profile,
						'request'		=>	$request,
						'accept_dating'	=>	$accept_dating
				), true);
			}
		}
		echo json_encode($result);
		exit;
	}	
	public function actionRequestdating(){
		$profileid = Yii::app()->request->getPost('profileid', false);
		
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile		=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
			if($checkprofile && $my_profile){
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $checkprofile->id);
				if(!$request){
					$model	=	new Table42DatingRequest();
					$model->inviter_id	=	$my_profile->id;
					$model->friend_id	=	$profileid;
					$model->round_id 	=	$this->currentround->id;
					$model->status		=	Table42DatingRequest::STATUS_REQUEST;
					$model->requesttime	=	time();
					$model->updatetime	=	0;
					$model->validate();
					
					if($model->save()){
						$result['status']	=	1;
					}else{
						$result['status']	=	0;
					}
				}else{
					$result['status']	=	0;
				}
			}else{
				$result['status']	=	0;
			}
		}
		echo json_encode($result);
		exit;
	}
	public function actionAgreedating(){
		$profileid = Yii::app()->request->getPost('profileid', false);
	
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile		=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
			if($checkprofile && $my_profile){
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $checkprofile->id);
				if($request){
					$request->status		=	Table42DatingRequest::STATUS_ACCEPTED;		
					$request->updatetime		=	time();
					if($request->save()){
						$result['status']	=	1;
						
					}else{
						$result['status']	=	0;
					}
				}else{
					$result['status']	=	0;
				}
			}else{
				$result['status']	=	0;
			}
		}
		echo json_encode($result);
		exit;
	}	
	public function actionMakedating(){
		$profileid = Yii::app()->request->getPost('profileid', false);
		
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile		=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
			if($checkprofile && $my_profile){
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $checkprofile->id);
				if($request){
					//check make dating before
					$check_makedating	=	Table42DatingRequest::model()->getUserMakeDating($my_profile->id);
					
					if(!$check_makedating){
						//no set dating before
						if(!$request->is_dating){
							$request->is_dating		=	1;
							$request->updatetime		=	time();
							$request->user_make_dating		=	$my_profile->id;
							if($request->save()){
								$result['status']	=	1;
							}else{
								$result['status']	=	0;
							}
						}else{
							$result['status']	=	0;
						}
					}else{
						$result['status']	=	2;
					}
				}else{
					$result['status']	=	0;
				}
			}else{
				$result['status']	=	0;
			}
		}
		echo json_encode($result);
		exit;		
	}
	public function actionCanceldating(){
		$profileid = Yii::app()->request->getPost('profileid', false);
	
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('id' => $profileid));
			$my_profile		=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
			if($checkprofile && $my_profile){
				$request	=	Table42DatingRequest::model()->getFriendShip($my_profile->id, $checkprofile->id);
				if($request){
					//no set dating before
					if($request->is_dating){
						$request->is_dating		=	0;
						$request->updatetime		=	time();
						$request->user_make_dating		=	0;
						if($request->save()){
							$result['status']	=	1;
						}else{
							$result['status']	=	0;
						}
					}else{
						$result['status']	=	0;
					}
				}else{
					$result['status']	=	0;
				}
			}else{
				$result['status']	=	0;
			}
		}
		echo json_encode($result);
		exit;
	}	
	public function actionListrequest(){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
			
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
				
			$limit = Yii::app()->request->getPost('limit', 10);
			$paging = Yii::app()->request->getPost('paging', 1);
				
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->addCondition('friend_id = :friend_id');
			$CDbCriteria->order	=	'requesttime DESC';
			$CDbCriteria->offset	=	($paging - 1) * $limit;
			$CDbCriteria->limit		=	$limit;
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_REQUEST, 'round_id' => $this->currentround->id,'friend_id' => $checkprofile->id);
				
			$members	=	Table42DatingRequest::model()->findAll($CDbCriteria);
				
				
			//get total
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->select	=	'COUNT(id) AS total';
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('friend_id = :friend_id');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_REQUEST, 'round_id' => $this->currentround->id,'friend_id' => $checkprofile->id);
						
			$row	=	Table42DatingRequest::model()->find($CDbCriteria);
		
			if(sizeof($members) > 0){
				$result['status']	=	true;
				$result['html']	= 	$this->renderPartial('//table42/partial/listrequest', array(
						'members'	=>	$members
				), true);
				if($row->total > ($paging - 1) * $limit + $limit){
					$result['next_page']	=	true;
				}else{
					$result['next_page']	=	false;
				}
			}else{
				$result['status']	=	false;
			}
		
		}else{
			$this->render('listrequest', array(
					'signup_ok'	=>	$this->signup_ok,
			));
		}
		
		echo json_encode($result);
		exit;		
	}
	public function actionListagree(){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
	
			$limit = Yii::app()->request->getPost('limit', 10);
			$paging = Yii::app()->request->getPost('paging', 1);
			
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
				
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('friend_id = :friend_id OR inviter_id= :friend_id');
			$CDbCriteria->addCondition('round_id = :round_id');
			
			$CDbCriteria->order	=	'requesttime DESC';
			$CDbCriteria->offset	=	($paging - 1) * $limit;
			$CDbCriteria->limit		=	$limit;
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_ACCEPTED, 'round_id' => $this->currentround->id, 'friend_id' => $checkprofile->id);
	
			$members	=	Table42DatingRequest::model()->findAll($CDbCriteria);
	
	
			//get total
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->select	=	'COUNT(id) AS total';
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('friend_id = :friend_id OR inviter_id= :friend_id');
			$CDbCriteria->addCondition('round_id = :round_id');
	
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_ACCEPTED, 'round_id' => $this->currentround->id, 'friend_id' => $checkprofile->id);
				
			$row	=	Table42DatingRequest::model()->find($CDbCriteria);
	
			if(sizeof($members) > 0){
				$result['status']	=	true;
				$result['html']	= 	$this->renderPartial('//table42/partial/listagree', array(
						'members'	=>	$members
				), true);
				if($row->total > ($paging - 1) * $limit + $limit){
					$result['next_page']	=	true;
				}else{
					$result['next_page']	=	false;
				}
			}else{
				$result['status']	=	false;
			}
	
		}else{
			$this->render('listagree', array(
					'signup_ok'	=>	$this->signup_ok,
			));
		}
	
		echo json_encode($result);
		exit;
	}	
	public function actionListcouple(){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest) {
				
			$limit = Yii::app()->request->getPost('limit', 10);
			$paging = Yii::app()->request->getPost('paging', 1);
	
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('is_dating = 1');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->order	=	'vote_total DESC';
			$CDbCriteria->offset	=	($paging - 1) * $limit;
			$CDbCriteria->limit		=	$limit;
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_ACCEPTED, 'round_id' => $this->currentround->id);
	
			$members	=	Table42DatingRequest::model()->findAll($CDbCriteria);
	
	
			//get total
			$CDbCriteria = new CDbCriteria;
			$CDbCriteria->select	=	'COUNT(id) AS total';
			$CDbCriteria->addCondition('status = :status');
			$CDbCriteria->addCondition('is_dating = 1');
			$CDbCriteria->addCondition('round_id = :round_id');
			$CDbCriteria->order	=	'requesttime DESC';
			$CDbCriteria->params = array(':status' => Table42DatingRequest::STATUS_ACCEPTED, 'round_id' => $this->currentround->id);
					
			$row	=	Table42DatingRequest::model()->find($CDbCriteria);
	
			if(sizeof($members) > 0){
				$result['status']	=	true;
				$result['html']	= 	$this->renderPartial('//table42/partial/listcouple', array(
						'members'	=>	$members
				), true);
				if($row->total > ($paging - 1) * $limit + $limit){
					$result['next_page']	=	true;
				}else{
					$result['next_page']	=	false;
				}
			}else{
				$result['status']	=	false;
			}
	
		}else{
			$this->render('listcouple', array(
					'signup_ok'	=>	$this->signup_ok,
			));
		}
	
		echo json_encode($result);
		exit;
	}
	public function actionVotecouple(){
		$result	=	array();
		if (Yii::app()->request->isAjaxRequest && Yii::app()->user->id) {
			$requestid = Yii::app()->request->getPost('requestid', false);
			
			$checkprofile	=	Table42Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'round_id' => $this->currentround->id));
				
			//check request
			$request	=	Table42DatingRequest::model()->findByAttributes(array('id' => $requestid));
			if($request && !$this->currentround->disable_vote){
				//check vote for yourself
				if($request->inviter_id == $checkprofile->id|| $request->friend_id == $checkprofile->id){
					$result['status']	=	3;
				}else{
					
					//check vote status
					$vote	=	Table42Vote::model()->getVoteBy(Yii::app()->user->id, $requestid, $this->currentround->id);
					if(!$vote){
						$vote = new Table42Vote();
						$vote->vote_by	=	Yii::app()->user->id;
						$vote->vote_for_id = $requestid;
						$vote->vote_for_type	=	Table42Vote::TYPE_VOTE_COUPLE;
						$vote->round_id 	=	$this->currentround->id;
						$vote->date_created	=	time();
						$vote->ip  = $_SERVER['REMOTE_ADDR'];
						if($vote->save()){
							//vote ok
							$result['status']	=	1;
							$total_vote			=	Table42Vote::model()->getTotalvote($requestid, $this->currentround->id);
							//update total vote
							$result['total']	=	$total_vote;
							$request->vote_total = $total_vote;
							$request->save();
						}else{
							//vote unsuccessfully
							$result['status']	=	0;
						}
					}else{
						//vote ready
						$result['status']	=	2;
					}
				}
				
			}else{
				$result['status']	=	0;
			}
		}else{
			//no login
			$result['status']	=	4;
		}
		echo json_encode($result);
		exit;
	}
	public function actionResult(){
		$result	=	Table42Result::model()->getResult();
		if(!$result){
			$this->redirect('/site/index');
		}
		$request	=	Table42DatingRequest::model()->findByAttributes(array('id' => $result->couple_id));
		
		
		$friend_profile	=	Table42Profile::model()->findByAttributes(array('id' => $request->friend_id));
		
		$inviter_profile	=	Table42Profile::model()->findByAttributes(array('id' => $request->inviter_id));
		
		
		
		$this->render('result', array(
			'signup_ok'	=>	$this->signup_ok,
			'friend_profile_full'	=>	$friend_profile->getFullprofiledetail(),
			'inviter_profile_full'	=>	$inviter_profile->getFullprofiledetail(),
			'result'	=>	$result,
			'request'	=>	$request,
				'friend_profile'		=>	$friend_profile,
				'inviter_profile'		=>	$inviter_profile,
		));		
	}
}