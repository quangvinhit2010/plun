<?php

/**
 * This is the model class for table "cms_venues_temp".
 *
 * The followings are the available columns in table 'cms_venues_temp':
 * @property string $id
 * @property string $title
 * @property string $title_nosymbol
 * @property integer $date_created
 * @property integer $parent_id
 * @property integer $date_modified
 * @property integer $user_created
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $city_id
 * @property integer $district_id
 */
class CmsVenuesTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_venues_temp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_created, parent_id, date_modified, user_created, country_id, state_id, city_id, district_id', 'numerical', 'integerOnly'=>true),
			array('title, title_nosymbol', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, title_nosymbol, date_created, parent_id, date_modified, user_created, country_id, state_id, city_id, district_id', 'safe', 'on'=>'search'),
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
			'title_nosymbol' => 'Title Nosymbol',
			'date_created' => 'Date Created',
			'parent_id' => 'Parent',
			'date_modified' => 'Date Modified',
			'user_created' => 'User Created',
			'country_id' => 'Country',
			'state_id' => 'State',
			'city_id' => 'City',
			'district_id' => 'District',
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
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('date_modified',$this->date_modified);
		$criteria->compare('user_created',$this->user_created);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('district_id',$this->district_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsVenuesTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
