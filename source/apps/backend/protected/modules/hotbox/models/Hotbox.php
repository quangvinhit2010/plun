<?php

/**
 * This is the model class for table "sys_hotbox".
 *
 * The followings are the available columns in table 'sys_hotbox':
 * @property integer $id
 * @property integer $type
 * @property integer $type_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $body
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $author_id
 * @property integer $thumbnail_id
 * @property integer $public_time
 * @property integer $status
 * @property integer $view
 * @property integer $created
 * @property integer $modify
 */
class Hotbox extends CActiveRecord
{
	
	const ACTIVE = 1;
	const PENDING = 2;
	const READ_ONLY = 3;
	const EVENT = 1;
	const PHOTO = 2;
	public $tmp_images;
	public $tmp_event_title;
	public $tmp_event_start_date;
	public $tmp_event_end_date;
	public $tmp_event_is_always;
	public $country_id;
	public $state_id;
	public $city_id;
	public $district_id;
	public $start;
	public $end;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_hotbox';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, type_id, author_id, thumbnail_id, public_time, status, view, created, modify', 'numerical', 'integerOnly'=>true),
			array('title, slug, description, body, meta_description, meta_keywords', 'safe'),
			//array('tmp_event_end_date', 'validateDateEvent'),
			array('title, body', 'required'),
			array('end', 'validateDateEvent'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, type_id, title, slug, description, body, meta_description, meta_keywords, author_id, thumbnail_id, public_time, status, view, created, modify', 'safe', 'on'=>'search'),
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
			'images' 				=> array(self::HAS_MANY, 'HotboxPhoto', 'hotbox_id'),
			'events' 				=> array(self::HAS_ONE, 'HotboxEvent', 'hotbox_id'),
			'author' 				=> array(self::BELONGS_TO, 'Member', 'author_id'),
			'feature' 				=> array(self::HAS_ONE, 'HotboxFeature', 'hotbox_id'),
			'stats' 				=> array(self::HAS_ONE, 'HotboxStats', 'hotbox_id'),
			'like' 					=> array(self::HAS_ONE, 'Like', 'item_id'),
			'comment' 				=> array(self::HAS_MANY, 'Comment', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'type_id' => 'Type',
			'title' => Lang::t('hotbox', 'Title'),
			'slug' => 'Slug',
			'description' => 'Description',
			'body' => Lang::t('hotbox', 'Body'),
			'meta_description' => 'Meta Description',
			'meta_keywords' => 'Meta Keywords',
			'author_id' => 'Author',
			'thumbnail_id' => 'Thumbnail',
			'public_time' => 'Public Time',
			'status' => 'Status',
			'view' => 'View',
			'created' => 'Created',
			'modify' => 'Modify',
		);
	}
	public function afterDelete(){
		if($this->id){
			$hotbox_stat = HotboxStats::model()->findByPk($this->id);
			if(isset($hotbox_stat)){
				$hotbox_stat->delete();
			}
		}
		if($this->type == Hotbox::EVENT) {
			$this->events->delete();
		}
		return parent::afterDelete();
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
		$criteria->compare('type',$this->type);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('thumbnail_id',$this->thumbnail_id);
		$criteria->compare('public_time',$this->public_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('view',$this->view);
		$criteria->compare('created',$this->created);
		$criteria->compare('modify',$this->modify);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hotbox the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	
	public function afterSave() {
		if(isset($this->tmp_images) && count($this->tmp_images) > 0 && $this->tmp_images != ""){
			foreach ($this->tmp_images as $image_id)
			{
				if(!empty($image_id)) {
					$model = HotboxPhoto::model()->findByAttributes(array('id' => $image_id));
					if(isset($model)){
						$model->hotbox_id = $this->id;
						$model->update();
					}
				}
			}
		}
		if($this->type == Hotbox::EVENT){
			if($this->isNewRecord){
				$model = new HotboxEvent();
				$model->hotbox_id = $this->id;
				$model->title = $this->title;
				$model->start = strtotime($this->start);
				$model->end = strtotime($this->end);
				//$model->thumbnail = $this->thumbnail_id;
				$model->status = HotboxEvent::ACTIVE;
				$model->country_id = $this->country_id;
				$model->state_id = ($this->state_id) ? $this->state_id:NULL;
				$model->city_id = ($this->city_id) ? $this->city_id:NULL;
				$model->district_id = ($this->district_id) ? $this->district_id:NULL;
				$model->save();
			} else {
				if(isset($this->country_id) && isset($this->start) && isset($this->end)){
					$model = HotboxEvent::model()->findByAttributes(array('hotbox_id' => $this->id));
					if(isset($model)){
						$model->hotbox_id = $this->id;
						$model->title = $this->tmp_event_title;
						$model->start = strtotime($this->start);
						$model->end = strtotime($this->end);
						//$model->thumbnail = $this->thumbnail_id;
						$model->status = HotboxEvent::ACTIVE;
						$model->country_id = $this->country_id;
						$model->state_id = ($this->state_id) ? $this->state_id:NULL;
						$model->city_id = ($this->city_id) ? $this->city_id:NULL;
						$model->district_id = ($this->district_id) ? $this->district_id:NULL;
						$model->status = HotboxEvent::ACTIVE;
						$model->update();
						
					} else {
						$model = new HotboxEvent();
						$model->hotbox_id = $this->id;
						$model->title = $this->tmp_event_title;
						$model->start = strtotime($this->start);
						$model->end = strtotime($this->end);
						//$model->thumbnail = $this->thumbnail_id;
						$model->status = HotboxEvent::ACTIVE;
						$model->country_id = $this->country_id;
						$model->state_id = ($this->state_id) ? $this->state_id:NULL;
						$model->city_id = ($this->city_id) ? $this->city_id:NULL;
						$model->district_id = ($this->district_id) ? $this->district_id:NULL;
						$model->status = HotboxEvent::ACTIVE;
						$model->save();
					}
					
				}
	
			}
				
		}
		
		$hotbox_stat = HotboxStats::model()->findByPk($this->id);
		if(empty($hotbox_stat)){
			$model = new HotboxStats();
			$model->hotbox_id = $this->id;
			$model->like_count = 0;
			$model->comment_count = 0;
			$model->save();
		}
	
		return parent::afterSave();
	}
	
	public function getType(){
		if($this->type == self::EVENT){
			return 'event';
		} elseif ($this->type == self::PHOTO){
			return 'photo';
		} else {
			return null;
		}
		
	}
	
	public static function Type2Id($type){
		if($type == 'photo'){
			return self::PHOTO;
		} elseif($type == 'event'){
			return self::EVENT;
		} elseif($type == 'me'){
			return 'me';
		} else {
			return NULL;
		}
	}
	
	public function getAuthorElement(){
		if($this->author_id == Yii::app()->user->id){
			return 'hotbox-my';
		} else {
			return null;
		}
	}
	
	public function getFeature(){
		if(isset($hotbox->feature) && $hotbox->feature->hotbox_id == $hotbox->id){
			return 'item-0';
		} else {
			return null;
		}   
	}
	
	public function getMyHotbox(){
		$criteria=new CDbCriteria;
		$criteria->addCondition('public_time <= :time AND author_id = :author_id');
		$criteria->params = array(':time' => time(), ':author_id' => Yii::app()->user->id);
		$criteria->order = 't.id DESC';
		$criteria->limit = Yii::app()->params->page_limit['hotbox_limit'];
		return Hotbox::model()->findAll($criteria);
	}
	
	public function getLikeCount(){
		if(isset($this->stats->like_count)){
			if($this->stats->like_count > 0){
				return $this->stats->like_count;
			}
		}
		return 0;
	}
	
	public function getCommentCount(){
		if(isset($this->stats->comment_count)){
			if($this->stats->comment_count > 0){
				return $this->stats->comment_count;
			}
		}
		return 0;
	}
		
	public function getStatus(){
		if(isset($this->status)){
			if($this->status == self::ACTIVE){
				return CHtml::link('Public', array('//hotbox/hotbox/status', 'status' => self::PENDING, 'id' => $this->id));
			} elseif ($this->status == self::PENDING) {
				return CHtml::link('Pending', array('//hotbox/hotbox/status', 'status' => self::ACTIVE, 'id' => $this->id));
				//return 'Pending';
			}
		}
	
	}
	
	public function validateDateEvent(){
		if(strtotime($this->start) > strtotime($this->end)){
			$this->addError('end', Lang::t('hotbox', 'End day must be greater than start day'));
		}
	}
	
	public function getNewHotbox(){
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('public_time <= :time AND status = :status OR (author_id = :author_id AND status != :status)');
		$criteria->params = array(':time' => time(), ':status' => Hotbox::ACTIVE, ':author_id' => Yii::app()->user->id);
		$criteria->order = 't.id DESC';
		$criteria->limit = Yii::app()->params->page_limit['hotbox_limit'];
		return Hotbox::model()->findAll($criteria);
	}
	
	public function createUrl($absolute = false){
		if($absolute){
			return Yii::app()->createAbsoluteUrl('//hotbox/load', array('id' => $this->id, 'slug' => $this->slug));
		}
		return Yii::app()->createUrl('//hotbox/load', array('id' => $this->id, 'slug' => $this->slug));
	}
	public function createShareUrl($absolute = false){
		if($absolute){
			return Yii::app()->createAbsoluteUrl('//site/hotbox', array('id' => $this->id, 'slug' => $this->slug));
		}
		return Yii::app()->createUrl('//site/hotbox', array('id' => $this->id, 'slug' => $this->slug));
	}
	public function getImage($forceGetDetail = false) {
		$image = NULL;
		if($this->images) {
			foreach($this->images as $thumb_key => $image) {
				if(empty($this->feature->hotbox_id) && !$forceGetDetail) {
					if($thumb_key < 1)
						$image = $image->getImageThumb(array(), true);
				} else {
					if($thumb_key < 1)
						$image = $image->getImageDetail(array(), true);
				}
			}
		}
		return $image;
	}
}
