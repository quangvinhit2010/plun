<?php

/**
 * This is the model class for table "sys_hotbox_event".
 *
 * The followings are the available columns in table 'sys_hotbox_event':
 * @property integer $id
 * @property integer $hotbox_id
 * @property string $title
 * @property integer $is_always
 * @property integer $start
 * @property integer $end
 * @property integer $thumbnail
 * @property integer $status
 */
class HotboxEvent extends CActiveRecord
{
	
	const ACTIVE = 1;
	const PENDING = 2;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_hotbox_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hotbox_id, is_always, start, end, thumbnail, status', 'numerical', 'integerOnly'=>true),
			array('title', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hotbox_id, title, is_always, start, end, thumbnail, status', 'safe', 'on'=>'search'),
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
			'hotbox_id' => 'Hotbox ID',
			'title' => 'Title',
			'is_always' => 'Is Always',
			'start' => 'Start',
			'end' => 'End',
			'thumbnail' => 'Thumbnail',
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
		$criteria->compare('hotbox_id',$this->hotbox_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('is_always',$this->is_always);
		$criteria->compare('start',$this->start);
		$criteria->compare('end',$this->end);
		$criteria->compare('thumbnail',$this->thumbnail);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotboxEvent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getLocation() {
		$location = array();
	
		if($this->country_id) {
			$country =  SysCountry::model()->getCountryInfo($this->country_id);
			$location[] = $country['name'];
		}
		if($this->state_id) {
			$state = LocationState::model()->getStateInfo($this->state_id);
			$location[] = $state['name'];
		}
		if($this->city_id) {
			$city = SysCity::model()->getCityInfo($this->city_id);
			$location[] = $city['name'];
		}
		if($this->city_id) {
			$district = SysDistrict::model()->getDistrictInfo($this->city_id);
			$location[] = $district['name'];
		}
		return implode(', ', array_filter($location));
	}
}
