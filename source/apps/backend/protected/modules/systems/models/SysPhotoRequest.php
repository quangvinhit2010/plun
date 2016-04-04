<?php

/**
 * This is the model class for table "sys_photo_request".
 *
 * The followings are the available columns in table 'sys_photo_request':
 * @property integer $id
 * @property string $request_user_id
 * @property integer $photo_id
 * @property string $photo_user_id
 * @property integer $status
 * @property integer $date_accepted
 * @property integer $date_request
 * @property integer $is_read
 */
class SysPhotoRequest extends CActiveRecord
{
	const REQUEST_ACCEPTED	=	2;
	const REQUEST_PENDING	=	1;
	const REQUEST_DECLINED	=	3;
	const REQUEST_NONE		=	0;
	
	const IS_UNREAD = 0;
	const IS_PROCESS = 2;
	const IS_READ = 3;
	const IS_SHARE_READ = 4;
	
	public $days;
	public $ids;
	public $total_photo_request;
	public $total_day_request;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_photo_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_user_id, photo_id, photo_user_id', 'required'),
			array('photo_id, status, date_accepted, date_request, request_number', 'numerical', 'integerOnly'=>true),
			array('request_user_id, photo_user_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, request_user_id, photo_id, photo_user_id, status, date_accepted, date_request, is_read, request_number', 'safe', 'on'=>'search'),
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
			'request_user'  => array(self::BELONGS_TO, 'Member', 'request_user_id'),
			'photo'  => array(self::BELONGS_TO, 'Photo', 'photo_id'),
			'reponse_user'	=> array(self::BELONGS_TO, 'Member', 'photo_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'request_user_id' => 'Request User',
			'photo_id' => 'Photo',
			'photo_user_id' => 'Photo User',
			'status' => 'Status',
			'date_accepted' => 'Date Accepted',
			'date_request' => 'Date Request',
			'is_read' => 'Is Read',
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
		$criteria->compare('request_user_id',$this->request_user_id,true);
		$criteria->compare('photo_id',$this->photo_id);
		$criteria->compare('photo_user_id',$this->photo_user_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_accepted',$this->date_accepted);
		$criteria->compare('date_request',$this->date_request);
		$criteria->compare('is_read',$this->is_read);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysPhotoRequest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getPhotoRequest($user_id, $offset = 0, $limit = 10){
		$criteria = new CDbCriteria;
		
		$criteria->select = "FROM_UNIXTIME(date_request, '%Y-%m-%d') AS `days`";
		$criteria->distinct	=	true;		
		$criteria->alias	=	'pr';
		$criteria->addCondition("((pr.request_user_id = :user_id AND ((is_read = 2 OR is_read != 2) AND `pr`.`status` != 1)) 
				OR (pr.status != 3 AND pr.status != 2 AND pr.photo_user_id = :user_id AND (is_read != 3 OR is_read != 2)))");
		$criteria->params = array(':user_id' => $user_id);
		$criteria->offset	=	$offset;
		$criteria->limit = $limit;		
		$criteria->order	=	'date_request DESC';
		
		$date_rows	=	$this->findAll($criteria);
		if(isset($date_rows[0])){
			$days	=	array();
			foreach($date_rows AS $row){
				$days[]	=	$row->days;
			}
			$dbrow	=	array();
			$response_id_json	=	array();
			foreach($days AS $day){
				$data			=	$this->getPhotoRequestByDate($user_id, $day);
				$response_id_json	=	array_merge($data['response_id_json'], $response_id_json);
				unset($data['response_id_json']);
				$dbrow[$day]	=	$data;
				
			}
			
			//get total day for paging
			$criteria = new CDbCriteria;
			$criteria->select = "COUNT(DISTINCT FROM_UNIXTIME(date_request, '%Y-%m-%d')) AS total_day_request";
			$criteria->alias	=	'pr';
			$criteria->addCondition("((pr.request_user_id = :user_id AND ((is_read = 2 OR is_read != 2) AND `pr`.`status` != 1)) 
					OR (pr.status != 3 AND pr.status != 2 AND pr.photo_user_id = :user_id AND (is_read != 3 OR is_read != 2)))");
			$criteria->params = array(':user_id' => $user_id);	
			
			$total	=	$this->find($criteria);
						
			return array('data' => $dbrow, 'response_id_json'	=>	$response_id_json, 'total' => $total->total_day_request);
		}else{
			return false;
		}
	}
	
	public function getPhotoRequestByDate($user_id, $date){
		$criteria = new CDbCriteria;
		$criteria->select = "pr.*, u.username, u.alias_name";
		$criteria->distinct	=	true;		
		$criteria->alias	=	'pr';
		$criteria->join	=	'INNER JOIN usr_user u ON (u.id = pr.request_user_id)';
		$criteria->addCondition("FROM_UNIXTIME(date_request, '%Y-%m-%d') = :date 
				AND ((pr.request_user_id = :user_id AND ((is_read = 2 OR is_read != 2) AND `pr`.`status` != 1)) 
				OR (pr.status != 3 AND pr.status != 2 AND pr.photo_user_id = :user_id AND (is_read != 3 OR is_read != 2)))");
			
		$criteria->params = array(':user_id' => $user_id, ':date'	=>	$date);
		$criteria->order	=	'pr.id DESC';
		
		$data	=	$this->findAll($criteria);
		$dbrow	=	array();
		$dbrow_tmp	=	array();
		$response_id_json	=	array();
		
		if(isset($data[0])){
			
			foreach ($data AS $row){
				if(!empty($row->photo)){
					$request_attributes['list']	=	$row->attributes;
					$request_attributes['list']['thumbnail_html']	=	$row->photo->getImageThumbnail(false, array('data-rid' => $row->id,  'align' => 'absmiddle', 'width' => '24px', 'height' => '24px'));	
					$request_attributes['list']['small_thumbnail_html']	=	$row->photo->getImageSmallThumbnail(array('align' => 'absmiddle', 'width' => '24px', 'height' => '24px'));
					
					$request_attributes['list']['description']	=	$row->photo->description;
					$request_attributes['list']['large_thumbnail_url']	=	$row->photo->getImageLarge(true);
					
					if($user_id == $row->request_user_id) {
						$request_attributes['user']	=	array(
								'avatar'	=>	$row->reponse_user->getAvatar(true),
								'url'	=>	$row->reponse_user->getUserUrl(),
								'name'	=>	$row->reponse_user->getDisplayName()
						);
						$dbrow_tmp['response'][$row->status][] = $request_attributes;
						if($row->is_read != 3){
							$response_id_json[]	=	$row->id;
						}
						
					} elseif($user_id == $row->photo_user_id) {
						$request_attributes['user']	=	array(
								'avatar'	=>	$row->request_user->getAvatar(true),
								'url'	=>	$row->request_user->getUserUrl(),
								'name'	=>	$row->request_user->getDisplayName()
						);
						$dbrow_tmp['request'][] = $request_attributes;
					}				
				}				
			}
							
			//group it
			if(isset($dbrow_tmp['request'])){
				foreach ($dbrow_tmp['request'] as $request){
					$dbrow['request'][$request['list']['request_user_id']]['request_list'][] = $request['list'];
					$dbrow['request'][$request['list']['request_user_id']]['request_user_info'] = $request['user'];
				}
			}
			if(isset($dbrow_tmp['response'])){
				foreach ($dbrow_tmp['response'] as $status => $response){
					foreach ($response as $get_user){
						$dbrow['response'][$get_user['list']['status']][$get_user['list']['photo_user_id']]['response_list'][] = $get_user['list'];
						$dbrow['response'][$get_user['list']['status']][$get_user['list']['photo_user_id']]['response_user_info'] = $get_user['user'];
					}
				}
			}						
		}
		
		//get total 
		$criteria->select = "COUNT(pr.id) AS total_photo_request";
		$criteria->alias	=	'pr';
		$criteria->distinct	=	true;
		$criteria->join	=	'INNER JOIN usr_profile p ON (p.user_id = pr.request_user_id)';
		$criteria->addCondition("FROM_UNIXTIME(date_request, '%Y-%m-%d') = :date 
				AND ((pr.request_user_id = :user_id AND ((is_read = 2 OR is_read != 2) AND `pr`.`status` != 1)) 
				OR (pr.status != 3 AND pr.status != 2 AND pr.photo_user_id = :user_id AND (is_read != 3 OR is_read != 2)))");
		$criteria->params = array(':user_id' => $user_id, ':date'	=>	$date);
		
		$total	=	$this->find($criteria);
				
		return array('data' => $dbrow, 'total' => $total->total_photo_request, 'response_id_json'	=>	$response_id_json);
	}
	public function getPhotoRequestResponse($user_id, $offset = 0, $limit = 4){
		
		/*
		 * SELECT 
			GROUP_CONCAT(pr.id) AS ids, 
			DATE_FORMAT(FROM_UNIXTIME(pr.date_request), '%Y-%m-%d %k') AS daily,
			DATE_FORMAT(FROM_UNIXTIME(pr.date_request), '%Y-%m-%d') AS days
			FROM `sys_photo_request` `pr` 
			INNER JOIN usr_profile p ON (p.user_id = pr.request_user_id) WHERE (is_read != 0 OR is_read != 2) 
			AND (pr.request_user_id = 3 OR pr.photo_user_id = 3) 
			GROUP BY HOUR(FROM_UNIXTIME(pr.date_request))
			ORDER BY pr.id DESC, pr.date_accepted
		 *   */
		
		$criteria = new CDbCriteria;
		$criteria->select = "GROUP_CONCAT(pr.id) AS ids, DATE_FORMAT(FROM_UNIXTIME(pr.date_request), '%Y-%m-%d %k') AS daily_group, DATE_FORMAT(FROM_UNIXTIME(pr.date_request), '%Y-%m-%d') AS days, pr.request_user_id";
		$criteria->distinct	=	true;
		$criteria->alias	=	'pr';
		$criteria->offset	=	$offset;
		$criteria->limit = $limit;
		$criteria->join	=	'INNER JOIN usr_profile p ON (p.user_id = pr.request_user_id)';
		$criteria->addCondition("(is_read != 0 OR is_read != 2) AND (pr.request_user_id = :user_id OR pr.photo_user_id = :user_id)");
		$criteria->params = array(':user_id' => $user_id);
		$criteria->group = 'days';
		$criteria->order = "pr.id DESC, pr.date_accepted DESC";
		$response	=	$this->findAll($criteria);
		return $response;
	}
	
	
	public function getDetailRequestReponse($ids){
		if(isset($ids)){
			$criteria = new CDbCriteria;
			$criteria->distinct	=	true;
			$criteria->alias	=	'pr';
			$criteria->join	=	'INNER JOIN usr_profile p ON (p.user_id = pr.request_user_id)';
			$criteria->addInCondition('pr.id', explode(",",$ids));
			$criteria->order = "pr.id DESC, pr.date_accepted DESC";
			$response	=	$this->findAll($criteria);
			return $response;
		} else {
			return false;
		}
		
	}
	
	public static function getDateTime($days){
		if(isset($days)){
			$date = date('d/m/Y', strtotime($days));
			if($date == date('d/m/Y')) {
				return Lang::t('photo', 'Today');
			} else if($date == date('d/m/Y', time() - (24 * 60 * 60))) {
				return Lang::t('photo', 'Yesterday');
			} else {
				return Lang::t('time', 'DATE_FORMAT_ELAPSED_OP2', 
						array(
								'{day}'		=>	date('d', strtotime($days)), 
								'{month}'	=>	Lang::t('time', strtoupper(date('F', strtotime($days)))),
								'{year}'	=>	date('Y', strtotime($days)), 
						)
				);
			}
			
		}
		
	}
	
	public static function getPhotoRequestCount($user_id){
		if(isset($user_id)){
			return SysPhotoRequest::model()->count('status = 1 AND photo_user_id = :user_id', array(':user_id' => $user_id));
		} else {
			return false;
		}
	}
	public static function getPhotoRequestUnreadCount($user_id){
		if(isset($user_id)){
			return SysPhotoRequest::model()->count('status = 1 AND is_read = 0 AND photo_user_id = :user_id', array(':user_id' => $user_id));
		} else {
			return false;
		}
	}
	public function checkPhotoRequest($request_user_id, $photo_id, $photo_user_id){
		$criteria = new CDbCriteria;
		$criteria->addCondition("request_user_id = :request_user_id");
		$criteria->addCondition("photo_id = :photo_id");
		$criteria->addCondition("photo_user_id = :photo_user_id");
		$criteria->params = array(':request_user_id' => $request_user_id, ':photo_id' => $photo_id, ':photo_user_id' => $photo_user_id);
		$data	=	$this->find($criteria);
		if($data){
			return $data;
		}else{
			return false;
		}
	}
}
