<?php

/**
 * This is the model class for table "table42_dating_request".
 *
 * The followings are the available columns in table 'table42_dating_request':
 * @property string $id
 * @property integer $inviter_id
 * @property integer $friend_id
 * @property integer $status
 * @property integer $requesttime
 * @property integer $updatetime
 * @property integer $is_dating
 * @property integer $vote_total
 * @property integer $view_total
 */
class Table42DatingRequest extends EventActiveRecord
{
	const STATUS_NONE = 0; 
	const STATUS_REQUEST = 1;
	const STATUS_ACCEPTED = 2;
	const STATUS_REJECTED = 3;
	public $total = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_dating_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inviter_id, friend_id, status', 'required'),
			array('inviter_id, friend_id, status, requesttime, updatetime, is_dating, vote_total, view_total, user_make_dating, round_id, is_win', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, inviter_id, friend_id, status, requesttime, updatetime, is_dating, vote_total, view_total, user_make_dating, round_id, is_win', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
	    $relations['inviter'] = array(self::BELONGS_TO, 'Table42Profile', 'inviter_id');
	    $relations['friend'] = array(self::BELONGS_TO, 'Table42Profile', 'friend_id');
	    $relations['round'] = array(self::BELONGS_TO, 'Table42Round', 'round_id');
	    
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
			'inviter_id' => 'Inviter',
			'friend_id' => 'Friend',
			'status' => 'Status',
			'requesttime' => 'Requesttime',
			'updatetime' => 'Updatetime',
			'is_dating' => 'Is Dating',
			'vote_total' => 'Vote Total',
			'view_total' => 'Vote View',
			'user_make_dating' => 'User Make Dating',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('inviter_id',$this->inviter_id);
		$criteria->compare('friend_id',$this->friend_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('requesttime',$this->requesttime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('is_dating',$this->is_dating);
		$criteria->compare('vote_total',$this->vote_total);
		$criteria->compare('view_total',$this->view_total);
		$criteria->compare('is_win',$this->is_win);
		$criteria->compare('user_make_dating',$this->user_make_dating);
		$criteria->compare('round_id',$this->round_id);
		$criteria->addCondition('is_dating = 1');
		$criteria->order = "updatetime DESC";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function win()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id,true);
		$criteria->compare('inviter_id',$this->inviter_id);
		$criteria->compare('friend_id',$this->friend_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('requesttime',$this->requesttime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('is_dating',$this->is_dating);
		$criteria->compare('vote_total',$this->vote_total);
		$criteria->compare('view_total',$this->view_total);
		$criteria->compare('user_make_dating',$this->user_make_dating);
		$criteria->compare('round_id',$this->round_id);
		$criteria->addCondition('is_win = 1');
		$criteria->order = "round_id DESC";
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	public function topvote()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id,true);
		$criteria->compare('inviter_id',$this->inviter_id);
		$criteria->compare('friend_id',$this->friend_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('requesttime',$this->requesttime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('is_dating',$this->is_dating);
		$criteria->compare('vote_total',$this->vote_total);
		$criteria->compare('view_total',$this->view_total);
		$criteria->compare('is_win',$this->is_win);
		$criteria->compare('user_make_dating',$this->user_make_dating);
		$criteria->compare('round_id',$this->round_id);
		$criteria->order = "vote_total DESC";
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42DatingRequest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getFriendShip($inviter_id, $friend_id){
		$criteria=new CDbCriteria;
		$criteria	=	$criteria->addCondition("(inviter_id = {$inviter_id} AND friend_id =  {$friend_id}) OR (inviter_id = {$friend_id} AND friend_id = {$inviter_id})");
		$row		=	$this->find($criteria);
		if($row){
			return $row;
		}else{
			return false;
		}
	}
	public function getUserMakeDating($user_id){
		$criteria=new CDbCriteria;
		$criteria	=	$criteria->addCondition("user_make_dating = {$user_id}");
		$row		=	$this->find($criteria);
		if($row){
			return $row;
		}else{
			return false;
		}		
	}
}
