<?php

/**
 * This is the model class for table "vip_member".
 *
 * The followings are the available columns in table 'vip_member':
 * @property string $user_id
 * @property string $date_start
 * @property integer $date_expire
 * @property integer $created
 * @property integer $last_update
 * @property integer $status
 */
class VipMember extends CandyActiveRecord
{
	const STATUS_ACTIVE		=	1;
	const STATUS_INACTIVE	=	0;
	/**
	 * @see CActiveRecord::getDbConnection()
	 */
	public function getDbConnection()
	{
		return self::getDbLogConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vip_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_expire, created, last_update, status', 'numerical', 'integerOnly'=>true),
			array('date_start', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, date_start, date_expire, created, last_update, status', 'safe', 'on'=>'search'),
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
			'user' => array(self::HAS_ONE, 'Member', 'user_id'),
			'setting' => array(self::HAS_ONE, 'UsrProfileSettings', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'date_start' => 'Date Start',
			'date_expire' => 'Date Expire',
			'created' => 'Created',
			'last_update' => 'Last Update',
			'status' => 'Status',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_expire',$this->date_expire);
		$criteria->compare('created',$this->created);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CandyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VipMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkExpireVipMember(){
		$time	=	time();
		$search	=	new Elasticsearch();
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("date_expire <= $time AND status = " . VipMember::STATUS_ACTIVE);
		
		$data	=	$this->findAll($criteria);
		foreach ($data AS $row){
			$usr_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => $row->user_id));
			
			$model_member = Member::model()->findByAttributes(array('id' => $row->user_id));
			
			//update index search
			$search->update(
					array('is_vip' => '0'), 
					$usr_setting->country_id, 
					$row->user_id
			);
			//update user tables			
			$model_member->is_vip = 0;
			$model_member->save();
			echo "Update UserID #" . $row->user_id . "\n";	
		}
		//update vip member
		$this->updateAll(array('status'=> VipMember::STATUS_INACTIVE, 'last_update' => time()), "date_expire <= $time AND status = " . VipMember::STATUS_ACTIVE);
		echo "The End" . "\n";
	}
	public function upgradeVip($user_id, $quantity, $unit = 'months'){
		$usr_vip = $this->findByAttributes(array('user_id' => $user_id));
		$current_time				=	mktime (date("H"), 0, 0, date("n"), date("j"), date("Y"));
		
		if($usr_vip){
			if($usr_vip->status){
				$usr_vip->date_expire		=	strtotime("+{$quantity} {$unit}", $usr_vip->date_expire);
			}else{
				$usr_vip->date_start		=	$current_time;
				$usr_vip->date_expire		=	strtotime("+{$quantity} {$unit}", $current_time);
			}
			$usr_vip->status			=	VipMember::STATUS_ACTIVE;
			$usr_vip->last_update		=	time();
			$usr_vip->save();
		}else{		
			$usr_vip					=	new VipMember();
			$usr_vip->user_id			=	$user_id;
			$usr_vip->created			=	time();
			$usr_vip->date_start		=	$current_time;
			$usr_vip->date_expire		=	strtotime("+{$quantity} {$unit}", $current_time);		
			$usr_vip->last_update		=	0;
			$usr_vip->status			=	VipMember::STATUS_ACTIVE;
			$usr_vip->save();
		}
		//update vip 
		
		$model_member = Member::model()->findByAttributes(array('id' => $user_id));
		$model_member->is_vip = 1;
		$model_member->save();
		
		//update index search
		$search	=	new Elasticsearch();
		$usr_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user_id));
		$search->update(
				array('is_vip' => '1'),
				$usr_setting->country_id,
				$user_id
		);
		
	}
}
