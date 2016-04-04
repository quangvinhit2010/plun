<?php

/**
 * This is the model class for table "sys_hotbox_photo".
 *
 * The followings are the available columns in table 'sys_hotbox_photo':
 * @property integer $id
 * @property integer $hotbox_id
 * @property string $title
 * @property string $description
 * @property string $name
 * @property string $path
 * @property integer $status
 * @property integer $sort
 * @property integer $created
 */
class HotboxPhoto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_hotbox_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hotbox_id, status, sort, created', 'numerical', 'integerOnly'=>true),
			array('title, description, name, path', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hotbox_id, title, description, name, path, status, sort, created', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'hotbox_id' => 'Hotbox',
			'title' => 'Title',
			'description' => 'Description',
			'name' => 'Name',
			'path' => 'Path',
			'status' => 'Status',
			'sort' => 'Sort',
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
		$criteria->compare('hotbox_id',$this->hotbox_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotboxPhoto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getImageThumb($htmlOptions = array(), $is_url = false){
		$params = CParams::load ();
		
		if(empty($this->name))
			$image = Yii::app()->createUrl(Yii::app()->theme->baseUrl .'/resources/css/images/img-news.jpg');
		else{			
			$image = "http://{$params->params->img_webroot_url}/{$this->path}/thumb300x0/{$this->name}";
			
		}
		if ($is_url) {
			return $image;
		}else{
			return CHtml::image($image, $this->id, $htmlOptions);
		}
	}
	
	public function getImageDetail($htmlOptions = array(), $is_url = false){
		$params = CParams::load ();
		
		
		if(empty($this->name))
			$image = Yii::app()->createUrl(Yii::app()->theme->baseUrl .'/resources/css/images/img-news.jpg');
		else{
			$image = "http://{$params->params->img_webroot_url}/{$this->path}/detail600x0/{$this->name}";
			
		}
		if ($is_url) return $image;
		return CHtml::image($image, $this->id, $htmlOptions);
	}
	
	public function getImageOrigin($htmlOptions = array(), $is_url = false){
		$params = CParams::load ();
		
		if(empty($this->name))
			$image = Yii::app()->createUrl(Yii::app()->theme->baseUrl .'/resources/css/images/img-news.jpg');
		else{
			$image = "http://{$params->params->img_webroot_url}/{$this->path}/origin/{$this->name}";
				
		}
		if ($is_url) return $image;
		return CHtml::image($image, $this->id, $htmlOptions);
	}
	
	
	public function deletePermanentlyPhoto(){
		if(isset($this->name) && isset($this->path)){
				
			if(file_exists($this->path.DS.'thumb300x0'.DS.$this->name)){
				@unlink($this->path.DS.'thumb300x0'.DS.$this->name);
			}
			if(file_exists($this->path.DS.'detail600x0'.DS.$this->name)){
				@unlink($this->path.DS.'detail600x0'.DS.$this->name);
			}
			if(file_exists($this->path.DS.'origin'.DS.$this->name)){
				@unlink($this->path.DS.'origin'.DS.$this->name);
			}
				
			$this->delete();
				
		}
	
	}
	

	public function getThumbnailImage($id, $type, $htmlOptions = array(), $is_url = false){
		if($id){
			$model = HotboxPhoto::model()->find('id = '.$id);
			if(isset($model)){
				$this->name = $model->name;
				$this->path = $model->path;
				$this->id= $model->id;
				if($type == 1){
					return $this->getImageThumb();
				} elseif($type == 2){
					return $this->getImageDetail();
				} elseif ($type == 3){
					return $this->getImageOrigin();
				} else {
					return null;
				}
			}
			
			return null;
		}
		
	}
}
