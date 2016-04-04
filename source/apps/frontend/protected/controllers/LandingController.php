<?php

class LandingController extends Controller
{
    public $basePath;
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
                'class'=>'backend.extensions.captchaExtended.CaptchaExtendedAction',
                'mode'=> CaptchaExtendedAction::MODE_NUM,
                'offset'=>'4',
                'density'=>'0',
                'lines'=>'0',
                'fillSections'=>'0',
                'foreColor'=>'0x000000',
                'minLength'=>'6',
                'maxLength'=>'6',
                'fontSize'=>'20',
                'angle'=>false,
		    ),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
				'basePath'=>'pages/'.Yii::app()->language,
// 				'layout'=>'layout',
			),
		);
	}

	
	public function actionIndex()
	{
		if(!Yii::app()->user->isGuest){
			$this->redirect('/');
		}
		$this->layout = '//layouts/landing';
		$pindex = 0;
		if (isset($_GET['pindex']))
			$pindex = intval($_GET['pindex']);
		
		$limit = Yii::app()->params->news_feed['limit_display'];		
		
		$sql = "SELECT id FROM usr_user ORDER BY lastvisit DESC LIMIT 50";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
		$result = $command->queryColumn();
		if(empty($result)){
			$this->redirect('/');
		}
		$inList = implode(',', $result);
		$condition = "(user_id != 0 AND owner_id != 0) AND (user_id IN ($inList) OR owner_id IN ($inList) ) AND t.group_id = 0 AND t.status = 1";	    
		$criteria=new CDbCriteria;
		$criteria->select = 't.*, FROM_UNIXTIME(t.timestamp, \'%d-%m-%Y\') as date, (Select count(*) From activities Where group_id = t.id) as quantity';
		$criteria->addCondition($condition);
		$criteria->limit = $limit;
		$criteria->order = 'timestamp DESC';
		
		$total_newsfeed    = Activity::model()->count($criteria);
		$show_more = ($total_newsfeed > $limit) ? true : false;
		
		//explode
		$country_in_cache   =   new CountryonCache();
		
		$state_info		=	array();
		$city_info		=	array();
		$district_info	=	array();
		$country_info	=	array();
		
		$filter = array();
		
		$limit = Yii::app()->params->search_result['limit_display'];
		$offset = Yii::app()->request->getParam('offset', 0);
		
		$current_country = $country_in_cache->getCurrentCountry();
		
		if(isset($current_country['id'])){
			$country_id	=	!empty($current_country['id'])	?	$current_country['id']	:	HomeController::COUNTRY_ID_DEFAULT;
		}else{
			$country_id	=	HomeController::COUNTRY_ID_DEFAULT;
		}
		
		$filter['current_country_id'] = array('=' => $country_id);
		
		//get country info
		$country_info	=	SysCountry::model()->getCountryInfo($filter['current_country_id']['=']);
		
		if(isset(Yii::app()->session['checkin_state_id'])){
			$filter['current_state_id'] = array('=' => Yii::app()->session['checkin_state_id']);
			$state_info	=	LocationState::model()->getStateInfo(Yii::app()->session['checkin_state_id']);
		}
		if(isset(Yii::app()->session['checkin_city_id'])){
			$filter['current_city_id'] = array('=' => Yii::app()->session['checkin_city_id']);
			$city_info	=	SysCity::model()->getCityInfo(Yii::app()->session['checkin_city_id']);
		}
		if(isset(Yii::app()->session['checkin_district_id'])){
			$filter['current_district_id'] = array('=' => Yii::app()->session['checkin_district_id']);
			$district_info	=	SysDistrict::model()->getDistrictInfo(Yii::app()->session['checkin_district_id']);
		}
		
		$search_conditions = array(
				'filter' => $filter,
				'keyword' => false,
				'country_id'	=>	$filter['current_country_id']['=']
		);
		
		$user_id_except = false;
		
		$elasticsearch	=	new Elasticsearch();
		$sort_script	=	"(doc['have_avatar'].value == 1)	?	doc['last_activity'].value + 7500000 : doc['last_activity'].value";
		$elasticsearch->setSortRules($sort_script);
		$data_search	=	$elasticsearch->querySearchIndex($search_conditions, $user_id_except, $offset, $limit);		
		//end explode
		
		$this->render('//landing/page/index', array(
				'sex_roles_title' => Yii::app()->params['constants']['sex_roles'],
				'show_more' => $show_more,
				'limit' => $limit,
				'total_newsfeed' => $total_newsfeed,
				'total_result' => $data_search['total'],
				'data' => $data_search['fulldata'],
				'show_more' => $data_search['show_more'],
				'current_country_name'	=>	isset($country_info['name'])	?	$country_info['name']	:	false,
				'current_state_name'	=>	isset($state_info['name'])	?	$state_info['name']	:	false,
				'current_city_name'	=>	isset($city_info['name'])	?	$city_info['name']	:	false,
				'current_district_name'	=>	isset($district_info['name'])	?	$district_info['name']	:	false,
				'offset' => $offset + $limit
		));
	}
}