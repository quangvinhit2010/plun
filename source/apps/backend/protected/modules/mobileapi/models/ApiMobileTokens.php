<?php

/**
 * This is the model class for table "api_mobile_tokens".
 *
 * The followings are the available columns in table 'api_mobile_tokens':
 * @property string $id
 * @property string $token
 * @property string $macaddress
 * @property integer $user_id
 * @property integer $date_created
 * @property integer $date_updated
 * @property string $device_info
 * @property string $ip
 */
class ApiMobileTokens extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_mobile_tokens';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, date_created, date_updated', 'numerical', 'integerOnly'=>true),
			array('token', 'length', 'max'=>200),
			array('macaddress', 'length', 'max'=>50),
			array('ip', 'length', 'max'=>20),
			array('device_info', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, token, macaddress, user_id, date_created, date_updated, device_info, ip', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Member', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'token' => 'Token',
			'macaddress' => 'Macaddress',
			'user_id' => 'User',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
			'device_info' => 'Device Info',
			'ip' => 'Ip',
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
		$criteria->compare('token',$this->token,true);
		$criteria->compare('macaddress',$this->macaddress,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('date_updated',$this->date_updated);
		$criteria->compare('device_info',$this->device_info,true);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiMobileTokens the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
