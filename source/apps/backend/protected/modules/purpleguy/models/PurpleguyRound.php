<?php

/**
 * This is the model class for table "purpleguy_round".
 *
 * The followings are the available columns in table 'purpleguy_round':
 * @property integer $id
 * @property string $round_name
 * @property integer $time_start
 * @property integer $time_end
 * @property integer $event_id
 */
class PurpleguyRound extends EventActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purpleguy_round';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time_start, time_end, event_id', 'required'),
			array('time_start, time_end, event_id', 'numerical', 'integerOnly'=>true),
			array('round_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, round_name, time_start, time_end, event_id', 'safe', 'on'=>'search'),
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
			'event' => array(self::BELONGS_TO, 'PurpleguyEvent', 'event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'round_name' => 'Round Name',
			'time_start' => 'Time Start',
			'time_end' => 'Time End',
			'event_id' => 'Event',
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
		$criteria->compare('round_name',$this->round_name,true);
		$criteria->compare('time_start',$this->time_start);
		$criteria->compare('time_end',$this->time_end);
		$criteria->compare('event_id',$this->event_id);

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
	 * @return PurpleguyRound the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCurrentRound() {
		$purpleGuyEvent = PurpleguyEvent::model()->find('enable = :enable', array(':enable'=>PurpleguyEvent::ENABLED));
		
		if($purpleGuyEvent) {
			$purpleGuyRound = PurpleguyRound::model()->find('event_id = :event_id AND time_start <= :now AND time_end >= :now', array(':event_id'=>$purpleGuyEvent->id, ':now'=>time()));
		} else {
			$purpleGuyRound = NULL;
		}
		return $purpleGuyRound;
	}
}
