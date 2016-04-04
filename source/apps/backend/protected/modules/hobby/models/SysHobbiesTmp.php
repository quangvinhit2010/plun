<?php

/**
 * This is the model class for table "sys_hobbies_tmp".
 *
 * The followings are the available columns in table 'sys_hobbies_tmp':
 * @property integer $id
 * @property string $content
 * @property integer $date_created
 * @property integer $user_id
 */
class SysHobbiesTmp extends EMongoDocument
{
	public $content		=	null;
	public $date_created	=	null;
	public $user_id		=	null;
	
    public function getCollectionName()
    {
       return 'hobbies_tmp';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, date_created, user_id', 'required'),
			array('date_created, user_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('content, date_created, user_id', 'safe', 'on'=>'search'),
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
			'content' => 'Content',
			'date_created' => 'Date Created',
			'user_id' => 'User',
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

		$criteria->compare('content',$this->content,true);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function collectHobbies($hobby, $user_id){
		if(!is_array($hobby)){
			$hobby	=	array($hobby);
		}
		foreach($hobby AS $v){
			$hobby	=	new SysHobbiesTmp();
			$hobby->content	=	$v;
			$hobby->date_created	=	time();
			$hobby->user_id	=	$user_id;
			$hobby->save();
		}
		
	}	
}
