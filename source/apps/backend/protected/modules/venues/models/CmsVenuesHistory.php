<?php

/**
 * This is the model class for table "cms_venues_history".
 *
 * The followings are the available columns in table 'cms_venues_history':
 * @property string $id
 * @property integer $venue_id
 * @property integer $date_created
 * @property integer $user_id
 * @property string $ip
 */
class CmsVenuesHistory extends CActiveRecord
{
	const VISITOR_TYPE_CHECKIN = 1;
	const VISITOR_TYPE_ISU = 2;
	
	public $total;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_venues_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('venue_id, date_created, user_id, type', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, venue_id, date_created, user_id, ip, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'member' => array(self::BELONGS_TO, 'Member', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'venue_id' => 'Venue',
			'date_created' => 'Date Created',
			'user_id' => 'User',
			'ip' => 'Ip',
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
		$criteria->compare('venue_id',$this->venue_id);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsVenuesHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotalVisit($venue_id){
		$criteria=new CDbCriteria;
		$criteria->group	=	'user_id';
		$criteria->addCondition("venue_id = {$venue_id}");
		
		return $this->count($criteria);
	}
	public function getVisit($venue_id, $offset = 0, $limit = 20){
		$criteria=new CDbCriteria;
	
		$criteria->group	=	'user_id';
		$criteria->limit	=	$limit;
		$criteria->offset	=	$offset;
		$criteria->addCondition("venue_id = {$venue_id}");
		$data	= $this->findAll($criteria);
		
		//get total count
		$CDbCriteria = new CDbCriteria();
		$CDbCriteria->select =   'COUNT(DISTINCT user_id) AS total';
		$CDbCriteria->alias =   's';
		$CDbCriteria->addCondition("venue_id = {$venue_id}");
		$total  =   $this->find($CDbCriteria);
		$total_visit  =   isset($total->total)    ?   $total->total   :   0;
		
		$uid	=	array();
		if($data){
			foreach($data AS $row){
				$uid[]	=	$row->user_id;
			}
		}
		
		return array('data' => $data, 'total' => $total_visit, 'uid' => $uid);
	}
}
