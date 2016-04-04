<?php

/**
 * This is the model class for table "ofmessagearchive".
 *
 * The followings are the available columns in table 'ofmessagearchive':
 * @property string $conversationID
 * @property string $fromJID
 * @property string $fromJIDResource
 * @property string $toJID
 * @property string $toJIDResource
 * @property string $sentDate
 * @property string $body
 */
class MessageArchive extends ChatActiveRecord
{

	
	//public $send_date_time;
	
	
	
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	/* public function getDbConnection()
	{
		return Yii::app()->db_openfire;
	} */
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ofMessageArchive';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('conversationID, fromJID, toJID, sentDate', 'required'),
			array('conversationID, sentDate', 'length', 'max'=>20),
			array('fromJID, toJID', 'length', 'max'=>255),
			array('fromJIDResource, toJIDResource', 'length', 'max'=>100),
			array('body', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('conversationID, fromJID, fromJIDResource, toJID, toJIDResource, sentDate, body', 'safe', 'on'=>'search'),
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
			'conversationID' => 'Conversation',
			'fromJID' => 'From Jid',
			'fromJIDResource' => 'From Jidresource',
			'toJID' => 'To Jid',
			'toJIDResource' => 'To Jidresource',
			'sentDate' => 'Sent Date',
			'body' => 'Body',
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

		$criteria->compare('conversationID',$this->conversationID,true);
		$criteria->compare('fromJID',$this->fromJID,true);
		$criteria->compare('fromJIDResource',$this->fromJIDResource,true);
		$criteria->compare('toJID',$this->toJID,true);
		$criteria->compare('toJIDResource',$this->toJIDResource,true);
		$criteria->compare('sentDate',$this->sentDate,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessageArchive the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
