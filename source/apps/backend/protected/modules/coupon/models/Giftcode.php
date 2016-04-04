<?php

/**
 * This is the model class for table "giftcode".
 *
 * The followings are the available columns in table 'giftcode':
 * @property integer $id
 * @property integer $user_id
 * @property integer $event_id
 * @property string $code
 * @property integer $status
 * @property string $unique
 */
class Giftcode extends CActiveRecord
{	
	public $username;
	const TYPE_SYSTEM = 'System';
	const TYPE_MARKETING = 'Marketing';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Giftcode the static model class
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
		return 'coupon_giftcode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, event_id, status, type', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, event_id, code, status, type, unique', 'safe', 'on'=>'search'),
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
			'event' => array(self::BELONGS_TO, 'Events', 'event_id'),
			'user'  => array(self::BELONGS_TO, 'YumUser', 'user_id'),
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
			'event_id' => 'Event',
			'code' => 'Code',
			'status' => 'Status',
			'type' => 'Type',
			'unique' => 'Unique'
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		$criteria->compare('unique',$this->unique, true);
		
		if(!empty($this->username)) {
			$criteria->with = array(
				'user' => array(
					'alias' => 'u',
					'condition' => 'u.username = \''.$this->username.'\'',
				)
			);			
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getStatus() {
		if($this->status == 0)
			return 'Not yet';
		return 'Used';	
	}
	
	public function getUserName() {
		if(isset($this->user_id)) {
			return $this->user_id;
		}
		return '';
	}
	
	public function saveGiftCode($event_id, $type, $quantity, $formula, $numberOfDigit = false) {
		$result = array();
		for($i=0; $i< $quantity; $i++) {
			$code = $this->generateGiftCode($formula, $event_id, $numberOfDigit);
			$model = new Giftcode();
			$model->user_id = 0;
			$model->type = $type;
			$model->status = 0;
			$model->event_id = $event_id;
			$model->code = $code;
			$model->unique = uniqid();
			$model->save();
			$result[] = $code;
		}
		return $result;
	}
	
	public function generateRandomString($length = 4) {
		//$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	public function generateRandomStringWidthDigLimit($list, $numberOfDigit) {
		$strWithoutHypen = implode('', $list);
		$realLength = strlen($strWithoutHypen);
		$digit = '0123456789';
		$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $realLength; $i++) {
			if($numberOfDigit == 0)
				$randomString .= $alpha[rand(0, strlen($alpha) - 1)];
			else {
				$randomString .= $digit[rand(0, strlen($digit) - 1)];
				$numberOfDigit -= 1;
			}
		}
		$randomString = str_shuffle($randomString);
		
		$pos = 0;
		foreach($list as $k=>$l) {
			if($k < count($list) - 1) {
				$pos += strlen($l);
				$randomString = substr_replace($randomString, '-', $pos, 0);
				$pos += 1;
			}
		}
		return $randomString;
	}
	
	public function generateGiftCode($formula, $event_id, $numberOfDigit = false) {
		
		$list = explode("-", $formula);
		
		if($numberOfDigit !== false && $numberOfDigit != '') {
			$code = $this->generateRandomStringWidthDigLimit($list, $numberOfDigit);
		} else {
			$result = array();
			foreach($list as $item)
				$result[] = $this->generateRandomString(strlen($item));
			$code = implode("-", $result);
		}
		
		$criteria=new CDbCriteria;
		$criteria->compare('event_id', $event_id);
		$criteria->compare('code',$code,true);
		
		if($this->find($criteria)) {
			return $this->generateGiftCode($formula, $event_id);
		}
			
		return $code;	
	}
	
	public function loadGiftCode($event_id, $user_id) {

		if($user_id == 0 )
			return false;
		
		$event = Events::model()->findByPk($event_id);
		$history = History::model()->findByAttributes(array('event_id' => $event_id, 'user_id' => $user_id));
		
		if(!$event || $event->enabled == 0)
			return false;
		if($event->start > time())
			return false;
		if($event->end < time())
			return false;
			
		if($event && $event->end > time())
			$code = Giftcode::model()->findByAttributes(array('event_id' => $event_id, 'user_id' => $user_id));			
		if(empty($code)) {
			$code = Giftcode::model()->findByAttributes(array('event_id' => $event_id, 'user_id' => 0, 'status' => 0, 'type' => 1));
			if(isset($code)) {
				//Update gift code
				$code->status = 1;
				$code->user_id = $user_id;
				$code->update();
				
				//Save history
				$data = new ImportForm();
				$data->event = $event_id;
				$data->code = $code->code;
				if(isset($history)) {
					$list = json_decode($history->content);
					array_push($list, $data->attributes);
					$history->content = json_encode($list);
					$history->update();
				}
				else {
					$history = new History();
					$history->user_id = $user_id;
					$history->event_id = $event_id;
					$history->content = json_encode(array($data->attributes));
					$history->created = time();
					$history->save();
				}
			}
		}	
		return $code;			
	}
	
	public function useGiftcode($user_id, $unique){
		if(!empty($user_id) && !empty($unique)){
			$criteria=new CDbCriteria;
			$criteria->addCondition("`unique` = :unique");
			$criteria->params = array(':unique' => $unique);
			Giftcode::model()->updateAll(array('status'=>'1', 'user_id' => $user_id), $criteria);
			return true;
		}
	}
	
}