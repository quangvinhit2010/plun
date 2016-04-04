<?php

/**
 * This is the model class for table "com_community".
 *
 * The followings are the available columns in table 'com_community':
 * @property integer $id
 * @property string $community_name
 * @property string $community_alias
 * @property string $about
 * @property integer $privacy
 * @property integer $members
 * @property integer $creator
 * @property integer $created
 * @property integer $updated
 */
class Community extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'com_community';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('community_name', 'required'),
			array('privacy, members, creator, created, updated', 'numerical', 'integerOnly'=>true),
			array('community_name, community_alias', 'length', 'max'=>255),
			array('about', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, community_name, community_alias, about, privacy, members, creator, created, updated', 'safe', 'on'=>'search'),
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
			'creatorby' => array(self::BELONGS_TO, 'Member', 'creator'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'community_name' => 'Community Name',
			'community_alias' => 'Community Alias',
			'about' => 'About',
			'privacy' => 'Privacy',
			'members' => 'Members',
			'creator' => 'Creator',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('community_name',$this->community_name,true);
		$criteria->compare('community_alias',$this->community_alias,true);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('privacy',$this->privacy);
		$criteria->compare('members',$this->members);
		$criteria->compare('creator',$this->creator);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Community the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
