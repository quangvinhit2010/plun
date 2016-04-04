<?php

/**
 * This is the model class for table "purpleguy_profile".
 *
 * The followings are the available columns in table 'purpleguy_profile':
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $fullname
 * @property string $phone
 * @property string $email
 * @property integer $thumbnail_id
 * @property integer $status
 */
class PurpleguyProfile extends EventActiveRecord
{
	// status cua user tham gia nam trong bang purpleguy_profile
	const FAIL = 0; // user bi admin tu choi tham gia
	const WAIT = 1; // user cho approve cua admin
	const PASS = 2; // user duoc chap nhan tham gia
	public $totalVote = 0;
	public $totalComment = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purpleguy_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, username, thumbnail_id, fullname, phone, email', 'required'),
			array('user_id, thumbnail_id, round_win, round_next, status', 'numerical', 'integerOnly'=>true),
			array('phone', 'numerical'),
			array('username', 'length', 'max'=>20),
			array('fullname, email', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, username, fullname, phone, email, thumbnail_id, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
	    $relations['photo'] = array(self::BELONGS_TO, 'PurpleguyPhoto', 'thumbnail_id');
	    $relations['photos'] = array(self::HAS_MANY, 'PurpleguyPhoto', array('user_id'=>'user_id'), 'condition' => 'status=1');
	    $relations['user'] = array(self::BELONGS_TO, 'Member', 'user_id');
	    return CMap::mergeArray(
	            $relations,
	            parent::relations()
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
			'username' => 'Username',
			'fullname' => Lang::t('general', 'Fullname'),
			'phone' => Lang::t('general', 'Phone'),
			'email' => 'Email',
			'thumbnail_id' => 'Thumbnail',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('thumbnail_id',$this->thumbnail_id);
		$criteria->compare('status',$this->status);
		$criteria->order = "id DESC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_event;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurpleguyProfile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getRandomUsers($round_id) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'LEFT JOIN purpleguy_user_vote ON t.user_id = purpleguy_user_vote.user_id';
		$criteria->compare('purpleguy_user_vote.round_id',$round_id);
		$criteria->compare('t.status',PurpleguyProfile::PASS);
		$criteria->order = 'RAND()';
		$criteria->limit = Yii::app()->params->purpleguy['limit_on_home'];
		
		$purpleGuyProfiles = $this->findAll($criteria);
		
		return $purpleGuyProfiles;
	}
	
	public function getUsersInRound($order_by, $page, $round_id, $s) {
	
		$offset = ($page-1)*Yii::app()->params->purpleguy['limit_per_page'];
		
		$criteria = new CDbCriteria();
		$criteria->select = 't.*, purpleguy_user_vote.total_vote as totalVote, purpleguy_user_vote.total_comment as totalComment';
		$criteria->join = 'LEFT JOIN purpleguy_user_vote ON t.user_id = purpleguy_user_vote.user_id';
		
		if($s) {
			$criteria->addSearchCondition('t.fullname',$s, true);
			$criteria->addSearchCondition('t.user_id',$s, true, 'OR');
		}
		
		$criteria->compare('purpleguy_user_vote.round_id',$round_id);
		$criteria->compare('t.status',PurpleguyProfile::PASS);
		if($order_by == '$order_by')
			$criteria->order = $order_by.' DESC';
		else
			$criteria->order = $order_by.' DESC, created DESC';
		$criteria->limit = Yii::app()->params->purpleguy['limit_per_page'];
		$criteria->offset = $offset;
		
		$purpleGuyProfiles = $this->findAll($criteria);
		
		return $purpleGuyProfiles;
	}
	
	public function getUserInRound($user_id, $round_id) {
	
		$criteria = new CDbCriteria();
		$criteria->select = 't.*, purpleguy_user_vote.total_vote as totalVote';
		$criteria->join = 'LEFT JOIN purpleguy_user_vote ON t.user_id = purpleguy_user_vote.user_id';
		$criteria->compare('t.user_id',$user_id);
		$criteria->compare('purpleguy_user_vote.round_id',$round_id);
		$criteria->compare('t.status',PurpleguyProfile::PASS);
	
		$purpleGuyProfile = $this->find($criteria);
	
		return $purpleGuyProfile;
	}
	


	public function getTopVoteUsers($round_id) {
		
		$criteria = new CDbCriteria();
		$criteria->select = 't.*, purpleguy_user_vote.total_vote as totalVote';
		$criteria->join = 'LEFT JOIN purpleguy_user_vote ON t.user_id = purpleguy_user_vote.user_id';
		$criteria->compare('purpleguy_user_vote.round_id',$round_id);
		$criteria->compare('t.status',PurpleguyProfile::PASS);
		$criteria->order = 'total_vote DESC, created DESC';
		$criteria->limit = Yii::app()->params->purpleguy['limit_top_vote'];
		
		$purpleGuyProfiles = $this->findAll($criteria);
		
		return $purpleGuyProfiles;
	}
	
	public function checkVoted($vote_by, $round_id) {
		$purpleGuyVote = new PurpleguyVote();
		$voted = $purpleGuyVote->find('user_id = :user_id AND vote_by = :vote_by AND round_id = :round_id', array(':user_id'=>$this->user_id, ':vote_by'=>$vote_by, ':round_id'=>$round_id));
		return $voted;
	}
	
	public function getStatusName() {
	    switch ($this->status){
	        case 0:
	            echo 'Fail';
	            break;
	        case 1:
	            echo 'Wait';
	            break;
	        case 2:
	            echo 'Pass';
	            break;
	    }
	}
}
