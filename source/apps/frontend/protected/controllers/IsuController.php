<?php
/*
 *Nam Le 
 *nam@dwm.vn
 *I See U controller
 */

class IsuController extends Controller {
	
	public $clsAccNotActived;
	
	public function actionIndex($page = null, $type = null, $render = 'render'){
		if(isset($type) && $type == 'me' && !Yii::app()->user->isGuest){
			$me_condition = 'AND user_id = :user_id';
		}
		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		if(isset($me_condition)){
			$criteria->addCondition('status = :status ' . $me_condition);
		} else {
			$criteria->addCondition('status = :status OR (user_id = :user_id AND status != :status)');
		}
		$criteria->params = array(':status' => Notes::STATUS_ACTIVE, ':user_id' => Yii::app()->user->id);
		$criteria->order = 't.id DESC';
		$total = Notes::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['isu_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$isus = Notes::model()->findAll($criteria);
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($isus) > 0 ){
				$this->renderPartial('partial/items',array(
						'isus' => $isus,
						'pages' => $pages,
						'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->$render('page/index', array(
					'isus' => $isus,
					'pages' => $pages,
					'next_page' => $next_page,
					'my_isu' => Notes::model()->exists('user_id = :user_id AND status = 1', array(':user_id' => Yii::app()->user->id)),
			));
		}
		
	}
	
