<?php

/**
 * This is the model class for table "usr_profile_location".
 *
 * The followings are the available columns in table 'usr_profile_location':
 * @property string $user_id
 * @property string $country_id
 * @property string $state_id
 * @property string $city_id
 * @property string $district_id
 */
class UsrProfileLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usr_profile_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, current_country_id, current_state_id, current_city_id, current_district_id, published, updated, venue_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, current_country_id, current_state_id, current_city_id, current_district_id, updated, venue_id', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'current_country_id' => 'Country',
			'current_state_id' => 'State',
			'current_city_id' => 'City',
			'current_district_id' => 'District',
			'check_in_dated'	=>	'check_in_dated'
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('current_country_id',$this->current_country_id,true);
		$criteria->compare('current_state_id',$this->current_state_id,true);
		$criteria->compare('current_city_id',$this->current_city_id,true);
		$criteria->compare('current_district_id',$this->current_district_id,true);
		$criteria->compare('check_in_dated',$this->check_in_dated,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsrProfileLocation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
