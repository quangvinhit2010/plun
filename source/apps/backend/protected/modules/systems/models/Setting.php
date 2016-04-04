<?php

/**
 * This is the model class for table "sys_setting".
 *
 * The followings are the available columns in table 'sys_setting':
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property integer $user_id
 */
class Setting extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Setting the static model class
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
		return 'sys_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('value', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, value, user_id', 'safe', 'on'=>'search'),
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
			'value' => 'Value',
			'user_id' => 'User',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getCover($user_id){
		$data = Setting::model()->findByAttributes(array('name' => 'COVER', 'user_id' => $user_id));
		if ($data)
			return $data->value;
		return "VER";
	}
	
	public static function getValueConfig($config_name){
		$config = Yii::app()->config;
		$value = $config->get($config_name);
		if (!empty($value)){
			$value = explode(';', $value);
			
			if (!empty($value)){
				$arr = array();
				foreach ($value as $key => $val){
					$tmp = explode(':', $val);
					if (!empty($tmp) && count($tmp) == 2) $arr[$tmp[0]] = $tmp[1]; 
				}
				
				return $arr;
			}
		}
		return null;
	}
}