<?php

/**
 * This is the model class for table "table42_vote".
 *
 * The followings are the available columns in table 'table42_vote':
 * @property string $id
 * @property integer $vote_by
 * @property integer $user_id
 * @property integer $round_id
 * @property integer $date_created
 * @property string $ip
 */
class Table42Vote extends EventActiveRecord
{
	const TYPE_VOTE_COUPLE = 1;
	const TYPE_VOTE_USER = 2;
	
	public $total = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vote_by, vote_for_id, vote_for_type, round_id, date_created', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vote_by, vote_for_id, vote_for_type, round_id, date_created, ip', 'safe', 'on'=>'search'),
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
			'vote_for_id' => 'User',
			'round_id' => 'Round',
			'date_created' => 'Date Created',
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
		$criteria->compare('vote_by',$this->vote_by);
		$criteria->compare('vote_for_id',$this->vote_for_id);
		$criteria->compare('round_id',$this->round_id);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42Vote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * get total vote
	 */
	public function getTotalvote($vote_for_id, $round_id, $vote_for_type = Table42Vote::TYPE_VOTE_COUPLE){
		$criteria=new CDbCriteria;
		$criteria->select = "COUNT(id) AS total";
		$criteria->addCondition("vote_for_id = $vote_for_id");
		$criteria->addCondition("round_id = $round_id");
		$criteria->addCondition("vote_for_type = $vote_for_type");
		$row	=	$this->find($criteria);
		if($row){
			return $row->total;
		}else{
			return 0;
		}
	}
	/**
	 * check vote by
	 */
	public function getVoteBy($vote_by, $vote_for_id, $round_id, $vote_for_type = Table42Vote::TYPE_VOTE_COUPLE){
		$criteria=new CDbCriteria;
		$criteria->addCondition("vote_by = $vote_by");
		$criteria->addCondition("vote_for_id = $vote_for_id");
		$criteria->addCondition("round_id = $round_id");
		$criteria->addCondition("vote_for_type = $vote_for_type");		
		$row	=	$this->find($criteria);
		if($row){
			return $row;
		}else{
			return false;
		}
	}
}
