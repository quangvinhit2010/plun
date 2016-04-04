<?php

/**
 * This is the model class for table "white_party_manila".
 *
 * The followings are the available columns in table 'white_party_manila':
 * @property integer $id
 * @property integer $user_id
 * @property string $full_name
 * @property string $phone
 * @property integer $id_no
 * @property integer $createtime
 * @property string $ip
 */
class WhitePartyManila extends EventActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'white_party_manila';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('full_name, id_no', 'required'),
			array('id_no', 'numerical', 'integerOnly'=>true),
			array('full_name', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>32),
			array('phone', 'checkPhone'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, full_name, phone, id_no, createtime, ip', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'full_name' => 'Full Name',
			'phone' => 'Mobile Number',
			'id_no' => 'ID Number',
			'createtime' => 'Createtime',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('id_no',$this->id_no);
		$criteria->compare('ip',$this->ip,true);
		
		if($this->createtime) {
			$from = strtotime($this->createtime . " 00:00:00");
			$to = strtotime($this->createtime . " 23:59:59");
			$criteria->addCondition('createtime >= "'.$from.'" ');
			$criteria->addCondition('createtime <= "'.$to.'" ');
		} else
			$criteria->compare('createtime',$this->createtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_event;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WhitePartyManila the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkPhone($attribute) {
		$pattern = '/^[0-9 \.-]+$/';
		if($this->$attribute != '') {
			if(preg_match($pattern, $this->$attribute) == 0) {
				$this->addError($attribute, Lang::t('settings', 'Phone Number is not valid'));
			}
		}
	}
}
