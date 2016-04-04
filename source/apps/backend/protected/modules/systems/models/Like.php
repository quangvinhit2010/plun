<?php

/**
 * This is the model class for table "sys_like".
 *
 * The followings are the available columns in table 'sys_like':
 * @property integer $id
 * @property integer $item_id
 * @property integer $like_by
 * @property integer $like_date
 * @property string $type_id
 */
class Like extends CActiveRecord
{
    const LIKE_ACTIVITY = 'ACTIVITY';
    const LIKE_HOTBOX = 'HOTBOX';
    const LIKE_COMMENT = 'COMMENT';
	/**
	 * Returns the static model of the specified AR class.
	 * @return Like the static model class
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
		return 'sys_like';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, like_by, like_date', 'numerical', 'integerOnly'=>true),
			array('type_id', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, item_id, like_by, like_date, type_id', 'safe', 'on'=>'search'),
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
	        'user' => array(self::BELONGS_TO, 'Member', 'like_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_id' => 'Item',
			'like_by' => 'Like By',
			'like_date' => 'Like Date',
			'type_id' => 'Type',
		);
	}
	
	public function afterDelete(){
		$obj = ActivityLog::model()->findAllByAttributes(array('object_id' => $this->id));
		foreach ($obj as $item) {
			$item->delete();
		}
		
		if($this->type_id == self::LIKE_HOTBOX){
			$hotbox_stat = HotboxStats::model()->findByPk($this->item_id);
			if(isset($hotbox_stat)){
				$hotbox_stat->like_count--;
				$hotbox_stat->update();
			}
		}
		return parent::afterDelete();
	}
	
	public function afterSave(){
		if($this->type_id == self::LIKE_HOTBOX){
			$hotbox_stat = HotboxStats::model()->findByPk($this->item_id);
			if(isset($hotbox_stat)){
				$hotbox_stat->like_count++;
				$hotbox_stat->update();
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
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('like_by',$this->like_by);
		$criteria->compare('like_date',$this->like_date);
		$criteria->compare('type_id',$this->type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function callLike($id, $type, $user_id){
		if ($type == "album"){
			$tmp = Album::model()->findByPk($id);
			if (!$tmp) return false;
		}
		
		$arr = array('total' => 0, 'action' => '', 'id' => '', 'like_id' => 0);
		$ob = Like::model()->findByAttributes(array('item_id' => $id, 'like_by' => $user_id, 'type_id' => $type));
		if ($ob){
			$arr['action'] = Lang::t("newsfeed", 'Like');
			$ob->delete();
		}else{
			$ob = new Like();
			$ob->item_id = $id;
			$ob->like_by = $user_id;
			$ob->like_date = time();
			$ob->type_id = $type;
			if ($ob->save()){
				$arr['action'] = Lang::t("newsfeed", 'Unlike');
				$arr['like_id'] = $ob->id;
			}
		}
		
		$totalLike = Like::model()->countByAttributes(array('item_id' => $id, 'type_id' => $type));
		
		if ($type == "album"){
			$stats = AlbumStats::model()->findByAttributes(array('album_id' => $id));
			if ($stats){
				$stats->like_count = $totalLike;
				$stats->save();
			}else{
				$stats = new AlbumStats();
				$stats->like_count = $totalLike;
				$stats->album_id = $id;
				$stats->save();
			}
		}elseif ($type == "comment"){
			$cmt = Comment::model()->findByAttributes(array('id' => $id));
			if ($cmt){
				$cmt->like_count = $totalLike;
				$cmt->save();
			}
		}elseif ($type == Like::LIKE_ACTIVITY){
			$tmp = ActivityLogStats::model()->findByAttributes(array('log_id' => $id));
			if ($tmp){
				$tmp->like_count = $totalLike;
				$tmp->save();
			}else{
				$tmp = new ActivityLogStats();
				$tmp->like_count = $totalLike;
				$tmp->log_id = $id;
				$tmp->save();
			}
		} 
		$arr['total'] = $totalLike;
		$arr['id'] = Util::encrypt($id);
		
		return $arr;
	}
	
	
	public function isLike($id, $type, $user_id){
		if(isset($id) && isset($type) && isset($user_id)){
			$is_like = Like::model()->findByAttributes(array('item_id' => $id, 'like_by' => $user_id, 'type_id' => $type));
			if(isset($is_like)){
				return $is_like;
			}
			return false;
		}
	}
	
	public function getCount($id, $type){
		if(isset($id) && isset($type)){
			return Like::model()->count('item_id = :item_id AND type_id = :type_id', array('item_id' => $id, 'type_id' => $type));
		} 
		return 0;
	}
}