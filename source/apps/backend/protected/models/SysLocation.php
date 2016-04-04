<?php

/**
 * This is the model class for table "sys_location".
 *
 * The followings are the available columns in table 'sys_location':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $city_id
 * @property integer $country_id
 * @property integer $district_id
 */
class SysLocation extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sys_location';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city_id, country_id, district_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, description, city_id, country_id, district_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
            $relations  =   array();
            /*
            $relations['location'] = array(
                            self::HAS_MANY, 'YumUser', 'location_id');     
             * 
             */   
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'city_id' => 'City',
            'country_id' => 'Country',
            'district_id' => 'District',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('district_id', $this->district_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SysLocation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
