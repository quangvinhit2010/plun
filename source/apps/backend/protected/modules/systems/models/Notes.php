<?php

/**
 * This is the model class for table "sys_notes".
 *
 * The followings are the available columns in table 'sys_notes':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $location
 * @property integer $country_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $date
 * @property integer $end_date
 * @property string $body
 * @property string $image
 * @property sting $image_path
 * @property integer $image_in_body
 * @property integer $privacy_id
 * @property integer $status
 * @property integer $modify
 * @property integer $created
 */
class Notes extends CActiveRecord
{
	
	const FIRST_PAGE = 1;
	const STATUS_ACTIVE = 1;
	const STATUS_PENDING = 2;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_notes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, country_id, date, end_date, body', 'required',  'message' => Lang::t('yii', '{attribute} cannot be blank.')),
			array('user_id, country_id, city_id, district_id, image_in_body, privacy_id, status, modify, created, venue_id', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>500),
			array('title, slug, location, body, image, image_path', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('end_date', 'validateDateEvent'),
			array('id, user_id, title, slug, location, country_id, city_id, district_id, date, end_date, body, image, image_path, image_in_body, privacy_id, status, modify, created, venue_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Member', 'user_id'),
			'venue' => array(self::BELONGS_TO, 'CmsVenues', 'venue_id'),
		);
	}
	
	public function validateDateEvent(){
		if($this->date > $this->end_date){
			$this->addError('end_date', Lang::t('hotbox', 'End day must be greater than start day'));
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'title' => Lang::t('isu', 'Title'), 
			'location' => Lang::t('isu', 'Location'), 
			'country_id' => Lang::t('isu', 'Country'),
			'city_id' => Lang::t('isu', 'City'),
			'date' => Lang::t('isu', 'Date'),
			'end_date' => Lang::t('isu', 'End Date'),
			'body' => Lang::t('isu', 'Content'),
			'image' => 'Image',
			'image_path' => 'Image Path',
			'image_in_body' => 'Use thumbnail',
			'privacy_id' => 'Privacy',
			'status' => 'Status',
			'modify' => 'Modify',
			'created' => 'Created',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('date',$this->date);
		$criteria->compare('end_date',$this->end_date);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('image_path',$this->image_path,true);
		$criteria->compare('image_in_body',$this->image_in_body);
		$criteria->compare('privacy_id',$this->privacy_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('modify',$this->modify);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * Returns image thumbnail
	 */
	public function getImageThumb($htmlOptions = array(), $sourceOnly = false) {
		$params = CParams::load ();
		
		if(empty($this->image))
			$image = Yii::app()->createUrl(Yii::app()->theme->baseUrl .'/resources/css/images/img-news.jpg');
		else{
			$image = "http://{$params->params->img_webroot_url}/{$this->image_path}/thumb300x0/{$this->image}";
		}
		if($sourceOnly)
			return $image;
		else
			return CHtml::image($image, $this->id, $htmlOptions);
	}
	
	/* public function getISU($offset = 0, $limit = 12, $country_id, $city_id, $id){
		$criteria=new CDbCriteria;
		$criteria->with = array(
				'user' => array(
						'alias'		=> 'u',
						'joinType'=>'INNER JOIN',
						'together'=>true,
				),
		);
		$criteria->addCondition('t.status = 1');
		$criteria->addCondition('t.id != '.$id);
		$criteria->compare('country_id',$country_id);
		$criteria->compare('city_id',$city_id);
		$criteria->order = 't.created DESC';
		return $this->getDataPagging($criteria, $offset, $limit);
	} */
	
	/* function getDataPagging($criteria, $index, $limit){
		$data = array('data' => null, 'total' => 0, 'index' => $index, 'limit' => $limit, 'ismore' => false);
		$data['total'] = $this->count($criteria);
	
		if ($index > 0)
			//$criteria->offset = $index * $limit;
			$criteria->offset = $index;
		$criteria->limit = $limit;
		$data['data'] = $this->findAll($criteria);
		if ((intval($index) + 1) * intval($limit) < $data['total'] && $data['total'] > 0 )
			$data['ismore'] = true;
	
		return $data;
	} */
	
	public function getMyISU($page = self::FIRST_PAGE){
		$criteria=new CDbCriteria;
		$criteria->addCondition('user_id = :user_id');
		$criteria->params = array(':user_id' => Yii::app()->user->id);
		$criteria->order = 't.id DESC';
		return $this->getDataPagging($criteria, $page);
		//$criteria->limit = Yii::app()->params->page_limit['isu_limit'];
		//return Notes::model()->findAll($criteria);
	}
	
	//get page
	function getDataPagging($criteria, $page){
		$total = $this->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['isu_limit'];
		$pages->applyLimit($criteria);
		$pages->setCurrentPage($page);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$return = array('next_page' => $next_page, 'data' => $this->findAll($criteria), 'page' => $pages);
		return $return;
	
	}
	
	public function getISU($country_id, $city_id, $page, $user_id = false){
		$criteria=new CDbCriteria;
		$params = array();
		if($user_id){
			$criteria->addCondition('user_id = :user_id');
			$criteria->params = array(':user_id' => Yii::app()->user->id);
		}
		if(isset($country_id)){ 
			$criteria->compare('country_id',$country_id);
		}
		if(isset($city_id)){
			$criteria->compare('city_id',$city_id);
		}
		$criteria->addCondition('status = :status OR (user_id = :user_id AND status != :status)');
		$criteria->params = CMap::mergeArray($criteria->params, array(':user_id' => Yii::app()->user->id, ':status' => Notes::STATUS_ACTIVE));
		$criteria->order = 't.id DESC';
		return $this->getDataPagging($criteria, $page);
	}
	
	public function getAuthorElement(){
		if($this->user_id == Yii::app()->user->id){
			return 'isu-my';
		} else {
			return null;
		}
	}
	
	public function deletePermanentlyPhoto(){
		if(isset($this->image) && isset($this->path)){
			if(file_exists($this->path.DS.'thumb300x0'.DS.$this->image)){
				@unlink($this->path.DS.'thumb300x0'.DS.$this->image);
			}
			if(file_exists($this->path.DS.'detail600x0'.DS.$this->image)){
				@unlink($this->path.DS.'detail600x0'.DS.$this->image);
			}
			if(file_exists($this->path.DS.'origin'.DS.$this->image)){
				@unlink($this->path.DS.'origin'.DS.$this->image);
			}
		}
	
	}
	
	public function getStatus(){
		if(isset($this->status)){
			if($this->status == self::STATUS_ACTIVE){
				return CHtml::link('Public', array('//systems/notes/status', 'status' => self::STATUS_PENDING, 'id' => $this->id));
			} elseif ($this->status == self::STATUS_PENDING) {
				return CHtml::link('Pending', array('//systems/notes/status', 'status' => self::STATUS_ACTIVE, 'id' => $this->id));
				//return 'Pending';
			}
		}
		
	}
	
	public function behaviors(){
		return array(
				'SlugBehavior' => array(
						'class' => 'backend.components.SlugBehavior',
						'slug_col' => 'slug',
						'title_col' => 'title',
						//'max_slug_chars' => 125,
						'overwrite' => true
				),
		);
	}
	
	protected function afterSave()
	{
		if(isset($this->country_id) && isset($this->city_id) && isset($this->district_id)){
			
			$criteria=new CDbCriteria;
			$criteria->addCondition('current_country_id = :current_country_id');
			$criteria->addCondition('current_state_id = :current_state_id');
			$criteria->addCondition('current_city_id = :current_city_id');
			$criteria->addCondition('updated > :start');
			$criteria->addCondition('updated < :end');
			$criteria->params = array(
					':current_country_id' => $this->country_id, 
					':current_state_id' => $this->city_id, 
					'current_city_id' => $this->district_id,
					'start' => $this->date,
					'end' => $this->end_date
					
			);
			
			$get_profile = UsrProfileLocation::model()->findAll($criteria);
			
			$country_in_cache   =   new CountryonCache();
			$country_info = $country_in_cache->getListCountry();
			
			$state_in_cache	=	new StateonCache();
			$state_info = $state_in_cache->getListState();
			
			$city_in_cache = new CityonCache();
			$city_info = $city_in_cache->getListCity();
			
			$location_display	=	'';
			$state_name   =   !empty($state_info[$this->city_id]['name'])  ?  $state_info[$this->city_id]['name']    :   '' ;
			$country_name   =   !empty($country_info[$this->country_id]['name'])   ?   $country_info[$this->country_id]['name']    :   '';
			$district_name = !empty($city_info[$this->district_id]['name'])   ?   $city_info[$this->district_id]['name']    :   '';
			
			if(!empty($district_name)){
				$location_display	=	"$country_name, $state_name, $district_name";
			} else {
				$location_display	=	"$state_name, $district_name";
			} 
			
			if(!empty($get_profile)){
				foreach ($get_profile as $value){
					
					
					$notifiType = NotificationsTypes::model()->findByAttributes(array('variables'=>XNotifications::SYS_ISU_MATCH_LOCATION));
					$notification_data = array(
		                    'params' => array('{username}'=>Yii::app()->user->name, '{location}' => $location_display, '{isu_id}' => $this->id),
		                    'message' => 'You checked in at {location}? {username} has created a {isu_id} that might be relevant to you. Check it out to see if you\'re the person {username} is looking for.' 
							
		            );
					$data = array(
							'userid'=>Yii::app()->user->id,
							'ownerid'=>$value->user_id,
							'owner_type'=>'user',
							'notification_type'=>$notifiType->id,
							'notification_data'=>addslashes(json_encode($notification_data)),
							'timestamp'=>time(),
							'last_read'=>0,
					);
					XNotifications::model()->saveNotifications($data);
				}
				
			}
			parent::afterSave();
		}
	}
	
	public function getLocation(){
			$country_in_cache   =   new CountryonCache();
			$country_info = $country_in_cache->getListCountry();
				
			$state_in_cache	=	new StateonCache();
			$state_info = $state_in_cache->getListState();
				
			$city_in_cache = new CityonCache();
			$city_info = $city_in_cache->getListCity();
				
			$location_display	=	array();
			if(!empty($state_info[$this->city_id]['name'])){
			    $location_display[] =  $state_info[$this->city_id]['name'];
			}
			if(!empty($country_info[$this->country_id]['name'])){
			    $location_display[] =  $country_info[$this->country_id]['name'];
			}
			if(!empty($city_info[$this->district_id]['name'])){
			    $location_display[] =  $city_info[$this->district_id]['name'];
			}
				
			$return = '';
			if(!empty($location_display)){
				$return	=	implode(', ', $location_display);
			}
			
			return $return;
	}
	
	
	
}
