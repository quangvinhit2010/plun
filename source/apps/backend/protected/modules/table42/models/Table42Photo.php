<?php

/**
 * This is the model class for table "table42_photo".
 *
 * The followings are the available columns in table 'table42_photo':
 * @property string $id
 * @property string $title
 * @property integer $profile_id
 * @property string $name
 * @property string $path
 * @property integer $status
 * @property integer $date_created
 * @property integer $round_id
 */
class Table42Photo extends EventActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profile_id, status, date_created', 'numerical', 'integerOnly'=>true),
			array('title, name, path', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, profile_id, name, path, status, date_created', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'profile_id' => 'User',
			'name' => 'Name',
			'path' => 'Path',
			'status' => 'Status',
			'date_created' => 'Date Created',
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
		$criteria->compare('profile_id',$this->profile_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_created',$this->date_created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42Photo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getImageThumb203x204($return_url = false, $htmlOptions = array()){
		$params = CParams::load ();
	
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/thumb203x204/{$this->name}";
	
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}
	public function getImageThumb768x1024($return_url = false, $htmlOptions = array()){
		$params = CParams::load ();
	
		$name = "http://{$params->params->img_webroot_url}/{$this->path}/thumb768x1024/{$this->name}";
	
		if(!$return_url){
			$htmlOptions['onerror']	=	'$(this).attr(\'src\',\''.'/public/images/no-image.jpg'  .'\');';
			return CHtml::image($name, $this->title, $htmlOptions);
		}else{
			return $name;
		}
	}		
}
