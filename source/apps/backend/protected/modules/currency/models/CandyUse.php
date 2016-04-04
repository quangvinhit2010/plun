<?php

/**
 * This is the model class for table "cr_candy_use".
 *
 * The followings are the available columns in table 'cr_candy_use':
 * @property integer $id
 * @property integer $user_id
 * @property integer $object_id
 * @property integer $object_type
 * @property string $candy_amount
 * @property string $candy_current
 * @property integer $charge_id
 * @property string $description
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 */
class CandyUse extends CandyActiveRecord
{
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
		return 'cr_candy_use';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, object_id, object_type, charge_id, status, created, updated', 'numerical', 'integerOnly'=>true),
			array('candy_amount, candy_current', 'length', 'max'=>20),
			array('description', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, object_id, object_type, candy_amount, candy_current, charge_id, description, status, created, updated', 'safe', 'on'=>'search'),
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
			'object_id' => 'Object',
			'object_type' => 'Object Type',
			'candy_amount' => 'Candy Amount',
			'candy_current' => 'Candy Current',
			'charge_id' => 'Charge',
			'description' => 'Description',
			'status' => 'Status',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('object_type',$this->object_type);
		$criteria->compare('candy_amount',$this->candy_amount,true);
		$criteria->compare('candy_current',$this->candy_current,true);
		$criteria->compare('charge_id',$this->charge_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CandyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CandyUse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
