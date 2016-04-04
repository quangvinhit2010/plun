<?php

/**
 * This is the model class for table "auth_codes".
 *
 * The followings are the available columns in table 'auth_codes':
 * @property string $code
 * @property string $client_id
 * @property string $redirect_uri
 * @property integer $expires
 * @property string $scope
 */
class AuthCodes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuthCodes the static model class
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
		return 'api_auth_codes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, client_id, redirect_uri, expires', 'required'),
			array('expires', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>40),
			array('client_id', 'length', 'max'=>20),
			array('redirect_uri', 'length', 'max'=>200),
			array('scope', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('code, client_id, redirect_uri, expires, scope', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'client_id' => 'Client',
			'redirect_uri' => 'Redirect Uri',
			'expires' => 'Expires',
			'scope' => 'Scope',
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('redirect_uri',$this->redirect_uri,true);
		$criteria->compare('expires',$this->expires);
		$criteria->compare('scope',$this->scope,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}