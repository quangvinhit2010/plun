<?php

/**
 * This is the model class for table "sys_photo".
 *
 * The followings are the available columns in table 'sys_photo':
 * @property integer $id
 * @property integer $album_id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $name
 * @property string $path
 * @property integer $status
 * @property integer $order
 * @property integer $created
 * @property integer $updated
 */
class Photo extends CActiveRecord
{
	const PUBLIC_PHOTO		=	1;
	const PRAVITE_PHOTO		=	2;
	const VAULT_PHOTO		=	3;
	
	public $request_status	=	null;
	public $total	=	null;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_id, user_id, status, order, created, updated', 'numerical', 'integerOnly'=>true),
			array('title, slug, name, path', 'length', 'max'=>500),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, album_id, user_id, title, slug, description, name, path, status, order, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'album' => array(self::BELONGS_TO, 'Album', 'album_id'),
			'request_photo'	=>	array(self::HAS_MANY, 'SysPhotoRequest', 'photo_id'),
			'user'  => array(self::BELONGS_TO, 'Member', 'user_id'),
			'photo_private' => array(self::HAS_ONE, 'SysPhotoPrivate', 'photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'album_id' => 'Album',
			'user_id' => 'User',
			'title' => 'Title',
			'slug' => 'Slug',
			'description' => 'Description',
			'name' => 'Name',
			'path' => 'Path',
			'status' => 'Status',
			'order' => 'Order',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',1);
		$criteria->compare('album_id',$this->album_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('status',1);
		$criteria->compare('order',$this->order);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'id DESC',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Photo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getImageWaterMark($return_url = false, $htmlOptions = array()) {
		$params = CParams::load ();
		$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/thumbwatermark/{$this->name}";
	
		if(!$return_url){
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getImageThumbnail($return_url = false, $htmlOptions = array()) {
		$params = CParams::load ();
		/*
		if(empty($this->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$this->path .'/thumb275x275/'. $this->name)){
			$name = Yii::app()->createAbsoluteUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
		} else {
			$name = Yii::app()->createAbsoluteUrl($this->img_webroot_url . "/{$this->path}/thumb275x275/". $this->name);
		}
		*/
		$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/thumb275x275/{$this->name}";
		
		if(!$return_url){
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getImageThumbnail425x320($return_url = false, $htmlOptions = array()){
		$params = CParams::load ();
	
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/detail425x320/{$this->name}";
	
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getImageThumbnail160x160($return_url = false, $htmlOptions = array()){
		$params = CParams::load ();
		
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/thumb160x160/{$this->name}";
		
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}		
	}
	public function getImageSmallThumbnail($htmlOptions = array()) {
		$params = CParams::load ();
		
		/*
		if(empty($this->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$this->path .'/detailw200/'. $this->name)){
			$name = Yii::app()->createUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
		} else {
			$name = Yii::app()->createAbsoluteUrl($this->path .'/detailw200/'. $this->name);
		}
		*/
		$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/detailw200/{$this->name}";
	
		return CHtml::image($name, $this->title, $htmlOptions);
	}	
	public function getImageLarge($return_url = false, $htmlOptions = array()) {
		$params = CParams::load ();
		
		/*
		if(empty($this->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$this->path .'/detail1600x900/'. $this->name)){
			$name = Yii::app()->createUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
		} else {
			$name = Yii::app()->createAbsoluteUrl($this->path .'/detail1600x900/'. $this->name);
		}
		*/
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/detail1600x900/{$this->name}";
		
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getImageMedium($return_url = false, $htmlOptions = array()) {
		$params = CParams::load ();
		/*
		if(empty($this->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$this->path .'/detail1600x900/'. $this->name)){
			$name = Yii::app()->createUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
		} else {
			$name = Yii::app()->createAbsoluteUrl($this->path .'/detail1600x900/'. $this->name);
		}
		*/
		$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
		
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/detail1600x900/{$this->name}";
		if(!$return_url){
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}	
	public function getImageOrigin($return_url = false, $htmlOptions = array()) {
		/*
		if(empty($this->name) || !file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS .$this->path .'/origin/'. $this->name)){
			$name = Yii::app()->createUrl(Yii::app()->homeUrl .'/public/images/no-image.jpg');
		} else {
			$name = Yii::app()->createAbsoluteUrl($this->path .'/origin/'. $this->name);
		}
		*/
		
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/origin/{$this->name}";		
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getPhotoList($user_id, $request_user_id, $type = Photo::PUBLIC_PHOTO, $limit = 10, $offset = 0){
		$CDbCriteria = new CDbCriteria;
		$CDbCriteria->select	=	'pr.status AS request_status, p.*';
		$CDbCriteria->distinct = true;
		$CDbCriteria->alias	=	'p';
		$CDbCriteria->join	=	"LEFT JOIN sys_photo_request pr ON (p.id = pr.photo_id 
						AND pr.request_user_id ={$request_user_id})";
		$CDbCriteria->addCondition("p.user_id = {$user_id}");
		$CDbCriteria->addCondition("p.type = {$type}");
		$CDbCriteria->addCondition("p.status = 1");
		$CDbCriteria->offset	=	$offset;
		$CDbCriteria->limit	=	$limit;
		$CDbCriteria->order	=	'p.id DESC';
		
		$photos	=	$this->findAll($CDbCriteria);
		
		return $photos;
	}
	public function countPhotoList($user_id, $request_user_id, $type = Photo::PUBLIC_PHOTO){
		$CDbCriteria = new CDbCriteria;
		$CDbCriteria->select	=	'count(p.id) as total';
		$CDbCriteria->distinct = true;
		$CDbCriteria->alias	=	'p';
		$CDbCriteria->join	=	"LEFT JOIN sys_photo_request pr ON (p.id = pr.photo_id
		AND pr.request_user_id ={$request_user_id})";
		$CDbCriteria->addCondition("p.user_id = {$user_id}");
		$CDbCriteria->addCondition("p.type = {$type}");
		$CDbCriteria->addCondition("p.status = 1");
		$row	=	$this->find($CDbCriteria);
		
		return $row->total;	
	}
	
	public function getPhotoByType($user_id, $page = null, $type = null, $limit = 5){
		
		$page = (isset($page)) ? $page : 1;
		$criteria = new CDbCriteria;
		
		$criteria->addCondition('t.user_id = :user_id');
		$criteria->addCondition('t.status = 1');
		$params = array();
		if($type != null){
			$criteria->addCondition('t.type = :type');
			$params = array_merge($params, array(':type' => $type));
		}
		$params = array_merge($params, array(':user_id' => $user_id));
		$criteria->params = $params;
		$criteria->order = 't.id DESC';
		
		$total = Photo::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = $limit;
		$pages->applyLimit($criteria);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$photo = Photo::model()->findAll($criteria);
		return array('data' => $photo, 'pages' => $pages, 'next_page' => $next_page, 'type' => $type);
	}
	
	public function getPhotoByTypeExtend($user_id, $page = null, $type = null, $limit = 5, $offset = 0){
	
		$page = (isset($page)) ? $page : 1;
		$criteria = new CDbCriteria;
	
		$criteria->addCondition('t.user_id = :user_id');
		$criteria->addCondition('t.status = 1');
		$params = array();
		if($type != null){
			$criteria->addCondition('t.type = :type');
			$params = array_merge($params, array(':type' => $type));
		}
		$params = array_merge($params, array(':user_id' => $user_id));
		$criteria->params = $params;
		$criteria->order = 't.id DESC';
	
		$total = Photo::model()->count($criteria);
		
		$criteria->limit = $limit;
		$criteria->offset = ($page-1)*$criteria->limit + $offset;
		
		$photo = Photo::model()->findAll($criteria);
		
		$next_page = ($total > $criteria->offset + $criteria->limit) ? $page + 1 : 'end' ;
		
		return array('data' => $photo, 'next_page' => $next_page, 'type' => $type);
	}
	
	public function deletePermanentlyPhoto(){
		if(isset($this->name) && isset($this->path)){
			
			if(file_exists($this->path.DS.'thumb275x275'.DS.$this->name)){
				@unlink($this->path.DS.'thumb275x275'.DS.$this->name);
			}
			if(file_exists($this->path.DS.'detail1600x900'.DS.$this->name)){
				@unlink($this->path.DS.'detail1600x900'.DS.$this->name);
			}
			if(file_exists($this->path.DS.'detailw200'.DS.$this->name)){
				@unlink($this->path.DS.'detailw200'.DS.$this->name);
			}
			if(file_exists($this->path.DS.'origin'.DS.$this->name)){
				@unlink($this->path.DS.'origin'.DS.$this->name);
			}
			
			$this->delete ();
			
		}
		
	}
	
	public function isAccept(){
		if(!empty($this->id) && !empty(Yii::app()->user->id)){
			$is = SysPhotoRequest::model()->count('photo_id = :photo_id AND request_user_id = :request_user_id AND status = :status', 
					array(':photo_id'=>$this->id, ':request_user_id'=>Yii::app()->user->id, ':status' => SysPhotoRequest::REQUEST_ACCEPTED)
			);
			if($is){
				return true;
			}
			return false;			
		}
		
	}
	public function getStatus(){
		if(!empty($this->id) && !empty(Yii::app()->user->id)){
			$status = SysPhotoRequest::model()->find('photo_id = :photo_id AND request_user_id = :request_user_id', 
					array(':photo_id'=>$this->id, ':request_user_id'=>Yii::app()->user->id)
			);
			if(isset($status)){
				return $status->status;
			}	else {
				return false;
			}		
		}
		
	}
	public function resendRequest($photo_id, $userid){
		$request = SysPhotoRequest::model()->find('photo_id = :photo_id AND request_user_id = :request_user_id AND photo_user_id = :photo_user_id',
				array(':photo_id' => $photo_id, ':request_user_id' => Yii::app()->user->id, ':photo_user_id' => $userid)
		);
		//update request
		if(isset($request->id)){
			$params = CParams::load ();
			$resend_request_limit = $params->params->uploads->photo->resend_request_limit;
			
			if($request->request_number < $resend_request_limit){
				$request->request_number	+=	1;
				$request->date_request	=	time();
				$request->status	=	SysPhotoRequest::REQUEST_PENDING;
				$request->is_read	=	SysPhotoRequest::IS_UNREAD;				
				if($request->save()){
					return '1';
				}else{
					return '0';
				}
			}else{
				return '2';
			}
		}else{
			return '0';
		}
		
	}
	public function isAcceptAll(){
		if(!empty($this->user_id) && !empty(Yii::app()->user->id)){
			$is = SysPhotoAcceptall::model()->count('user_id = :user_id AND request_user_id = :request_user_id', 
					array(':user_id'=>$this->user_id, ':request_user_id'=>Yii::app()->user->id)
			);
			if($is){
				return true;
			}
			return false;			
		}
	}
	
	public function getType(){
		if($this->type == self::PUBLIC_PHOTO){
			return 'Public Photo';
			
		} elseif($this->type == self::PRAVITE_PHOTO){
			return 'Private Photo';
		} elseif($this->type == self::VAULT_PHOTO){
			return 'Vault Photo';
		}
		
	}
	
	public function getAdminImageThumbnail($return_url, $htmlOptions = array()) {
		
		if(empty($this->name) || !file_exists('../'. $this->path .'/thumb275x275/'. $this->name)){
			$name = '/public/images/no-image.jpg';
		} else {
			$name = $this->path .'/thumb275x275/'. $this->name;
		}
		
// 		$name = str_replace('admin/', '', $name);
		$name = Yii::app()->request->getHostInfo().DS.$name;
		if(!$return_url){
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	
	public function afterSave(){
	    if($this->status == 0){
	        $cri = new CDbCriteria();
	        $cri->addCondition("user_id = :user_id AND action = :action AND object_id = :object_id");
	        $cri->params = array(':user_id'=>Yii::app()->user->id, ':action'=>Activity::LOG_PHOTO_UPLOAD, ':object_id'=> $this->id );
	        $logExist = Activity::model()->find($cri);
	        if(!empty($logExist)){
	            $logExist->status = 0;
	            $logExist->save();
	        }
	        /** update index search **/
	        
	        if(!Yii::app()->user->isGuest){
	            $user = Member::model()->findByAttributes(array('avatar'=>$this->id));
	            if(!empty($user)){
    	            $user->avatar = NULL;
    	            $user->save();
        	        $Elasticsearch = new Elasticsearch();
        	        $Elasticsearch->updateSearchIndexUser($user->id);
	            }
	        }
	    }
	    return parent::afterSave();
	}
	public function mapPartial($type = FALSE) {
		$type = ($type) ? $type : $this->type;
		$typeRenderPartial = array(Photo::PUBLIC_PHOTO => 'public', Photo::VAULT_PHOTO => 'vault', Photo::PRAVITE_PHOTO => 'private');
		return $typeRenderPartial[$type];
	}
}
