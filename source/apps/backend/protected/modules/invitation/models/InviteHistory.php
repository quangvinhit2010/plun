<?php

/**
 * This is the model class for table "invite_history".
 *
 * The followings are the available columns in table 'invite_history':
 * @property string $id
 * @property integer $user_id
 * @property string $invited_email
 * @property integer $invited_id
 * @property string $message
 * @property string $type
 * @property integer $status
 * @property integer $created
 */
class InviteHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InviteHistory the static model class
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
		return 'invite_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invited_email', 'required'),
			array('user_id, invited_id, status, created', 'numerical', 'integerOnly'=>true),
			array('invited_email, type', 'length', 'max'=>128),
			array('message', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, invited_email, invited_id, message, type, status, created', 'safe', 'on'=>'search'),
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
			'inviter' => array(self::BELONGS_TO, 'YumUser', 'user_id'),
			'invited' => array(self::BELONGS_TO, 'YumUser', 'invited_id'),
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
			'invited_email' => 'Invited Email',
			'invited_id' => 'Invited',
			'message' => 'Message',
			'type' => 'Type',
			'status' => 'Status',
			'created' => 'Created',
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

		$criteria->compare('id',$this->id,true);
//		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('invited_email',$this->invited_email,true);
//		$criteria->compare('invited_id',$this->invited_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);
		
		if(!empty($this->user_id)){
			$criteria->with = array(
				'inviter' => array(
					'alias' => 'inviter',		
					'condition' => 'inviter.username LIKE "'.$this->user_id.'%"'				 		
				),					
			);
		}
		
		if(!empty($this->invited_id)){
			$criteria->with = array(
				'invited' => array(
					'alias' => 'invited',		
					'condition' => 'invited.username LIKE "'.$this->invited_id.'%"'				 		
				),					
			);
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}