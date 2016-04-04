<?php

/**
 * This is the model class for table "sys_comment".
 *
 * The followings are the available columns in table 'sys_comment':
 * @property integer $id
 * @property string $content
 * @property integer $created_date
 * @property integer $created_by
 * @property integer $approved
 * @property string $type_id
 * @property integer $item_id
 * @property integer $like_count
 * @property integer $photo_id
 */
class Comment extends CActiveRecord
{
    const COMMENT_ACTIVITY = 'ACTIVITY';
    const COMMENT_HOTBOX = 'HOTBOX';
	public $username;
	public $page_size = 5;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
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
		return 'sys_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('created_date, created_by, approved, item_id, like_count, photo_id', 'numerical', 'integerOnly'=>true),
			array('type_id', 'length', 'max'=>255),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content, created_date, created_by, approved, type_id, item_id, like_count, photo_id, tagger_id', 'safe', 'on'=>'search'),
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
			'cmt' => array(self::BELONGS_TO, 'Member', 'created_by'),
			'author' => array(self::BELONGS_TO, 'Member', 'created_by'),
			'photo' => array(self::BELONGS_TO, 'Photo', 'photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'BÃ¬nh luáº­n',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'approved' => 'Approved',
			'type_id' => 'Type',
			'item_id' => 'Item',
			'like_count' => 'Like Count',
			'photo_id' => 'Photo Id'
		);
	}
	
	public function afterDelete(){
		if ($this->type_id == 'ALBUM'){
			$obj = ActivityLog::model()->findAllByAttributes(array('object_id' => $this->id));
			foreach ($obj as $item)
				$item->delete();
		}
		$likes = Like::model()->findAllByAttributes(array('item_id' => $this->id));
		foreach ($likes as $item){
			$item->delete();
		}
		
		if($this->type_id == self::COMMENT_HOTBOX){
			$hotbox_stat = HotboxStats::model()->findByPk($this->item_id);
			if(isset($hotbox_stat) && $hotbox_stat->comment_count > 0){
				$hotbox_stat->comment_count--;
				$hotbox_stat->update();
			}
		}
	}
	
	public function beforeSave(){
		$this->content = htmlspecialchars($this->content);
		if($this->type_id == self::COMMENT_HOTBOX){
			$hotbox_stat = HotboxStats::model()->findByPk($this->item_id);
			if(isset($hotbox_stat)){
				$hotbox_stat->comment_count++;
				$hotbox_stat->update();
			} 
		}
		return parent::beforeSave();
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_date',$this->created_date);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('like_count',$this->like_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getComments($item_id, $type = "ALBUM", $page = 1){
		$criteria=new CDbCriteria;
		//$criteria->alias = "c";
		
		$criteria->with = array(
			'cmt' => array(
				'joinType'=>'INNER JOIN',
				'together'=>true,
   				'alias' => 'c',
			)
		);
		
		$criteria->addCondition('item_id=:item_id');
		$criteria->addCondition('approved=1');
		$criteria->addCondition('type_id=:type_id');
		$criteria->params = array(':item_id' => $item_id, ':type_id' => $type);
		
		
		if ($item_id > 0){
			$totaldata = $this->count($criteria);
			
			$criteria->order = "created_date desc";
			
			/***/
			$pages = new CPagination($totaldata);
			$pages->pageSize = $this->page_size;
			$pages->applyLimit($criteria);
			$pages->setCurrentPage($page - 1);
			$next_page = ($totaldata > $pages->pageSize * $page) ? $page + 1 : 0 ;
			
			/***/
			$data = $this->findAll($criteria);
			if ($data){
			    $data = array_reverse($data);
			}			
			return array('data' => $data, 'next' => $next_page, 'pages' => $pages);
		}else{
			$criteria->order = "created_date";
			return $this->findAll($criteria);
		}
	}
	
	public function getUserComment($item_id, $type = self::COMMENT_ACTIVITY){
		$criteria=new CDbCriteria;
		$criteria->addCondition('item_id=:item_id AND type_id=:type_id');	
		$criteria->params = array(':item_id' => $item_id, ':type_id' => $type);
		$criteria->group = "created_by";
		
		if ($item_id > 0){
			$data = $this->findAll($criteria);
			return $data;
		}
		return false;
	}
	
	public function getContent(){
		return nl2br($this->content);
		//return str_replace('\\n', '<br>', $this->content);
	}
	
	public static function ConfigView(){
		$arr = Setting::getValueConfig('comment_view');
		$value = array('default' => 5, 'view' => 5);
		if ($arr){
			if (isset($arr['default'])) $value['default'] = intval($arr['default']) == 0 ? 5 : intval($arr['default']);
			if (isset($arr['view'])) $value['view'] = intval($arr['view']) == 0 ? 5 : intval($arr['view']);
		}
		return $value;
	}
	
	//get page
	function getDataPagging($criteria, $page){
		$total = $this->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['hotbox_comment_limit'];
		$pages->applyLimit($criteria);
		$pages->setCurrentPage($page);
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ;
		$return = array('next_page' => $next_page, 'data' => $this->findAll($criteria), 'page' => $pages);
		return $return;
		
	}
	
	public function getListComment($item_id, $type_id = self::COMMENT_ACTIVITY, $page){
		$criteria=new CDbCriteria;
		$criteria->addCondition('item_id = :item_id AND type_id = :type_id AND approved = 1');
		$criteria->params = array(':item_id' => $item_id, ':type_id' => $type_id);
		$criteria->order = 't.id ASC';
		return $this->getDataPagging($criteria, $page);
	}
	
	
	public function getLikeHotboxCount(){
		if(isset($this->id)){
			return Like::model()->getCount($this->id, Like::LIKE_COMMENT);
		}
		return 0;
	}
	
	public function isAuthor(){
		if($this->created_by == Yii::app()->user->id){
			return true;
		}
		return false;
	}
}