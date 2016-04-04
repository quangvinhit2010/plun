<?php

/**
 * This is the model class for table "cms_venues".
 *
 * The followings are the available columns in table 'cms_venues':
 * @property string $id
 * @property string $title
 * @property string $title_nosymbol
 * @property string $description
 * @property string $thumbnail
 * @property integer $date_created
 * @property integer $published
 * @property integer $date_modified
 * @property integer $user_created
 * @property double $longitude
 * @property double $latitude
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $parent_id
 * @property integer $total_visit
 * 
 */
class CmsVenues extends CActiveRecord
{
	const STATUS_PUBLISHED	=	1;
	const STATUS_UNPUBLISHED	=	0;
	
	const TOP_VENUES	=	1;
	const NOTOP_VENUES	=	0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_venues';
	}
	public function behaviors(){
		return array(
				'SlugBehavior' => array(
						'class' => 'backend.components.SlugBehavior',
						'slug_col' => 'slug',
						'title_col' => 'title',
						'overwrite' => true
				),
		);
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_created, published, date_modified, user_created, country_id, state_id, city_id, district_id, top_venue, parent_id, total_visit, cat_id, locality_id', 'numerical', 'integerOnly'=>true),
			array('title, title_nosymbol, longitude, latitude, address, tags', 'length', 'max'=>200),
			array('thumbnail, thumbnail_path', 'length', 'max'=>150),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, title_nosymbol, description, tags, thumbnail, thumbnail_path, date_created, published, top_venue, date_modified, user_created, longitude, latitude, country_id, state_id, city_id, district_id, parent_id, total_visit, cat_id, locality_id, address', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'CmsVenuesCategory', 'cat_id')
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'title_nosymbol' => 'Title Nosymbol',
			'tags' => 'Tags',
			'address' => 'Address',
			'description' => 'Description',
			'thumbnail' => 'Thumbnail',
			'date_created' => 'Date Created',
			'published' => 'Published',
			'date_modified' => 'Date Modified',
			'user_created' => 'User Created',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'country_id' => 'Country',
			'state_id' => 'State',
			'city_id' => 'City',
			'district_id' => 'District',
			'total_visit'	=>	'Total Visit',
			'cat_id'	=>	'Category'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('title_nosymbol',$this->title_nosymbol,true);
		$criteria->compare('title_nosymbol',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('published',$this->published);
		$criteria->compare('date_modified',$this->date_modified);
		$criteria->compare('user_created',$this->user_created);
		$criteria->compare('address',$this->address);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('cat_id',$this->cat_id);
		$criteria->compare('district_id',$this->district_id);
		$criteria->compare('total_visit',$this->total_visit);
		
		$criteria->order	=	'id DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsVenues the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
		$name = "http://{$params->params->img_webroot_url}/{$this->thumbnail_path}/detail200x200/{$this->thumbnail}";
		
		if(!$return_url){
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}	
}
