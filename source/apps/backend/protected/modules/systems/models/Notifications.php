<?php

/**
 * This is the model class for table "sys_notifications".
 *
 * The followings are the available columns in table 'sys_notifications':
 * @property integer $id
 * @property integer $userid
 * @property integer $ownerid
 * @property string $owner_type
 * @property integer $notification_type
 * @property string $notification_data
 * @property integer $timestamp
 * @property integer $last_read
 */
class Notifications extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, ownerid, notification_type, timestamp, last_read, object_id, status', 'numerical', 'integerOnly'=>true),
			array('owner_type', 'length', 'max'=>6),
			array('notification_data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userid, ownerid, owner_type, notification_type, notification_data, timestamp, last_read, object_id, status', 'safe', 'on'=>'search'),
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
			'userid' => 'Userid',
			'ownerid' => 'Ownerid',
			'owner_type' => 'Owner Type',
			'notification_type' => 'Notification Type',
			'notification_data' => 'Notification Data',
			'timestamp' => 'Timestamp',
			'last_read' => 'Last Read',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('ownerid',$this->ownerid);
		$criteria->compare('owner_type',$this->owner_type,true);
		$criteria->compare('notification_type',$this->notification_type);
		$criteria->compare('notification_data',$this->notification_data,true);
		$criteria->compare('timestamp',$this->timestamp);
		$criteria->compare('last_read',$this->last_read);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}