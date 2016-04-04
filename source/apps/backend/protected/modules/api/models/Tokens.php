<?php

/**
 * This is the model class for table "tokens".
 *
 * The followings are the available columns in table 'tokens':
 * @property string $oauth_token
 * @property string $client_id
 * @property integer $expires
 * @property string $scope
 */
class Tokens extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tokens the static model class
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
		return 'api_tokens';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oauth_token, client_id, expires', 'required'),
			array('expires', 'numerical', 'integerOnly'=>true),
			array('oauth_token', 'length', 'max'=>40),
			array('client_id', 'length', 'max'=>20),
			array('scope', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('oauth_token, client_id, expires, scope', 'safe', 'on'=>'search'),
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
			'oauth_token' => 'Oauth Token',
			'client_id' => 'Client',
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

		$criteria->compare('oauth_token',$this->oauth_token,true);
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('expires',$this->expires);
		$criteria->compare('scope',$this->scope,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
}