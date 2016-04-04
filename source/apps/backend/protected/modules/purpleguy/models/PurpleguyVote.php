<?php

/**
 * This is the model class for table "purpleguy_vote".
 *
 * The followings are the available columns in table 'purpleguy_vote':
 * @property integer $id
 * @property integer $vote_by
 * @property integer $user_id
 * @property integer $round_id
 */
class PurpleguyVote extends EventActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purpleguy_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vote_by, user_id, round_id', 'required'),
			array('vote_by, user_id, round_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vote_by, user_id, round_id', 'safe', 'on'=>'search'),
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
			'vote_by' => 'Vote By',
			'user_id' => 'User',
			'round_id' => 'Round',
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
		$criteria->compare('vote_by',$this->vote_by);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('round_id',$this->round_id);

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
	 * @return PurpleguyVote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function insertVote($userId, $voteBy, $roundId) {
		$this->vote_by = $voteBy;
		$this->user_id = $userId;
		$this->round_id = $roundId;
		$this->created = time();
		$this->ip = $_SERVER['REMOTE_ADDR'];
	
		$this->save();
	}
}
