<?php

/**
 * This is the model class for table "coupon_events".
 *
 * The followings are the available columns in table 'coupon_events':
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $description
 * @property string $item
 * @property integer $start
 * @property integer $end
 * @property integer $created
 * @property integer $enabled
 * @property string $note
 */
class Events extends CActiveRecord
{
	const GIFT_TO_ROI 				= 1;
	const GIFT_QUAY_SO 				= 2;
	const GIFT_TAN_THU 				= 3;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Events the static model class
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
		return 'coupon_events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start, end, created, enabled', 'numerical', 'integerOnly'=>true),
			array('title, item', 'length', 'max'=>255),
			array('image', 'length', 'max'=>500),
			array('description, note', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, image, description, item, start, end, created, enabled, note', 'safe', 'on'=>'search'),
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
			'codeCount'   	=> array(self::STAT, 'GiftCode', 'event_id'),
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
			'image' => 'Image',
			'description' => 'Description',
			'item' => 'Item',
			'start' => 'Start',
			'end' => 'End',
			'created' => 'Created',
			'enabled' => 'Enabled',
			'note' => 'Note',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('start',$this->start);
		$criteria->compare('end',$this->end);
		$criteria->compare('created',$this->created);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave(){
		if(!empty($this->item)){
			$arr = array();
			$items = explode("\n", $this->item);
			if(count($items) > 0) {
				foreach ($items as $item) {
					$item = trim($item);
					if(!empty($item)){
						$arr[] = trim($item);
					}
				}
			}
			$this->item = json_encode($arr);
		}
		return parent::beforeSave();
	}
}