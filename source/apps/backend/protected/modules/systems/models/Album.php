<?php

/**
 * This is the model class for table "sys_album".
 *
 * The followings are the available columns in table 'sys_album':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $photo_id
 * @property integer $type
 * @property integer $order
 * @property integer $created
 * @property integer $updated
 * @property integer $status
 */
class Album extends CActiveRecord
{
	
	public $_default_album_data = array(
			'public' => array('title' => 'Public Photos', 'description' => 'Everyone can see your photos', 'order' => 1),
			'pravite' => array('title' => 'Pravite Photos', 'description' => 'Only you can see your photos', 'order' => 2),
			'vault' => array('title' => 'Vault Photos', 'description' => 'Someone can see your photos', 'order' => 3),
			
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_album';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, photo_id, type, order, created, updated, status', 'numerical', 'integerOnly'=>true),
			array('title, slug', 'length', 'max'=>500),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, title, slug, description, photo_id, type, order, created, updated, status', 'safe', 'on'=>'search'),
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
			'photo'   	=> array(self::HAS_MANY, 'Photo', 'album_id'),
			'album_count'   	=> array(self::STAT, 'Photo', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'title' => 'Title',
			'slug' => 'Slug',
			'description' => 'Description',
			'photo_id' => 'Photo',
			'type' => 'Type',
			'order' => 'Order',
			'created' => 'Created',
			'updated' => 'Updated',
			'status' => 'Status',
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
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('photo_id',$this->photo_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('order',$this->order);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Album the static model class
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
	
	public function createDefaultAlbum($user_id){
		if($this->_default_album_data){
			foreach ($this->_default_album_data as $value){
				$model = new Album();
				$model->attributes = $value;
				$model->user_id = $user_id;
				$model->created = time();
				$model->save();
			}
		}
	}
}
