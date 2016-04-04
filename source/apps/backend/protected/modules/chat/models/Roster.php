<?php

/**
 * This is the model class for table "ofroster".
 *
 * The followings are the available columns in table 'ofroster':
 * @property string $rosterID
 * @property string $username
 * @property string $jid
 * @property integer $sub
 * @property integer $ask
 * @property integer $recv
 * @property string $nick
 */
class Roster extends ChatActiveRecord //ChatActiveRecord
{
	
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
		return 'ofRoster';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, jid, sub, ask, recv', 'required'),
			array('sub, ask, recv', 'numerical', 'integerOnly'=>true),
			array('rosterID', 'length', 'max'=>20),
			array('username', 'length', 'max'=>64),
			array('jid', 'length', 'max'=>1024),
			array('nick', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rosterID, username, jid, sub, ask, recv, nick', 'safe', 'on'=>'search'),
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
			'rosterID' => 'Roster',
			'username' => 'Username',
			'jid' => 'Jid',
			'sub' => 'Sub',
			'ask' => 'Ask',
			'recv' => 'Recv',
			'nick' => 'Nick',
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

		$criteria->compare('rosterID',$this->rosterID,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('jid',$this->jid,true);
		$criteria->compare('sub',$this->sub);
		$criteria->compare('ask',$this->ask);
		$criteria->compare('recv',$this->recv);
		$criteria->compare('nick',$this->nick,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
