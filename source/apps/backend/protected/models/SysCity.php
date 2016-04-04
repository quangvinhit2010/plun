<?php

/**
 * This is the model class for table "sys_city".
 *
 * The followings are the available columns in table 'sys_city':
 * @property string $id
 * @property string $name
 * @property integer $country_id
 */
class SysCity extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sys_city';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('country_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            array('code', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, code, country_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        	'code' => 'Code',
            'country_id' => 'Country',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('country_id', $this->country_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SysCity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getCityList() {
        $data = $this->findAll();
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id,
            	'state_id' => $row->state_id
            );
        }
        return $result;
    }

    public function getCityByCountry($country_id) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('country_id =' .  $country_id);
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id,
            	'state_id' => $row->state_id
            );
        }
        return $result;
    }
    public function getCityByState($state_id) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('state_id =' .  $state_id);
        $criteria->order	=	"LEFT(`name`, 1) ASC,BIT_LENGTH(`name`) ASC, `name` ASC";
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'country_id' => $row->country_id,
            	'state_id' => $row->state_id
            );
        }
        return $result;
    }
    public function getCityInfo($id) {
        $row = $this->findByPk($id);
        if ($row) {
            return array(
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
            	'state_id' => $row->state_id,
                'country_id' => $row->country_id
            );
        } else {
            return false;
        }
    }

}
