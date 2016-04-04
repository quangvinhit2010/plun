<?php

/**
 * This is the model class for table "table42_result".
 *
 * The followings are the available columns in table 'table42_result':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $round_id
 * @property integer $published
 * @property integer $date_created
 * @property integer $couple_id
 */
class Table42Result extends EventActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_result';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('round_id, published, date_created, couple_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, round_id, published, date_created, couple_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
	    $relations['photo'] = array(self::HAS_MANY, 'Table42ResultPhoto', 'result_id');
	    $relations['round'] = array(self::BELONGS_TO, 'Table42Round', 'round_id');
	    $relations['couple'] = array(self::BELONGS_TO, 'Table42DatingRequest', 'couple_id');
	     
	    
	    return CMap::mergeArray(
	            $relations,
	            parent::relations()
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
			'description' => 'Description',
			'round_id' => 'Round',
			'published' => 'Published',
			'date_created' => 'Date Created',
			'couple_id' => 'Couple ID',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('round_id',$this->round_id);
		$criteria->compare('published',$this->published);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('couple_id',$this->couple_id);
		$criteria->order = 'id DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42Result the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getResult(){
		$criteria=new CDbCriteria;
		$criteria->addCondition("published = 1");
	
		return $this->find($criteria);
	}	
}
