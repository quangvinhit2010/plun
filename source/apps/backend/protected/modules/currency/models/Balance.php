<?php

/**
 * This is the model class for table "cr_balance".
 *
 * The followings are the available columns in table 'cr_balance':
 * @property integer $id
 * @property integer $user_id
 * @property string $candy
 */
class Balance extends CandyActiveRecord
{
	const TYPE_PRIVATE_PHOTO = 1;
	const TYPE_UPDATE_VIP = 2;
	const TYPE_TRANSFER = 3;
	const TYPE_GET_FREE = 4;
	
	const ACTION_TYPE_USE = 1;
	const ACTION_TYPE_RECEIVE = 2;
	
	const ERROR_CHARGE_FEE = 1;
	const ERROR_NOT_ENOUGHT = 2;
	const ERROR_SQL = 3;
	
	private $errorCode = 0;
	public $new_transaction = 0;
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
		return 'cr_balance';
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
			array('candy', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, candy', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'candy' => 'Candy',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('candy',$this->candy,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function transfer($senderId, $receiverId, $objectId, $type, $candyAmount, $chargeId, $description = NULL) {
		$candyCurrent = array('sender' => $candyAmount, 'receiver' => $candyAmount);
		if($chargeId) {
			$candyCurrent = Charge::model()->CalCandyCurrent($chargeId, $candyAmount);
			if(!$candyCurrent) {
				$this->errorCode = self::ERROR_CHARGE_FEE;
				return false;
			}
		}
		
		if($senderId) {
			$senderBalance = $this->getBalance($senderId);
			if($senderBalance->candy < $candyCurrent['sender']) {
				$this->errorCode = self::ERROR_NOT_ENOUGHT;
				return false;
			}
		}
			
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$now = time();
			
			if($senderId) {
				$candyUse = new CandyUse();
				$candyUse->user_id = $senderId;
				$candyUse->object_type = $type;
				$candyUse->candy_amount = $candyAmount;
				$candyUse->candy_current = $candyCurrent['sender'];
				$candyUse->charge_id = $chargeId;
				$candyUse->created = $now;
				$candyUse->description = ($description) ? $description : NULL;
				$candyUse->object_id = ($objectId) ? $objectId : 0;
				
				$metaData = array();
				if($receiverId) {
					$receiverUser = Member::model()->find('id = :id', array(':id'=>$receiverId));
					$metaData['userId'] = $receiverId;
					$metaData['username'] = $receiverUser['username'];
					$metaData['avatar'] = $receiverUser->getAvatar();
				}
				if($type == self::TYPE_PRIVATE_PHOTO) {
					$privatePhotoUrl = Photo::model()->find('id = :id', array(':id'=>$objectId))->getImageLarge(true);
					$metaData['privatePhotoUrl'] = $privatePhotoUrl;
				}
				$metaData = json_encode($metaData);
				$candyUse->meta_data = $metaData;
				
				$candyUse->save(false);
				
				$candyHistoryUse = new CandyHistory();
				$candyHistoryUse->user_id = $senderId;
				$candyHistoryUse->object_type = $type;
				$candyHistoryUse->action_id = $candyUse->id;
				$candyHistoryUse->action_type = self::ACTION_TYPE_USE;
				$candyHistoryUse->candy_amount = $candyUse->candy_amount;
				$candyHistoryUse->candy_current = $candyUse->candy_current;
				$candyHistoryUse->charge_id = $chargeId;
				$candyHistoryUse->created = $now;
				$candyHistoryUse->description = ($description) ? $description : NULL;
				$candyHistoryUse->object_id = ($objectId) ? $objectId : 0;
				$candyHistoryUse->meta_data = $metaData;
				
				$candyHistoryUse->save(false);
				
				$senderBalance->candy -= $candyCurrent['sender'];
				$senderBalance->save();
			}
			
			if($receiverId) {
				$CandyReceive = new CandyReceive();
				$CandyReceive->user_id = $receiverId;
				$CandyReceive->object_type = $type;
				$CandyReceive->candy_amount = $candyAmount;
				$CandyReceive->candy_current = $candyCurrent['receiver'];
				$CandyReceive->charge_id = $chargeId;
				$CandyReceive->created = $now;
				$CandyReceive->description = ($description) ? $description : NULL;
				$CandyReceive->object_id = ($objectId) ? $objectId : 0;
				
				$metaData = array();
				if($senderId) {
					$senderUser = Member::model()->find('id = :id', array(':id'=>$senderId));
					$metaData['userId'] = $senderId;
					$metaData['username'] = $senderUser['username'];
					$metaData['avatar'] = $senderUser->getAvatar();
				}
				if($type == self::TYPE_PRIVATE_PHOTO) {
					$metaData['privatePhotoUrl'] = $privatePhotoUrl;
				}
				$metaData = json_encode($metaData);
				$CandyReceive->meta_data = $metaData;
				
				$CandyReceive->save(false);
				
				$candyHistoryReceive = new CandyHistory();
				$candyHistoryReceive->user_id = $receiverId;
				$candyHistoryReceive->object_type = $type;
				$candyHistoryReceive->action_id = $CandyReceive->id;
				$candyHistoryReceive->action_type = self::ACTION_TYPE_RECEIVE;
				$candyHistoryReceive->candy_amount = $CandyReceive->candy_amount;
				$candyHistoryReceive->candy_current = $CandyReceive->candy_current;
				$candyHistoryReceive->charge_id = $chargeId;
				$candyHistoryReceive->created = $now;
				$candyHistoryReceive->description = ($description) ? $description : NULL;
				$candyHistoryReceive->object_id = ($objectId) ? $objectId : 0;
				$candyHistoryReceive->meta_data = $metaData;
				
				$candyHistoryReceive->save(false);
				
				$receiverBalacne = $this->getBalance($receiverId);
				$receiverBalacne->candy += $candyCurrent['receiver'];
				$receiverBalacne->new_transaction += 1;
				$receiverBalacne->save();
			}
		
			$transaction->commit();
			
			return true;
		} catch(Exception $e) {
			$this->errorCode = self::ERROR_SQL;
			$transaction->rollback();
			return false;
		}
	}
	
	public function getBalance($userId) {
		$balanceModel = new Balance();
		$balance      = $balanceModel->find('user_id = :user_id', array(':user_id'=>$userId));
		if($balance)
			return $balance;
		else {
			$balanceModel->user_id = $userId;
			$balanceModel->save();
			return $balanceModel;
		}
	}
	public function getErrorCode() {
		$errors = array(
			self::ERROR_CHARGE_FEE => 'Candy giao dịch phải lớn hơn phí thu !',
			self::ERROR_NOT_ENOUGHT => 'Không đủ candy để thực hiện giao dịch',
			self::ERROR_SQL => 'Gặp lỗi trong quá trình thực hiện giao dịch',
		);
		$errorCode = $this->errorCode;
		$this->errorCode = 0;
		return $errors[$errorCode];
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CandyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Balance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
