<?php

/**
 * This is the model class for table "sys_vote_logs".
 *
 * The followings are the available columns in table 'sys_vote_logs':
 * @property string $id
 * @property string $voter_id
 * @property string $vote_for
 * @property string $vote_datetime
 * @property string $voter_ip
 * @property integer $vote_type
 */
class SysVoteLogs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_vote_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vote_type', 'numerical', 'integerOnly'=>true),
			array('voter_id, vote_for, vote_datetime', 'length', 'max'=>10),
			array('voter_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, voter_id, vote_for, vote_datetime, voter_ip, vote_type', 'safe', 'on'=>'search'),
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
			'voter_id' => 'Voter',
			'vote_for' => 'Vote For',
			'vote_datetime' => 'Vote Datetime',
			'voter_ip' => 'Voter Ip',
			'vote_type' => 'Vote Type',
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
		$criteria->compare('voter_id',$this->voter_id,true);
		$criteria->compare('vote_for',$this->vote_for,true);
		$criteria->compare('vote_datetime',$this->vote_datetime,true);
		$criteria->compare('voter_ip',$this->voter_ip,true);
		$criteria->compare('vote_type',$this->vote_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysVoteLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
