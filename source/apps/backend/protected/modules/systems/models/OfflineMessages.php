<?php

/**
 * This is the model class for table "tig_offline_messages".
 *
 * The followings are the available columns in table 'tig_offline_messages':
 * @property string $user_id
 * @property string $list_offline
 */
class OfflineMessages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tig_offline_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('list_offline', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, list_offline', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'list_offline' => 'List Offline',
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
		$criteria->compare('list_offline',$this->list_offline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OfflineMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getOfflineMessages($userid, $username) {
		$offlineMessage = $this->find('user_id = :user_id', array(':user_id'=>$userid));
		if(!$offlineMessage) {
			$offlineMessage = new OfflineMessages();
			$offlineMessage->user_id = $userid;
			$offlineMessage->save(false);
		}
		
		$savedOfflineMessages = $offlineMessage->getSavedOfflineMessages();
		$newOfflineMessages = $offlineMessage->getNewOfflineMessages($username);
		
		$list_offline = implode(',', array_unique(array_merge($savedOfflineMessages, $newOfflineMessages)));
		$offlineMessage->list_offline = $list_offline;
		$offlineMessage->save(false);
		
		return $list_offline;
	}
	
	public function getSavedOfflineMessages() {
		return $this->list_offline ? explode(',', $this->list_offline) : array();
	}
	
	public function getNewOfflineMessages($username) {
		$username = $username . '@' . Yii::app()->params['XMPP']['server'];
		
		$command = Yii::app()->db_tigase->createCommand("SELECT jid_id FROM user_jid WHERE jid = ?");
		$command->bindParam(1, $username, PDO::PARAM_STR);
		$row = $command->queryRow();
		
		$newOfflineMessages = array();
		
		if($row) {
			$query = "SELECT `user_jid`.`jid` FROM `msg_history` LEFT JOIN `user_jid` ON `msg_history`.`sender_uid` = `user_jid`.`jid_id` WHERE receiver_uid = ? GROUP BY sender_uid";
			$command = Yii::app()->db_tigase->createCommand($query);
			$command->bindParam(1, $row['jid_id'], PDO::PARAM_INT);
			$tempNewOfflineMessages = $command->queryAll();
			foreach ($tempNewOfflineMessages as $t) {
				$t = str_replace('@'.Yii::app()->params['XMPP']['server'], '', $t['jid']);
				$newOfflineMessages[] = $t;
			}
		}
		
		return $newOfflineMessages;
	}
	
	public function updateOffline($ownerUserId, $usernameToUpdate, $increase = FALSE) {
		$offlineMessage = $this->find('user_id = :user_id', array(':user_id'=>$ownerUserId));
		if(!$offlineMessage) {
			$offlineMessage = new OfflineMessages();
			$offlineMessage->user_id = $ownerUserId;
			$offlineMessage->save(false);
		}
		
		$savedOfflineMessages = $offlineMessage->getSavedOfflineMessages();
		if($increase)
			$offlineMessage->list_offline = implode(',', array_unique(array_merge($savedOfflineMessages, array($usernameToUpdate))));
		else
			$offlineMessage->list_offline = implode(',', array_diff($savedOfflineMessages, array($usernameToUpdate)));
		$offlineMessage->save(false);
	}
}
