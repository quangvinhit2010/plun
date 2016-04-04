<?php

/**
 * @author vinhnguyen
 * @desc My Controller
 */
class MyController extends MemberController {

    /**
     * profile user
     */
    public function actionView($alias = null) {
        $roles = VHelper::model()->checkAccess('Manager', $this->user->id); 
        if(!empty($roles) || $this->user->status < 0){
            $this->redirect($this->usercurrent->getUserFeedUrl());
        }
        if(!empty(CParams::load()->params->visitor->enable)){
	        $userView = VstUser::model()->getUser($this->usercurrent);
	        if(!$this->usercurrent->isFriendOf($this->user->id) && !$userView->isViewProfile($this->user) && !$this->user->isMe()){
	        	if($userView->isLimitRightToViewProfile()){
	        		Yii::app()->user->setFlash('limitViewProfileInDay', true);
	                $this->redirect($this->usercurrent->getUserFeedUrl());
	        	}else{
	        		$vstUserViewUser = VstUserViewUser::model()->saveUserViewUser($this->usercurrent, $this->user);
	        	}        	
	        }
        }else{        	
	        $get = Yii::app()->cache->get('viewProfiles_'.$this->usercurrent->id);
	        $arrProfiles = array();
	        if(!empty($get)){
	            $arrProfiles = json_decode($get);
	        }
	        if(!$this->usercurrent->isFriendOf($this->user->id) && !in_array($this->user->id, $arrProfiles) && !$this->user->isMe()){
	            if(!empty($arrProfiles) && count($arrProfiles) >= Yii::app()->config->get('view_profile_in_day')){
	                Yii::app()->user->setFlash('limitViewProfileInDay', true);
	                $this->redirect($this->usercurrent->getUserFeedUrl());
	            }else{
	                if(!in_array($this->user->id, $arrProfiles)){
	                    array_push($arrProfiles, $this->user->id);
	                }
	                Yii::app()->cache->set('viewProfiles_'.$this->usercurrent->id, json_encode($arrProfiles), 86400);
	            }
	        }
        }
        
        //check settings - create new settings then it's empty
    	if($this->user->isMe()){
			$user_id	=	$this->usercurrent->id;
			$current_user	=	$this->usercurrent;
		}else{
			$user_id	=	$this->user->id;
			$current_user	=	$this->user;
		}
		        
    	$model = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user_id));
		if(!$model){
			//create settings
			$model     =   new UsrProfileSettings();
			$model->user_id       =   $user_id;
			$model->save();
		}
        //end check settings
		
        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('partial/view', array(
            		'current_user' => $current_user,
            		'friend_id' => $this->user->id
            ));
        else
            $this->render('page/view', array(
            		'current_user' => $current_user,
            		'friend_id' => $this->user->id
            ));
    }
    
    public function actionUploadAvatar() {
    	$params = CParams::load();
    	Yii::import ( "backend.extensions.plunUploader.upload");
    	$uploader = new upload($params->params->uploads->upload_method);
    	$uploader->allowedExtensions = array (
    			'jpg',
    			'jpeg',
    			'png'
    	);
    	$uploader->inputName	=	'image';
    	$uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes    	
    	
        //$imageFile = CUploadedFile::getInstanceByName('image');
                
        $thumb160x160 = $params->params->uploads->photo->thumb160x160;
        $thumb160x160_folder = $uploader->setPath ( $thumb160x160->p , false );
        
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && !isset($_POST['photo_id'])){
            $limit_public_upload = Photo::model()->count('user_id = :user_id AND status = 1 AND type = :type', array('user_id' => Yii::app()->user->id, 'type' => Photo::PUBLIC_PHOTO));
            
            //check sizes
            $result	=	array();
            
            list ( $width, $height ) = getimagesize ( $_FILES[$uploader->inputName]['tmp_name'] );
            if ($width < 200 || $height < 200) {            	
            	$result	=	array('status' => false,'error'=> Lang::t('photo', 'Please choose an image with minimum size is {limit_w}x{limit_h}px.', array('{limit_w}' => 200, '{limit_h}' => 200)));
            	echo CJSON::encode($result);
            	Yii::app()->end();
            }          
            
            if($limit_public_upload >= $params->params->uploads->photo->limit_upload->public){   
            	$result	=	array('status' => false,'error'=> Lang::t('general', 'Public photo is full, can be removed for upload new avatar'));            	 
            	echo CJSON::encode($result);
            	Yii::app()->end();
            }
            
            
            
            $photo  = VAvatar::model()->autoResizeAvatar();  

            
            $v	=	Yii::app()->request->getParam('v', false);
            $view = 'upload-avatar';
            if(!empty($v)){
                $view = $v;
            }
            //check images 
            if($params->params->uploads->upload_method == 'ftp'){
            	$check_detail425x320	=	$uploader->check_file_exists($params->params->uploads->ftp_basedir . DS . $photo->path . DS . 'detail425x320' . DS . $photo->name);
            }else{
            	$check_detail425x320	=	$uploader->check_file_exists($params->params->upload_path . DS . $photo->path . DS . 'detail425x320' . DS . $photo->name);
           	}
            $html	=	$this->renderPartial('partial/'.$view,array('photo'=>$photo, 'check_detail425x320' => $check_detail425x320), true, false);
            $result	=	array('status' => true,'html'=> $html);
            echo CJSON::encode($result);
            Yii::app()->end();
        }
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && isset($_POST['photo_id'])){
            $photo = Photo::model()->findByPk($_POST['photo_id']);
            if(!empty($photo) ){
            	
                $src = DS . $photo->path .'/detail425x320/'. $photo->name;
                //load images resource
                $uploader->loadImageResource($src);
                
                if(!empty($_POST['w']) && !empty($_POST['h']) && $uploader->img_resource->width >= $_POST['w'] && $uploader->img_resource->height >= $_POST['h']){
                	
                    //$thumb=Yii::app()->phpThumb->create($src);
                    //$thumb->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);
                    
                    $uploader->crop($_POST['w'], $_POST['h'],  $_POST['y'],  $_POST['x']);
                   	$uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
                    $uploader->saveImg($thumb160x160_folder  . DS . $photo->name);
                    $uploader->detroyResource();
                    //$thumb->resize($thumb160x160->w);
                    //                 $thumb->show();
                    //$thumb->save($thumb160x160_folder . DS . $photo->name);
                    
                	$photo->status = 1;
                	$photo->save();
                	//$this->usercurrent->avatar = $photo->id;
                	//$this->usercurrent->register_step = YumUser::REGISTER_STEP_COMPLETE;
                	
                	$model_member = Member::model()->findByAttributes(array('id' => Yii::app()->user->id));	
                	$model_member->register_step = YumUser::REGISTER_STEP_COMPLETE;
                	$model_member->avatar = $photo->id;
                	$model_member->save();
                	
                	$Elasticsearch = new Elasticsearch();
                	$Elasticsearch->updateSearchIndexUser(Yii::app()->user->id);
                	echo CJSON::encode(array('status'=>true, 'file'=>$photo->getImageThumbnail160x160(true).'?t='.time()));
                	Yii::app()->end();
                }
            }
        }
    }
    
    public function actionPhotosSetAvatar() {
    	$params = CParams::load ();

        Yii::import ( "backend.extensions.plunUploader.upload");
        $uploader = new upload($params->params->uploads->upload_method);
        $uploader->allowedExtensions = array (
        		'jpg',
        		'jpeg',
        		'png'
        );
        $uploader->sizeLimit = $params->params->uploads->photo->size; // maximum file size in bytes
        $thumb160x160 = $params->params->uploads->photo->thumb160x160;
                
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/avatar.js?t=' .time(), CClientScript::POS_END);
         
        if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && isset($_POST['photo_id'])){
            $photo = Photo::model()->findByPk($_POST['photo_id']);
            if(isset($photo)){
            	
            	/*
                $thumb160x160 = $params->params->uploads->photo->thumb160x160;
                $photo_origin = Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path . DS. 'origin'.  DS .$photo->name;
                $save_to = Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .DS. 'thumb160x160';
                VHelper::checkDir ( $save_to );
                */
            	
                //load images resource
                $src = DS . $photo->path .'/origin/'. $photo->name;
                $thumb160x160_folder	=	DS . $photo->path .'/thumb160x160/';
                
                $uploader->loadImageResource($src);
                $uploader->sharpen(20);
                $uploader->checkDir($thumb160x160_folder);
                $uploader->resizeImage($thumb160x160->w, $thumb160x160->h);
                $uploader->crop($thumb160x160->w, $thumb160x160->h, 'top');
                $uploader->saveImg($thumb160x160_folder . $photo->name);
                $uploader->detroyResource();
                                
                /*
                list ( $width, $height ) = getimagesize ( $photo_origin );
                if ($width > $height) {
                    $resize_type = Image::HEIGHT;
                } else {
                    $resize_type = Image::WIDTH;
                }
                Yii::app()->image->load($photo_origin)->resize($thumb160x160->w, $thumb160x160->h, $resize_type)->crop ( $thumb160x160->w, $thumb160x160->h, 'top' )->sharpen(20)->save($save_to . DS. $photo->name);
                */
                
                $this->usercurrent->avatar = $photo->id;
                if($this->usercurrent->save()){
                    //update index search
                    $Elasticsearch = new Elasticsearch();
                    $Elasticsearch->updateSearchIndexUser($this->usercurrent->id);
                    echo 1;
                } else {
                    echo 0;
                }
                Yii::app()->end();
            }
        }
         
        $public_photos = Photo::model ()->getPhotoByType($this->usercurrent->id, null, Photo::PUBLIC_PHOTO , $params->params->uploads->photo->limit_display->public_thumbnail);
    
    
        $this->render('page/photos-setavatar', array(
                'public_photos' => $public_photos,
        ));
    }
    
	public function actionQuicksearch(){
		$q	=	Yii::app()->request->getParam('q', false);
		$this->render('page/search', array(
			'q'	=>	$q
		));
		
	}
	
	public function actionGetUsersSuggest() {
		/*
	    $q = Yii::app()->request->getParam('q');
	    $user_id = Yii::app()->user->id;
	    if(!empty($q) && $user_id){
	        $sql = "SELECT tbl.user_id FROM (
                    SELECT inviter_id AS user_id  FROM usr_friendship WHERE (inviter_id = $user_id OR friend_id = $user_id) AND STATUS = 2
                    UNION ALL
                    SELECT friend_id AS user_id  FROM usr_friendship WHERE (inviter_id = $user_id OR friend_id = $user_id) AND STATUS = 2
                    ) tbl
                    WHERE tbl.user_id != $user_id
                    GROUP BY tbl.user_id";
    	    $cri = new CDbCriteria();
    	    $cri->addCondition("username LIKE :key AND id IN ($sql)");
    	    $cri->params = array(':key'=>"%$q%");
    	    $parents = Member::model()->findAll($cri);
    	    $results = array();
    	    foreach($parents as $p) {
    	        $results[] = array(
    	                'id' => $p->username,
    	                'text' => $p->username
    	        );
    	    }
    	    echo CJSON::encode($results);
	    }
	    Yii::app()->end();
	    */
		$keyword	=	Yii::app()->request->getParam('q', false);
		$offset = Yii::app()->request->getParam('offset', 0);
		
		//set keyword search
		$search_conditions = array(
				'keyword' => strtolower($keyword),
				'country_id'	=>	0
		);
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		//remove myself
		$user_id_except = array(Yii::app()->user->id);
		
		$elasticsearch	=	new Elasticsearch();
		$sort_script	=	"doc['username'].value.length()";
		$elasticsearch->setSortRules($sort_script, 'asc');
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, 5);
		
		$dbrows	=	array();
		foreach($data_search['fulldata'] AS $row){
			$row	=	$row['_source'];
			$dbrows[]	=	array(
					'id' => $row['username'],
					'text' => $row['username']
			);
		}
		echo CJSON::encode($dbrows);
		Yii::app()->end();		
	}
	
	public function actionCheckonline(){
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);

			$elasticsearch	=	new Elasticsearch();
			$online_data	=	$elasticsearch->checkOnlineStatus($my_friendlist);
			

						
			if(isset($online_data['online'])){
				if(isset($online_data['online'][Yii::app()->user->id])){
					unset($online_data['online'][Yii::app()->user->id]);
				}
				//remove myself
				if(sizeof($online_data['online']) > 0){
					echo json_encode(array('list' => array_values($online_data['online']), 'status' => true));
				}else{
					echo json_encode(array('status' => false));
				}
			}else{
				echo json_encode(array('status' => false));
			}
			exit;
		}
	}
	
	public function actionCheckOnlineByUsername() {
		if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
			$username = Yii::app()->request->getPost('username');
			
			if(Yii::app()->request->getPost('updateOffline')) {
				OfflineMessages::model()->updateOffline(Yii::app()->user->id, $username);
			}
			
			$Elasticsearch = new Elasticsearch();
			$user   = $Elasticsearch->load($username);
			
			if(time() - $user['last_activity'] <= Yii::app()->params->Elastic['update_activity_time'])
				echo '1';
			else
				echo '0';
			exit;
		}
	}
	
	public function actionProfile($uid, $type){
		if(empty($uid) || empty($type)){
			return false;
		}
		$Elasticsearch	=	new Elasticsearch();
		$e_user			=	$Elasticsearch->load($uid);
		if(!empty($e_user)){
			$params = CParams::load ();
			switch ($type){
				case 'avatar':
					$path = "http://{$params->params->img_webroot_url}{$e_user['avatar']}";
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					header('Content-Type: '.$ext);
					echo file_get_contents($path);					
					break;
			}
			Yii::app()->end();
		}
	}	
	
}