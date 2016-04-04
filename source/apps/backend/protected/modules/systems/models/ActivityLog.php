<?php

/**
 * This is the model class for table "activities".
 *
 * The followings are the available columns in table 'activities':
 * @property integer $id
 * @property string $user_id
 * @property string $user_name
 * @property integer $action
 * @property string $message
 * @property string $params
 * @property string $ip_address
 * @property integer $timestamp
 * @property integer $object_id
 * @property integer @owner_id
 * @property string @owner_name
 * @property integer @group_id
 */
class ActivityLog extends ActivityActiveRecord
{
	
	public $date;
	public $quantity; 
	public $is_like;
	public $is_feed = false;
	const STATUS_DELETE = 0;
	const STATUS_ENABLED = 1;
	
	public function getDbConnection()
    {
        return self::getDbLogConnection();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActivityLog the static model class
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
		return 'activities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action, timestamp, object_id, owner_id, date_last_update', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>10),
			array('user_name', 'length', 'max'=>255),
			array('ip_address', 'length', 'max'=>32),
			array('message, params, object_id, owner_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, user_name, action, message, params, ip_address, timestamp, date_last_update', 'safe', 'on'=>'search'),
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
			'member' => array(self::BELONGS_TO, 'Member', 'user_id'),
			'owner' => array(self::BELONGS_TO, 'Member', 'owner_id'),
			'stats' => array(self::HAS_ONE, 'ActivityLogStats', 'log_id'),
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
			'user_name' => 'User Name',
			'action' => 'Action',
			'message' => 'Message',
			'params' => 'Params',
			'ip_address' => 'Ip Address',
			'timestamp' => 'Timestamp',
		);
	}
	
	public function afterDelete(){
		ActivityLogStats::model()->deleteAllByAttributes(array('log_id' => $this->id));
		Comment::model()->deleteAllByAttributes(array('item_id' => $this->id));
		Like::model()->deleteAllByAttributes(array('item_id' => $this->id));
	}
	
	public function afterSave(){
	    $oldID = $this->id;
	    if(!empty($oldID) && $this->status == 0 && $this->group_id == 0){
	        $criteria = new CDbCriteria();
	        $criteria->condition = "group_id = :id AND status = 1";
	        $criteria->params = array(':id'=>$oldID);
	        $criteria->order = "id DESC";
	        $newRc = $this->find($criteria);
	        if(!empty($newRc)){
	            $this->group_id = $newRc->id;
	            $this->save();
	            $newRc->group_id = 0;
	            $newRc->save();
	            
	            $criteria2=new CDbCriteria;
	            $criteria2->addCondition("group_id = :group_id");
	            $criteria2->params = array(':group_id' => $oldID);
	            $this->updateAll(array('group_id'=>$newRc->id), $criteria2);
	        }
	    }
	    return parent::afterSave();
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('action',$this->action);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('timestamp',$this->timestamp);
		
		$criteria->order = 'timestamp DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getActivitiesCriteria($user_id, $limit = 10) {
	    /*
	     * get activity friend
	     */
	    preg_match("/dbname=([^;]*)/", Yii::app()->db->connectionString, $wmatches);
	    $webDbName = $wmatches[1];
	    preg_match("/dbname=([^;]*)/", Yii::app()->db_activity->connectionString, $amatches);
	    $activityDbName = $amatches[1];
	    
	    $Elasticsearch	=	new Elasticsearch();
	    $notification			=	$Elasticsearch->load($user_id);
	    
	    if(Yii::app()->user->id == $user_id && $this->is_feed == true && !empty($notification['friendlist'])){
	        $inList = $notification['friendlist'];
	    }else{
	        $inList = $user_id;
	    }
	    
	    $condition = "(user_id != 0 AND owner_id != 0) AND (user_id IN ($inList) OR owner_id IN ($inList) ) AND t.group_id = 0 AND t.status = 1";
// 	    $condition .= " AND (CASE
// 	    WHEN `action` IN ('1') THEN 1
// 	    WHEN `action` = '2' THEN (SELECT IF(EXISTS(SELECT b.`status` FROM `$webDbName`.`sys_photo` b WHERE (b.id IN (SELECT object_id FROM `activities`  WHERE group_id = t.id) OR b.id = t.object_id) AND `status` = 1), 1, 0))	    
// 	    ELSE '0' END)";
	    
	    
		$criteria=new CDbCriteria;
		$criteria->select = 't.*, FROM_UNIXTIME(t.timestamp, \'%d-%m-%Y\') as date, (Select count(*) From activities Where group_id = t.id) as quantity';
		$criteria->addCondition($condition);
		$criteria->limit = $limit;
		$criteria->order = 'timestamp DESC, date_last_update DESC';
		//$criteria->group = 't.action, t.object_id, date';
		return $criteria;
	}
		
	public function searchActivitiesByUserPagging($limit = 10, $offset = 0) {
		$criteria = $this->getActivitiesCriteria($this->user_id, $limit);
		$total_newsfeed    = $this->count($criteria);
		$data = array('data' => null, 'end' => true);
		
		$criteria->offset = $offset;
        $criteria->limit    =   $limit;
                
		$data['data'] = $this->findAll($criteria);
        $data['total']  =   isset($total_newsfeed)    ?   $total_newsfeed   :   0;
		
		return $data;
	}
	
	public function getLikeState(){
		$params = json_decode($this->params, true);
		if ($this->is_like){
			return Lang::t("newsfeed", 'Unlike');
		}
		return Lang::t("newsfeed", 'Like');
	}
	
	public function getLikeCount(){
		$params = json_decode($this->params, true);
		$tmp = ActivityLogStats::model()->findByAttributes(array('log_id' => $this->id));
			
		if ($tmp){
			return $tmp->like_count;
		}
		return 0;
	}
	
	public function getCommentCount(){
		$tmp = ActivityLogStats::model()->findByAttributes(array('log_id' => $this->id));
		if ($tmp){
			return $tmp->comment_count;
		}
		return 0;
	}
	
	public function getPhotos($limit = 5){
		$criteria = new CDbCriteria;
		$criteria->addCondition('group_id = :group_id');
		$criteria->params = array(':group_id' => $this->id);
		$criteria->limit = $limit;
		$criteria->order = "timestamp";
		return $this->findAll($criteria);
	}
}