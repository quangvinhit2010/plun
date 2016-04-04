<?php

/**
 * This is the model class for table "cr_charge".
 *
 * The followings are the available columns in table 'cr_charge':
 * @property integer $id
 * @property integer $charge
 * @property integer $charge_type
 * @property integer $type
 * @property string $description
 * @property integer $status
 * @property integer $created
 */
class Charge extends CandyActiveRecord
{
	const CHARGE_TYPE_PERCENT = 1;
	const CHARGE_TYPE_AMOUNT = 2;
	
	const TYPE_USER_TRANSFER = 1;
	const TYPE_CANDY_TO_MONEY = 2;
	const TYPE_PRIVATE_PHOTO = 3;
	
	/**
	 * @see CActiveRecord::getDbConnection()
	 */
	public function getDbConnection()
	{
		return self::getDbLogConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cr_charge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('charge, type', 'required'),
			array('charge, charge_type, type, status, created', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, charge, charge_type, type, description, status, created', 'safe', 'on'=>'search'),
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
			'charge' => 'Charge',
			'charge_type' => 'Charge Type',
			'type' => 'Type',
			'description' => 'Description',
			'status' => 'Status',
			'created' => 'Created',
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
		$criteria->compare('charge',$this->charge);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('type',$this->type);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CandyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Charge the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function CalCandyCurrent($chargeId, $candyAmount) {
		$chargeOnSender = true;
		$charge = $this->findByPk($chargeId);
		
		if($charge->type == Charge::TYPE_PRIVATE_PHOTO)
			$chargeOnSender = false;
		
		$fee = ($charge->charge_type == Charge::CHARGE_TYPE_AMOUNT) ? $charge->charge : ($candyAmount * $charge->charge/100);

		if($fee < $candyAmount) {
			$candyCurrent = array('sender' => $candyAmount, 'receiver' => $candyAmount);
			
			if($chargeOnSender)
				$candyCurrent['sender'] = $candyAmount + $fee;
			else
				$candyCurrent['receiver'] = $candyAmount - $fee;
				
			return $candyCurrent;
		} else
			return false;
	}
}
