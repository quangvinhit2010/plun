<?php

/**
 * This is the model class for table "usr_bookmark".
 *
 * The followings are the available columns in table 'usr_bookmark':
 * @property integer $id
 * @property integer $user_id
 * @property integer $target_id
 * @property integer $created
 */
class Bookmark extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usr_bookmark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, target_id, created', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, target_id, created', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Member', 'target_id'),
			'profile_settings' => array(self::BELONGS_TO, 'UsrProfileSettings', 'target_id'),
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
			'target_id' => 'Target',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('target_id',$this->target_id);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bookmark the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//get page
	function getDataPagging($criteria, $index, $limit){
		$data = array('data' => null, 'total' => 0, 'index' => $index, 'limit' => $limit, 'ismore' => false);
		$data['total'] = $this->count($criteria);
	
		if ($index > 0)
			//$criteria->offset = $index * $limit;
			$criteria->offset = $index;
		$criteria->limit = $limit;
		$data['data'] = $this->findAll($criteria);
		if ((intval($index) + 1) * intval($limit) < $data['total'] && $data['total'] > 0 )
			$data['ismore'] = true;
	
		return $data;
	}
	
	public function getBookmark($user_id, $offset = 0, $limit = 12){
		$criteria=new CDbCriteria;
		$criteria->addCondition('user_id = :user_id');
		$criteria->params = array(':user_id' => $user_id);
		$criteria->order = 't.created DESC';
		return $this->getDataPagging($criteria, $offset, $limit);
	}
	
	public function checkBookmark($target_id){
		if(isset(Yii::app()->user->id) && $target_id){
			$result = Bookmark::model()->exists('user_id = :user_id AND target_id = :target_id', array(':user_id' => Yii::app()->user->id, 'target_id' => $target_id));
			if($result){
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}
