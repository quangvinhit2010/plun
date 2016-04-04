<?php

/**
 * This is the model class for table "usr_profile_settings".
 *
 * The followings are the available columns in table 'usr_profile_settings':
 * @property string $user_id
 * @property integer $sex_role
 * @property double $height
 * @property double $weight
 * @property integer $birthday
 * @property integer $birthday_year
 * @property integer $country_id
 * @property integer $city_id
 * @property integer $state_id
 * @property integer $district_id
 * @property integer $default_language
 * @property integer $ethnic_id
 * @property integer $body
 * @property integer $smoke
 * @property integer $safer_sex
 * @property integer $s_and_m
 * @property integer $tattoo
 * @property string $about_me
 * @property string $ persional_unit
 */
class UsrProfileSettings extends CActiveRecord {
    
    public $unit_weight =   null;
    public $unit_height =   null;
    public $birthday_day=   null;
    public $birthday_month  =   null;
    
    //kg & cm
    const VN_UNIT	=	1;
    //lbs & ft
    const EN_UNIT	=	2;
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'usr_profile_settings';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('sex_role, birthday, birthday_year, country_id, city_id, state_id, district_id, ethnic_id, default_language, measurement, body, smoke, safer_sex, s_and_m, tattoo, relationship, sexuality, drink, club, piercings, occupation, religion, body_hair, public_information', 'numerical', 'integerOnly' => true),
            array('height, weight', 'numerical'),
            array('user_id', 'length', 'max' => 10),
            array('about_me, persional_unit, looking_for, mannerism, languages, my_attributes,  my_types, into_stuff, live_with', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, sex_role, height, weight, birthday, birthday_year, country_id, city_id, state_id, district_id, ethnic_id, default_language, measurement, body, smoke, safer_sex, s_and_m, tattoo, about_me, persional_unit, relationship, latitude, longitude', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'language' => array(self::BELONGS_TO, 'SysLanguage', 'default_language'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => 'User',
            'sex_role' => 'Sex Role',
            'height' => 'Height',
            'weight' => 'Weight',
            'birthday' => 'Birthday',
            'birthday_year' => 'Birthday Year',
            'country_id' => 'Country',
            'city_id' => 'City',
            'state_id' => 'State',
            'district_id' => 'District',
            'ethnic_id' => 'Ethnic',
            'body' => 'Body',
            'smoke' => 'Smoke',
            'safer_sex' => 'Safer Sex',
            's_and_m' => 'S And M',
            'tattoo' => 'Tattoo',
            'about_me' => 'About Me',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('sex_role', $this->sex_role);
        $criteria->compare('height', $this->height);
        $criteria->compare('weight', $this->weight);
        $criteria->compare('birthday', $this->birthday);
        $criteria->compare('birthday_year', $this->birthday_year);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('state_id', $this->state_id);
        $criteria->compare('district_id', $this->district_id);
        $criteria->compare('ethnic_id', $this->ethnic_id);
        $criteria->compare('body', $this->body);
        $criteria->compare('smoke', $this->smoke);
        $criteria->compare('safer_sex', $this->safer_sex);
        $criteria->compare('s_and_m', $this->s_and_m);
        $criteria->compare('tattoo', $this->tattoo);
        $criteria->compare('about_me', $this->about_me, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UsrProfileSettings the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getOnlineUserList() {
        $dbrows = array();

        $users_online = Yii::app()->db_activity->createCommand()
                ->select('user_id')
                ->from("activities_sessions")
                ->group('user_id')
                ->queryAll();
        
        //get id of user online
        $user_online_id = array();
        $users_online_info = array();
        foreach ($users_online AS $userid) {
            $user_online_id[] = $userid['user_id'];
        }
        $user_on_filter = array();
        if (sizeof($user_online_id)) {
            $users_online_info = Yii::app()->db->createCommand()
                    ->select('u.id, u.alias_name, p.*, ps.*')
                    ->from("usr_user u")
                    ->join('usr_profile p', 'u.id = p.user_id')
                    ->leftJoin('usr_profile_settings ps', 'u.id = ps.user_id')
                    ->where('u.id IN(' . implode(',', $user_online_id) . ')')
                    ->queryAll();
            foreach ($users_online_info AS $row) {
                $user_on_filter[$row['id']]    =   $row;
            }
        }

        return $user_on_filter;
    }

}
