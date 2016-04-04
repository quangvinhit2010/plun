<?php

/**
 * This is the model class for table "sys_state".
 *
 * The followings are the available columns in table 'sys_state':
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $country_id
 * @property string $location_type_id
 */
class LocationState extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id', 'required'),
			array('name', 'length', 'max'=>150),
			array('code', 'length', 'max'=>20),
			array('country_id, location_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, code, country_id, location_type_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'code' => 'Code',
			'country_id' => 'Country',
			'location_type_id' => 'Location Type',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('country_id',$this->country_id,true);
		$criteria->compare('location_type_id',$this->location_type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocationState the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function getStateList() {
    	$criteria = new CDbCriteria;
    	$criteria->order = 'ordering DESC';
        $data = $this->findAll();
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id
            );
        }
        return $result;
    }
    public function getStateByCountry($country_id) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('country_id =' .  $country_id);
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id
            );
        }
        return $result;
    } 
    public function getTopStateByCountry($country_id) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('country_id =' .  $country_id);
        $criteria->addCondition('top = 1');
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id
            );
        }
        return $result;
    }    
    public function getStateInfo($id) {
        $row = $this->findByPk($id);
        if ($row) {
            return array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id
            );
        } else {
            return false;
        }
    }       	
}
