<?php

/**
 * This is the model class for table "cms_venues_category".
 *
 * The followings are the available columns in table 'cms_venues_category':
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $thumbnail
 * @property string $thumbnail_path
 * @property integer $date_created
 * @property integer $date_modified
 */
class CmsVenuesCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_venues_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_created, date_modified', 'numerical', 'integerOnly'=>true),
			array('title, slug', 'length', 'max'=>200),
			array('thumbnail, thumbnail_path', 'length', 'max'=>150),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, slug, description, thumbnail, thumbnail_path, date_created, date_modified', 'safe', 'on'=>'search'),
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
		);
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'slug' => 'Slug',
			'description' => 'Description',
			'thumbnail' => 'Thumbnail',
			'thumbnail_path' => 'Thumbnail Path',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
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
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('thumbnail_path',$this->thumbnail_path,true);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('date_modified',$this->date_modified);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsVenuesCategory the static model class
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
	public function getList(){
		$data	=	$this->findAll();
		$result	=	array();
		foreach($data AS $row){
			$result[$row->id]	=	$row->title;
		}
		return $result;
	}	
}
