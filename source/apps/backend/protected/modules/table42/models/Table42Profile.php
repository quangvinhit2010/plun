<?php

/**
 * This is the model class for table "table42_profile".
 *
 * The followings are the available columns in table 'table42_profile':
 * @property string $id
 * @property string $user_id
 * @property string $username
 * @property string $phone
 * @property string $facebook_id
 * @property string $email
 * @property integer $status
 * @property integer $date_created
 * @property integer $sex_role
 * @property integer $thumbnail_id
 * @property integer $date_approved
 * @property integer $round_id
 */
class Table42Profile extends EventActiveRecord
{
	public $total		=	0;
	
	const STEP_SIGNUP	=	1;
	const STEP_THUMBNAIL=	2;
	
	const STATUS_PENDING	=	2;
	const STATUS_APPROVED	=	1;
	const STATUS_DECLINE	=	0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table42_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('status, step, date_created, sex_role, thumbnail_id, date_approved, round_id, published', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>10),
			array('username, facebook_id, email', 'length', 'max'=>200),
			array('phone', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, username, phone, facebook_id, email, status, step, date_created, sex_role, thumbnail_id, date_approved, round_id, published', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
	    $relations['photo'] = array(self::BELONGS_TO, 'Table42Photo', 'thumbnail_id');
	    $relations['round'] = array(self::BELONGS_TO, 'Table42Round', 'round_id');
	    $relations['photos'] = array(self::HAS_MANY, 'Table42Photo', array('profile_id'=>'id'));
	    
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
			'phone' => 'Phone',
			'facebook_id' => 'Facebook',
			'email' => 'Email',
			'status' => 'Status',
			'date_created' => 'Date Created',
			'sex_role' => 'Sex Role',
			'thumbnail_id' => 'Thumbnail',
			'date_approved' => 'Date Approved',
			'round_id' => 'Round',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('facebook_id',$this->facebook_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('sex_role',$this->sex_role);
		$criteria->compare('thumbnail_id',$this->thumbnail_id);
		$criteria->compare('date_approved',$this->date_approved);
		$criteria->compare('round_id',$this->round_id);
		$criteria->order	=	'date_created DESC';
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function approved()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('facebook_id',$this->facebook_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('sex_role',$this->sex_role);
		$criteria->compare('thumbnail_id',$this->thumbnail_id);
		$criteria->compare('date_approved',$this->date_approved);
		$criteria->compare('round_id',$this->round_id);
		$criteria->addCondition('status = ' . Table42Profile::STATUS_APPROVED);
		$criteria->order	=	'date_created DESC';
		
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}	
	public function notapproved()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('facebook_id',$this->facebook_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_created',$this->date_created);
		$criteria->compare('sex_role',$this->sex_role);
		$criteria->compare('thumbnail_id',$this->thumbnail_id);
		$criteria->compare('date_approved',$this->date_approved);
		$criteria->compare('round_id',$this->round_id);
		$criteria->addCondition('status != ' . Table42Profile::STATUS_APPROVED);
		$criteria->order	=	'date_created DESC';
		
		
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Table42Profile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getFullprofiledetail($user_id = false){
		$data	=	array();
		$city_in_cache = new CityonCache();
		$country_in_cache   =   new CountryonCache();
		$state_in_cache	=	new StateonCache();
		$district_in_cache	=	new DistrictonCache();
		
		if(!$user_id){
			$user_id = $this->user_id;
		}
		
		$profile_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => $user_id));
		
		//get location
		$location	=	array();
		if(!empty($profile_setting->district_id)){
			$district =	$district_in_cache->getDistrictInfo($profile_setting->district_id);
			$location[]	=	$district['name'];
		}
		if(!empty($profile_setting->city_id)){
			$city =	$city_in_cache->getCityInfo($profile_setting->city_id);
			$location[]	=	$city['name'];
		}
		if(!empty($profile_setting->state_id)){
			$state =	$state_in_cache->getStateInfo($profile_setting->state_id);
			$location[]	=	$state['name'];
		}
		if(!empty($profile_setting->country_id)){
			$country =	$country_in_cache->getCountryInfo($profile_setting->country_id);
			$location[]	=	$country['name'];
		}
		//general location
		if(sizeof($location)){
			$location	=	implode(', ', $location);
		}else{
			$location	=	ProfileSettingsConst::EMPTY_DATA;
		}
		
		$data['location']	=	$location;
		
		//get relationship
		$relationship	=	ProfileSettingsConst::getRelationshipLabel();
		if($profile_setting->relationship != ProfileSettingsConst::RELATIONSHIP_PREFER_NOTTOSAY){
			$relationship	=	isset($relationship[$profile_setting->relationship])	?	$relationship[$profile_setting->relationship]	:	ProfileSettingsConst::EMPTY_DATA;
		}else{
			$relationship	=	$relationship[$profile_setting->relationship];
		}
		$data['relationship']	=	$relationship;
		
		//for Age
		$birthday_year   =   isset($profile_setting->birthday_year)  ?  $profile_setting->birthday_year    :   false ;
		if($birthday_year){
			$data['birthday_year']	=	(date('Y') - $birthday_year);
		}
		//for Ethnicity
		$ethnicity=	ProfileSettingsConst::getEthnicityLabel();
		if(!empty($profile_setting->ethnic_id)){
			$data['ethnicity']	=	$ethnicity[$profile_setting->ethnic_id];
		}
		
		//for Sexuality
		$sexuality	=	ProfileSettingsConst::getSexualityLabel();
		if(!empty($profile_setting->sexuality)){
			$data['sexuality']	=	$sexuality[$profile_setting->sexuality];
		}
		//for Role
		$roles	=	ProfileSettingsConst::getSexRoleLabel();
		if(!empty($profile_setting->sex_role)){
			$data['role']	=	$roles[$profile_setting->sex_role];
		}
		
		$user_measurement	=	$profile_setting->measurement;
		
		if($user_measurement == UsrProfileSettings::VN_UNIT){
				$height	=	$profile_setting->height . ' cm';
				$weight	=	$profile_setting->weight . ' kg';				
		}else{
				$height	=	$profile_setting->height . ' ft';
				$weight	=	$profile_setting->weight . ' lbs';				
		}
		
		if(empty($profile_setting->height)){
			$height	=	ProfileSettingsConst::EMPTY_DATA;
		}
		if(empty($profile_setting->weight)){
			$weight	=	ProfileSettingsConst::EMPTY_DATA;
		}
		$data['height']	=	$height;
		$data['weight']	=	$weight;
		
		//get language you understand
		$languages	=	ProfileSettingsConst::getLanguagesLabel();
		$languages_value	=	array();
		if(!empty($profile_setting->languages)){
			$languages_choose	=	explode(',', $profile_setting->languages);
			foreach($languages_choose AS $l):
			if(!empty($languages[$l])){
				$languages_value[]	=	$languages[$l];
			}
			endforeach;
		}
		$data['languages']	=	sizeof($languages_value)	?	implode(', ', $languages_value) :	ProfileSettingsConst::EMPTY_DATA;
		
		//get looking for
		$looking_for	=	ProfileSettingsConst::getLookingforLabel();
		$looking_for_value	=	ProfileSettingsConst::EMPTY_DATA;
		$looking_for_arr	=	array();
		
		if(!empty($profile_setting->looking_for)){
			$looking_for_choose	=	explode(',', $profile_setting->looking_for);
			if(!empty($looking_for_choose)){
				foreach($looking_for_choose AS $l):
				if(!empty($looking_for[$l])){
					$looking_for_arr[]	=	$looking_for[$l];
				}
				endforeach;
				$data['looking_for']	=	sizeof($looking_for_arr)	?	implode(', ', $looking_for_arr) :	ProfileSettingsConst::EMPTY_DATA;
			}
		}
				
		//get Religion
		$religion			=	ProfileSettingsConst::getReligionLabel();
		$data['religion']	=	isset($religion[$profile_setting->religion])	?	$religion[$profile_setting->religion]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get build
		$build	=	ProfileSettingsConst::getBuildLabel();
		$data['build']	=	isset($build[$profile_setting->body])	?	$build[$profile_setting->body]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get body hair
		$body_hair	=	ProfileSettingsConst::getBodyHairLabel();
		$data['body_hair']	=	isset($body_hair[$profile_setting->body_hair])	?	$body_hair[$profile_setting->body_hair]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Tattoos
		$tattoo	=	ProfileSettingsConst::getTattoosLabel();
		$data['tattoo']	=	isset($tattoo[$profile_setting->tattoo])	?	$tattoo[$profile_setting->tattoo]	:	ProfileSettingsConst::EMPTY_DATA;
				
		//get Piercings
		$piercing	=	ProfileSettingsConst::getPiercingsLabel();
		$data['piercing']	=	isset($piercing[$profile_setting->piercings])	?	$piercing[$profile_setting->piercings]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Mannerism
		$mannerism	=	ProfileSettingsConst::getMannerismLabel();
		$data['mannerism']	=	isset($mannerism[$profile_setting->mannerism])	?	$mannerism[$profile_setting->mannerism]	:	ProfileSettingsConst::EMPTY_DATA;
			
		//get smoke
		$smoke	=	ProfileSettingsConst::getSmokeLabel();
		$data['smoke']	=	isset($smoke[$profile_setting->smoke])	?	$smoke[$profile_setting->smoke]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get drink
		$drink	=	ProfileSettingsConst::getDrinkLabel();
		$data['drink']	=	isset($drink[$profile_setting->drink])	?	$drink[$profile_setting->drink]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get club
		$club	=	ProfileSettingsConst::getClubLabel();
		$data['club']	=	isset($club[$profile_setting->club])	?	$club[$profile_setting->club]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get Safe Sex
		$safer_sex	=	ProfileSettingsConst::getSafeSexLabel();
		$data['safer_sex']	=	isset($safer_sex[$profile_setting->safer_sex])	?	$safer_sex[$profile_setting->safer_sex]	:	ProfileSettingsConst::EMPTY_DATA;
		
		//get live with
		$live_with	=	ProfileSettingsConst::getLiveWithLabel();
		$data['live_with']	=	isset($live_with[$profile_setting->live_with])	?	$live_with[$profile_setting->live_with]	:	ProfileSettingsConst::EMPTY_DATA;
			
		//get Occupation
		$occupation	=	ProfileSettingsConst::getOccupationLabel();
		$occupation_value	=	array();
		if(!empty($profile_setting->occupation)){
			$occupation_choose	=	explode(',', $profile_setting->occupation);
			foreach($occupation_choose AS $l):
			if(!empty($occupation[$l])){
				$occupation_value[]	=	$occupation[$l];
			}
			endforeach;
		}
		$data['occupation']	=	sizeof($occupation_value)	?	implode(', ', $occupation_value) :	ProfileSettingsConst::EMPTY_DATA;
		//end get Occupation
		
		return $data;
	}
}
