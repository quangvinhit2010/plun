<?php

/**
 * This is the model class for table "archivemessages".
 *
 * The followings are the available columns in table 'archivemessages':
 * @property string $messageId
 * @property string $time
 * @property string $direction
 * @property string $type
 * @property string $subject
 * @property string $body
 * @property string $conversationId
 */
class ArchiveMessages extends ChatActiveRecord
{
	public $chat_time;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'archiveMessages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('messageId, time, direction, type, conversationId', 'required'),
			array('messageId, time, conversationId', 'length', 'max'=>20),
			array('direction', 'length', 'max'=>4),
			array('type', 'length', 'max'=>15),
			array('subject', 'length', 'max'=>255),
			array('body', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('messageId, time, direction, type, subject, body, conversationId', 'safe', 'on'=>'search'),
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
			'messageId' => 'Message',
			'time' => 'Time',
			'direction' => 'Direction',
			'type' => 'Type',
			'subject' => 'Subject',
			'body' => 'Body',
			'conversationId' => 'Conversation',
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

		$criteria->compare('messageId',$this->messageId,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('direction',$this->direction,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('conversationId',$this->conversationId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_openfire;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function afterFind(){
		$this->setAttribute('time', $this->chat_time);
		return parent::afterFind();
	}
}
