<?php

/**
 * This is the model class for table "sys_referrer_log".
 *
 * The followings are the available columns in table 'sys_referrer_log':
 * @property integer $id
 * @property integer $type_referrer
 * @property string $redirect_url
 * @property string $referrer_url
 * @property integer $referrer_id
 * @property integer $user_id
 * @property integer $type_log
 * @property integer $created
 */
class ReferrerLog extends CActiveRecord
{
	const TYPE_LOG_REGISTER = 1;
	const TYPE_LOG_LOGIN = 2;
	const TYPE_UTM = 1;
	const TYPE_DIRECT = 2;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_referrer_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_referrer, referrer_id, user_id, type_log, created', 'numerical', 'integerOnly'=>true),
			array('redirect_url, referrer_url', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type_referrer, redirect_url, referrer_url, referrer_id, user_id, type_log, created', 'safe', 'on'=>'search'),
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
				'define' => array(self::BELONGS_TO, 'ReferrerDefine', 'referrer_id'),
				'user' => array(self::BELONGS_TO, 'Member', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_referrer' => 'Type Referrer',
			'redirect_url' => 'Redirect Url',
			'referrer_url' => 'Referrer Url',
			'referrer_id' => 'Referrer',
			'user_id' => 'User',
			'type_log' => 'Type Log',
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
		$criteria->compare('type_referrer',$this->type_referrer);
		$criteria->compare('redirect_url',$this->redirect_url,true);
		$criteria->compare('referrer_url',$this->referrer_url,true);
		$criteria->compare('referrer_id',$this->referrer_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type_log',$this->type_log);
		if($this->created) {
			$from = strtotime($this->created . " 00:00:00");
			$to = strtotime($this->created . " 23:59:59");
			$criteria->addCondition('created >= "'.$from.'" ');
			$criteria->addCondition('created <= "'.$to.'" ');
		} else
			$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReferrerLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function afterSave() {
		if(isset($this->referrer_id)){
			$define = ReferrerDefine::model()->findByPk($this->referrer_id);
			if(isset($define)){
				if($this->type_log == self::TYPE_LOG_LOGIN){
					$define->login_count ++;
				} elseif($this->type_log == self::TYPE_LOG_REGISTER){
					$define->register_count ++;
				}
				$define->save();
			}
		}
		return parent::afterSave();
	}
}
