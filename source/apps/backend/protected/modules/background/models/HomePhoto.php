<?php

/**
 * This is the model class for table "sys_home_photo".
 *
 * The followings are the available columns in table 'sys_home_photo':
 * @property integer $id
 * @property integer $position_id
 * @property string $file_name
 * @property string $link
 * @property string $description
 * @property integer $status
 * @property integer $created
 */
class HomePhoto extends CActiveRecord
{
	const TYPE_245_245 = 1;
	const TYPE_245_490 = 2;
	const TYPE_490_245 = 3;
	const TYPE_490_490 = 4;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_home_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('position_id, file_name', 'required'),
			array('position_id, status, created', 'numerical', 'integerOnly'=>true),
			array('file_name', 'length', 'max'=>225),
			array('link', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, position_id, file_name, link, description, status, created', 'safe', 'on'=>'search'),
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
			'position' => array(self::BELONGS_TO, 'HomePosition', 'position_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'position_id' => 'Position',
			'file_name' => 'File Name',
			'link' => 'Link',
			'description' => 'Description',
			'status' => 'Status',
			'created' => 'Created',
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
		$criteria->compare('position_id',$this->position_id);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HomePhoto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * get attributtes by column and row
	 * @param $col
	 * @param $row
	 * @return multitype:number string
	 */
	public function getAttrByColRow($col, $row){
		if($col == 1 && $row == 1){
			$_width = 245;
			$_height = 245;
			$pathThumb = 'uploads'.DS.'home'.DS.'photo'.DS.'thumb245x245';
			$type = HomePhoto::TYPE_245_245;
		}elseif($col == 1 && $row == 2){
			$_width = 245;
			$_height = 490;
			$pathThumb = 'uploads'.DS.'home'.DS.'photo'.DS.'thumb245x490';
			$type = HomePhoto::TYPE_245_490;
		}elseif($col == 2 && $row == 1){
			$_width = 490;
			$_height = 245;
			$pathThumb = 'uploads'.DS.'home'.DS.'photo'.DS.'thumb490x245';
			$type = HomePhoto::TYPE_490_245;
		}elseif($col == 2 && $row == 2){
			$_width = 490;
			$_height = 490;
			$pathThumb = 'uploads'.DS.'home'.DS.'photo'.DS.'thumb490x490';
			$type = HomePhoto::TYPE_490_490;
		}
		return array('_width'=>$_width,
				'_height'=>$_height,
				'pathThumb'=>$pathThumb,
				'type'=>$type
		);
	}
	
	public function getColRowByType($type){
		if(!empty($type)){
			switch ($type){
				case HomePhoto::TYPE_245_245:
					return array('col'=>1, 'row'=>1);
					break;
				case HomePhoto::TYPE_245_490:
					return array('col'=>1, 'row'=>2);
					break;
				case HomePhoto::TYPE_490_245:
					return array('col'=>2, 'row'=>1);
					break;
				case HomePhoto::TYPE_490_490:
					return array('col'=>2, 'row'=>2);
					break;
			}
		}
		return false;
	}
}
