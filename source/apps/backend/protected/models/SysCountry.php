<?php

/**
 * This is the model class for table "sys_country".
 *
 * The followings are the available columns in table 'sys_country':
 * @property string $id
 * @property string $name
 */
class SysCountry extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sys_country';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
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

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SysCountry the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getCountryList() {
    	$criteria = new CDbCriteria();
    	//$criteria->order	=	'ordering DESC';
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name
            );
        }
        return $result;
    }
    
    public function getCountryCodeList(){
        $data = $this->findAll();
        $result = array();
        foreach ($data AS $row) {
            $result[$row->code] = array(
                'id' => $row->id,
                'name' => $row->name,
            	'code' => $row->code
            );
        }
        return $result;    	
    }
    public function getCountryTop(){
    	$criteria = new CDbCriteria();
    	$criteria->condition = 'top = 1';
    	$criteria->order = 'ordering ASC';
        $data = $this->findAll($criteria);
        $result = array();
        foreach ($data AS $row) {
            $result[$row->id] = array(
                'id' => $row->id,
                'name' => $row->name
            );
        }
        return $result;    
    }
    public function getCountryInfo($id){
        $row    =   $this->findByPk($id);
        if($row){
            return array(
                'id'    =>  $row->id,
                'name'  =>  $row->name
            );
        }else{
            return false;
        }
    }

}
