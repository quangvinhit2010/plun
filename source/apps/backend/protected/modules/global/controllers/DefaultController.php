<?php

class DefaultController extends Controller
{
	public function filters()
	{
		return array(
				'rights',
		);
	}
	public function actionIndex()
	{
		$user = (Yii::app()->user->name == 'admin') ? Yii::app()->user->data() : null;
		$this->render('index', array('user' => $user) );
	}
	
	public function actionConfig()
	{
		$this->layout='//layouts/column1';
		$config = Yii::app()->config;
		if(!empty($_POST)){
			foreach ($_POST as $key=>$value){
				if($key=='newitem'){
					if(!empty($_POST['newitem']['variable']) && !empty($_POST['newitem']['value'])){
						$config->set(preg_replace('/[^a-zA-Z0-9\']/', '_', $_POST['newitem']['variable']), $_POST['newitem']['value'], true);
					}
				}else{
					$config->set($key, $value, true);
				}
			}
			$this->refresh();
		}
		$dbReader = Yii::app()->db->createCommand("SELECT * FROM `{$config->configTableName}`")->query();
		$_config_vars = array();
		if($dbReader){
			while (false !== ($row = $dbReader->read()))
			{
				$_config_vars[$row['key']] = unserialize($row['value']);
			}
		}
		$this->render('config',
				array(
						'_config_vars'=>$_config_vars,
				)
		);
	}
	
	public function actionClearcache(){
	    Yii::app()->cache->flush();
	}
	
	public function actionClearSesion(){
		$dbReader = Yii::app()->db_activity->createCommand("TRUNCATE TABLE `activities_sessions`")->query();
	}
}