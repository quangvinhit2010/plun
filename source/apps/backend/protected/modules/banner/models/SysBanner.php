<?php

/**
 * This is the model class for table "sys_banner".
 *
 * The followings are the available columns in table 'sys_banner':
 * @property integer $id
 * @property string $type
 * @property string $full_path
 * @property string $file_name
 * @property string $url
 * @property integer $created
 * @property integer $status
 */
class SysBanner extends CActiveRecord
{
	const TYPE_W_160 = 'W_160';
	const TYPE_W_300 = 'W_300';
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	const PATH = "/uploads/banner/";
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_banner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, full_path, file_name, url, created, status', 'required'),
			array('created, status', 'numerical', 'integerOnly'=>true),
			array('full_path, url', 'length', 'max'=>255),
			array('file_name', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, full_path, file_name, url, created, status', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'full_path' => 'Path',
			'file_name' => 'File Name',
			'url' => 'Url',
			'created' => 'Created',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('full_path',$this->full_path,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysBanner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getImage() {
		return '<img src="'.$this->full_path.'" />';
	}
	public function mapStatus() {
		$mapStatus = array(
				SysBanner::STATUS_ENABLED => 'ENABLED',
				SysBanner::STATUS_DISABLED => 'DISABLED',
		);
		if($this->status === NULL) {
			return $mapStatus;
		} else {
			return $mapStatus[$this->status];
		}
	}
}
