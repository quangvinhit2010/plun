<?php

/**
 * This is the model class for table "sys_hotbox_stats".
 *
 * The followings are the available columns in table 'sys_hotbox_stats':
 * @property integer $hotbox_id
 * @property integer $like_count
 * @property integer $comment_count
 */
class HotboxStats extends CActiveRecord
{
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_hotbox_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hotbox_id', 'required'),
			array('hotbox_id, like_count, comment_count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hotbox_id, like_count, comment_count', 'safe', 'on'=>'search'),
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
			'hotbox_id' => 'Hotbox',
			'like_count' => 'Like Count',
			'comment_count' => 'Comment Count',
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

		$criteria->compare('hotbox_id',$this->hotbox_id);
		$criteria->compare('like_count',$this->like_count);
		$criteria->compare('comment_count',$this->comment_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotboxStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
}
