<?php

/**
 * This is the model class for table "invite_bonus".
 *
 * The followings are the available columns in table 'invite_bonus':
 * @property integer $id
 * @property string $history_invited_id
 * @property double $invited_realcash
 * @property double $rate
 * @property double $bonus
 * @property integer $execute
 * @property integer $timeline_rate_id
 * @property integer $fromdate
 * @property integer $todate
 * @property string $params
 * @property integer $created
 * @property integer $award_date
 *
 * The followings are the available model relations:
 * @property InviteHistory $historyInvited
 * @property InviteTimelineRate $timelineRate
 */
class InviteBonus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InviteBonus the static model class
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
		return 'invite_bonus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('execute, timeline_rate_id, fromdate, todate, created, award_date', 'numerical', 'integerOnly'=>true),
			array('invited_realcash, rate, bonus', 'numerical'),
			array('history_invited_id', 'length', 'max'=>11),
			array('params', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, history_invited_id, invited_realcash, rate, bonus, execute, timeline_rate_id, fromdate, todate, params, created, award_date', 'safe', 'on'=>'search'),
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
			'historyInvited' => array(self::BELONGS_TO, 'InviteHistory', 'history_invited_id'),
			'timelineRate' => array(self::BELONGS_TO, 'InviteTimelineRate', 'timeline_rate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'history_invited_id' => 'History Invited',
			'invited_realcash' => 'Invited Realcash',
			'rate' => 'Rate',
			'bonus' => 'Bonus',
			'execute' => 'Execute',
			'timeline_rate_id' => 'Timeline Rate',
			'fromdate' => 'Fromdate',
			'todate' => 'Todate',
			'params' => 'Params',
			'created' => 'Created',
			'award_date' => 'Award Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('history_invited_id',$this->history_invited_id,true);
		$criteria->compare('invited_realcash',$this->invited_realcash);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('bonus',$this->bonus);
		$criteria->compare('execute',$this->execute);
		$criteria->compare('timeline_rate_id',$this->timeline_rate_id);
		$criteria->compare('fromdate',$this->fromdate);
		$criteria->compare('todate',$this->todate);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('award_date',$this->award_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}