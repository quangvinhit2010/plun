<?php

/**
 * This is the model class for table "table42_round".
 *
 * The followings are the available columns in table 'table42_round':
 * @property string $id
 * @property string $title
 * @property integer $time_start
 * @property integer $time_end
 * @property string $description
 * @property integer $published
 */
class Table42Round extends EventActiveRecord
{
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_round';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time_start, time_end, published, disable_vote, disable_request', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, time_start, time_end, description, published, disable_vote, disable_request', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'time_start' => 'Time Start',
			'time_end' => 'Time End',
			'description' => 'Description',
			'published' => 'Published',
			'disable_vote' => 'Disabled Vote',
			'disable_request' => 'Disable request',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('time_start',$this->time_start);
		$criteria->compare('time_end',$this->time_end);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('published',$this->published);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42Round the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCurrentRound(){
		$criteria=new CDbCriteria;
		$criteria->addCondition("published = 1");
		
		return $this->find($criteria);
	}
	
	public function getNextRound(){
		$row	=	$this->getCurrentRound();
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("time_start > {$row->time_end}");
		$criteria->limit	=	1;
		$criteria->order	=	'time_start ASC';
		
		return $this->find($criteria);		
	}
	public function getPreRound(){
		$row	=	$this->getCurrentRound();
		$criteria=new CDbCriteria;
		$criteria->addCondition("time_end < {$row->time_start}");
		$criteria->limit	=	1;
		$criteria->order	=	'time_end DESC';
		
		return $this->find($criteria);		
	}
	public function getList(){
		$data	=	$this->findAll();
		$result	=	array();
		foreach($data AS $row){
			$result[$row->id]	=	$row->title;
		}
		return $result;
	}
}
