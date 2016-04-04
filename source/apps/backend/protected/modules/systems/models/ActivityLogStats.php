<?php

/**
 * This is the model class for table "activities_stats".
 *
 * The followings are the available columns in table 'activities_stats':
 * @property integer $id
 * @property integer $log_id
 * @property integer $like_count
 * @property integer $comment_count
 */
class ActivityLogStats extends ActivityActiveRecord
{
	public function getDbConnection()
    {
        return self::getDbLogConnection();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActivityLogStats the static model class
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
		return 'activities_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log_id, like_count, comment_count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, log_id, like_count, comment_count', 'safe', 'on'=>'search'),
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
			'log_id' => 'Log',
			'like_count' => 'Like Count',
			'comment_count' => 'Comment Count',
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
		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('like_count',$this->like_count);
		$criteria->compare('comment_count',$this->comment_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}