	public function actionMy($page = null){

		$page = (isset($page)) ? $page : 1;
		$criteria=new CDbCriteria;
		$criteria->addCondition('user_id = :user_id');
		$criteria->addCondition('status = 1');
		$criteria->params = array(':user_id' => Yii::app()->user->id);
		$criteria->order = 'id';
		
		$total = Notes::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['isu_limit'];
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$isus = Notes::model()->findAll($criteria);
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($isus) > 0 ){
				$this->renderPartial('partial/index_ajax',array(
						'isus' => $isus,
						'pages' => $pages,
						'next_page' => $next_page,
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
					'isus' => $isus,
					'pages' => $pages,
					'next_page' => $next_page,
			));
		}
		
	}
	
	public function actionDelete($id){
		if(isset($id)){
			$model =	$this->loadModel($id);
			if(Yii::app()->request->isAjaxRequest && isset(Yii::app()->user->id) && Yii::app()->user->id == $model->user_id)
			{
				$model->deletePermanentlyPhoto();
				$model->delete();
				echo json_encode(array('status'=>true));
				Yii::app()->end();
			}
		}
	
	}
	
	
	public function actionEdit($id){
		if(isset($id)){
			$model =	$this->loadModel($id);
			if(isset($_POST['Notes']) && isset(Yii::app()->user->id))
			{
				$model->attributes=$_POST['Notes'];
				$model->date = strtotime($model->date);
				$model->end_date = strtotime($model->end_date);
				$model->status = Notes::STATUS_PENDING;
				$model->modify = time();
				$model->validate();
				
				if(!$model->hasErrors()){
					$model->save();
					$my_isus =    $model->getMyISU();
					$after_save = $this->renderPartial('partial/load', array('isu' => $model, 'my_isus' => $my_isus), true);
					echo json_encode(array('status'=>true,'url'=> Yii::app()->createUrl('//isu/load', array('id' => $model->id)),  'after_save' => $after_save));
					Yii::app()->end();
				}else{
					echo json_encode($model->errors);
					Yii::app()->end();
				}
				
			} 
			
			
			Yii::app()->clientScript
			->registerCoreScript( 'jquery' )
			->registerScriptFile( Yii::app()->theme->baseUrl . '/resources/html/js/jquery.simple-dtpicker.js' )
			->registerCssFile( Yii::app()->theme->baseUrl . '/resources/html/css/jquery.simple-dtpicker.css' );
			$this->render('partial/form', array('model' => $model));
		}
	
	}
	
	public function actionLoad($id){
		if(isset($id)){
			$model = $this->loadModel($id); 
			if($model){
// 				$my_isus =    $model->getISU($model->country_id, null/* $model->city_id */, Notes::FIRST_PAGE);
				if(Yii::app()->request->isAjaxRequest){
					$this->renderPartial('partial/detail', array('isu' => $model));
				} else{
					$this->render('partial/load', array('isu' => $model));
				}
			}
		}
		
	}
	
	public function actionCreate(){
		
		if(Yii::app()->user->isGuest){
		    Yii::app()->user->setFlash('msgLogin', Lang::t('login', 'Please sign-in to use this feature!'));
		    $this->redirect(Yii::app()->createUrl('//site/login', array('redirect_url'=>Yii::app()->createAbsoluteUrl('/isu'))));
			throw new CHttpException(403, 'You must login !');
		}
		
		
		$model = new Notes();
		$country_in_cache = new CountryonCache();
		$state_in_cache = new StateonCache();
		$city_in_cache = new CityonCache();
			
			
		$list_city = array();
		$list_state = array();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Notes']) && isset(Yii::app()->user->id))
		{
			
			$text_venue = !empty($_POST['Notes']['check_in_location_isu'])	?	$_POST['Notes']['check_in_location_isu']	:	false;
			$venue_id = isset($_POST['Notes']['isu_suggest_id_venue'])	?	intval($_POST['Notes']['isu_suggest_id_venue'])	:	0;
			
			$model->attributes=$_POST['Notes'];
			$model->user_id = Yii::app()->user->id;
			
			$model->date = strtotime($model->date);
			$model->end_date = strtotime($model->end_date);
			
			$model->status = Notes::STATUS_ACTIVE; 
			$model->created = time(); 
			$model->validate();
			if(!$model->hasErrors()){
				
				
				//begin processing venues
				$venue	=	new venues();
				if($text_venue){
					if(!$venue_id){
						 
						$venues_searchdata	=	$venue->search($text_venue, array('country_id' => $_POST['Notes']['country_id']));
						$parent_id	=	0;
						if(isset($venues_searchdata['data'][0])){
							$parent_id	=	$venues_searchdata['data'][0]['_source']['venue_id'];
						}
						//create venue
						$venue_model	=	new CmsVenues();
						$venue_create	=	array(
								'title'	=>	htmlspecialchars($text_venue),
								'title_nosymbol' => venues::alias($text_venue),
								'date_created' => time(),
								'user_created' => Yii::app()->user->id,
								'published'	=>	'0',
								'country_id'	=>	$_POST['Notes']['country_id'],
								'state_id'	=>	$_POST['Notes']['city_id'],
								'city_id'	=>	$_POST['Notes']['district_id'],
								'parent_id'	=>	$parent_id,
								'total_visit'	=>	1
						);
						$venue_model->attributes = $venue_create;
						$venue_model->validate();
						if($venue_model->save()){
							$venue_id	=	$venue_model->id;
							$venue->addVenues($venue_model->id);
				
				
				
						}
					}
					//log history
					if($venue_id && Yii::app()->user->id){
						$venue_history	=	new CmsVenuesHistory();
						$venue_history->venue_id	=	$venue_id;
						$venue_history->type		=	CmsVenuesHistory::VISITOR_TYPE_ISU;
						$venue_history->date_created=	time();
						$venue_history->user_id		=	Yii::app()->user->id;
						$venue_history->ip			=	$_SERVER['REMOTE_ADDR'];
						if($venue_history->save()){
							$venues_row	=	CmsVenues::model()->findByPk($venue_id);
							if($venues_row){
								$venues_row->total_visit	=	CmsVenuesHistory::model()->getTotalVisit($venue_id);
								$venues_row->save();
								$venue->updateVenues($venue_id);
							}
						}
					}
				}
				//end process isu 
				
				/*Begin set notify
				 *
				* */
				if($text_venue):
					$criteria=new CDbCriteria;
					$criteria->addCondition('date_created >= :start_date');
					$criteria->addCondition('date_created <= :end_date');
					$criteria->addCondition('venue_id = :venue_id');
					$criteria->addCondition('user_id <> :user_id');
					$criteria->group	=	'user_id';
					$criteria->params = array(
						'user_id'		=>	Yii::app()->user->id,
						'venue_id' 		=>	$venue_id,
						'start_date' 	=>	$model->date,
						'end_date' 		=>	$model->end_date
					);
					$users_checkin_isu = CmsVenuesHistory::model()->findAll($criteria);
										
					
					if(isset($users_checkin_isu[0])) {
						$notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_ISU_MATCH_LOCATION));
						
						
						
						foreach($users_checkin_isu AS $row):
							$notification_data = array(
									'params' => array('{number}'=> 1, '{location}' => $text_venue),
									'message' => 'You checked in at {location}? There\'ve been {number} new ISU that might be relevant to you. Check them now to see if you\'re the person they\'re looking for!'
				
							);
							$data = array(
									'userid'=> 1,
									'ownerid'=> $row->user_id,
									'owner_type'=>'user',
									'notification_type'=>$notifiType->id,
									'notification_data'=>addslashes(json_encode($notification_data)),
									'timestamp'=>time(),
									'last_read'=>0,
							);
							XNotifications::model()->saveNotifications($data);
						endforeach;
					}
				endif;
				/*End set notify
				 *
				*/
				
				$model->venue_id = $venue_id;
				$model->save();
				$my_isus =    $model->getMyISU();
				$after_save = $this->renderPartial('partial/detail', array('isu' => $model, 'my_isus' => $my_isus), true);
				echo json_encode(array('status'=>true,'after_save' => $after_save));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
			
		} else {
			$list_country = $country_in_cache->getListCountry();
			if ($list_country) {
				if (!$model->country_id) {
					$current_country = $country_in_cache->getCurrentCountry();
					$model->country_id	=	$current_country['id'];
				}
				//get state list
				$list_state	=	$state_in_cache->getlistStateinCountry($model->country_id);
					
				if($list_state){
					//get city list
					if(!$model->city_id){
						$first_state	=	current($list_state);
						$model->city_id	=	$first_state['id'];
					}
					$list_city	=	$city_in_cache->getlistCityinState($model->city_id);
					
				}else{
					$list_state	=	array();
				}
					
			}
		}
		$this->renderPartial('partial/create_isu', array(
			'model' => $model,
			'list_country' => $country_in_cache->getListCountry(),
			'list_city' => ($list_city ? $list_city : array()),
			'list_state' => ($list_state ? $list_state : array()),
		), false, true);
	}
	
	public function actionUpload($id = null){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
				
			$id = isset($id) ? $id : 0;
			
			//upload photo hotbox
			$params 	= CParams::load();
			Yii::import ( "backend.extensions.plunUploader.upload");
			$uploader = new upload($params->params->uploads->upload_method);
			
				
			$thumb300x0 = $params->params->uploads->isu->thumb300x0;
			$detail600x0 = $params->params->uploads->isu->detail600x0;
			$origin 	= $params->params->uploads->isu->origin;
			$path_folder = $uploader->setPath ( $params->params->uploads->isu->path, false );
				
				
			
			$thumb300x0_folder = $uploader->setPath ( $thumb300x0->p , false);
			$detail600x0_folder = $uploader->setPath ( $detail600x0->p , false);
			$origin_folder = $uploader->setPath ( $origin->p , false);
				
			$uploader->allowedExtensions = array (
					'jpg',
					'jpeg',
					'png'
			);
			$uploader->inputName	=	'qqfile';
			$uploader->sizeLimit = $params->params->uploads->isu->size;//maximum file size in bytes
				
			$result = $uploader->upload ( $origin_folder );
			
			$origin_uploaded_path = $origin_folder . DS . $uploader->getUploadName ();
			//load images resource
			
			if ($result ['success']) {
					
				$uploader->loadImageResource($origin_uploaded_path);
				// begin resize and crop for thumbnail
				$uploader->resizeImage($thumb300x0->w, $thumb300x0->h);
				$uploader->crop($thumb300x0->w, $thumb300x0->h, 'top');
				$uploader->saveImg($thumb300x0_folder . DS . $uploader->getUploadName ());
			
				// resize for thumbnail (width 200)
				$uploader->resizeImage($detail600x0->w, $detail600x0->w, true);
				$uploader->saveImg($detail600x0_folder . DS . $uploader->getUploadName ());
					
				$uploader->detroyResource();
				
				$result['filename'] = $uploader->getUploadName();
				$result['filepath'] = ltrim($path_folder,'\/');
				$result['host'] = Yii::app()->createAbsoluteUrl('/');
				header("Content-Type: text/plain");
				$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				echo $result;
			} else
				echo json_encode($result);

			
			Yii::app()->end();			
			//----------------------------
		}
	
	}
	
	
	public function actionRemoveImg(){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
			if(isset($_POST['name']) && isset($_POST['path'])){
				$this->deletePermanentlyPhoto($_POST['name'], $_POST['path']);
				Yii::app()->end();
			}
			
		}
		
	}
	
	public function deletePermanentlyPhoto($name, $path){
		if(isset($name) && isset($path)){
	
			if(file_exists($path.DS.'thumb300x0'.DS.$name)){
				@unlink($path.DS.'thumb300x0'.DS.$name);
			}
			if(file_exists($path.DS.'detail600x0'.DS.$name)){
				@unlink($path.DS.'detail600x0'.DS.$name);
			}
			if(file_exists($path.DS.'origin'.DS.$name)){
				@unlink($path.DS.'origin'.DS.$name);
			}
			
		}
	
	}
	
	public function actionSend($type = false, $isu_id = false)
	{
		if (Yii::app()->request->isAjaxRequest){
			$post = Yii::app()->request->getPost('MessageForm');
			$model = new MessageForm();
			$model->subject = substr($post['body'], 0, 100);
			
			if($type == 'forward' && isset($isu_id)){
				$detail = new Notes();
				$detail = $detail->findByPk($isu_id); 
				$link = CHtml::link($detail->title, Yii::app()->createUrl('isu/load', array('id' => $detail->id)))."\n";
				$post['body'] = $link."\n".$post['body'];
			}
			$model->attributes = $post;
			$model->validate();
			if(!$model->hasErrors()){
				$model->send();
				echo json_encode(array('status'=>true));
				Yii::app()->end();
			}else{
				echo json_encode($model->errors);
				Yii::app()->end();
			}
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Notes::model()->findByPk($id);
		if(isset($model)){
			if($model->status == Notes::STATUS_PENDING){
				if($model->user_id != Yii::app()->user->id){
					throw new CHttpException(404,'The requested page does not exist.');
				}
			
			}
		}
		
		if($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
    
    public function actionGetCityList() {
    	$list_city = array();
    	try {
    		$country_id = Yii::app()->request->getParam('country_id', 0);
    		$stage = new StateonCache();
    		$stages = $stage->getlistStateinCountry($country_id);
    		$list_city = $stages; 
    		/* $city_in_cache = new CityonCache();
    		$country_id = Yii::app()->request->getParam('country_id', 0);
    		$list_city = $city_in_cache->getlistCityinCountry($country_id); */
    		if (!$list_city) {
    			$list_city = array();
    		}
    	} catch (Exception $e) {
    
    	}
    
    	$this->renderPartial('partial/list_city', array(
    			'list_city' => $list_city
    	));
    }
    public function actionGetDistrictList() {
    	$list_district = array();
    	try {
    		$city_id = Yii::app()->request->getParam('city_id', 0);
    		
    		$district = new CityonCache();
    		$districts = $district->getlistCityinState($city_id);
    		$list_district = $districts; 
    		if (!$list_district) {
    			$list_district = array();
    		}
    	} catch (Exception $e) {
    
    	}
    
    	$this->renderPartial('partial/list_district', array(
    			'list_district' => $list_district
    	));
    }
    
    
}