<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MaintenanceSearchCommand extends CConsoleCommand {
    public function actionPushOnlinelistToCache(){
        $UserOnlineonCache  =   new UserOnlineonCache();
        $UserOnlineonCache->setUserOnlineOnCache();
    }
    public function actionPushDataToCache(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->buildFullIndex();
    }
    public function actionreOrderUsers(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->reOrderUsers();
    }
    public function actionUpdateLocation(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->updatePositionAllUser();
    }
    public function actionRegisterUser(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->registerSearchIndex(4141);
    }
    public function actionBuildIndex($country_id = 230, $limit = 10000, $offset = 0){

    	$Elasticsearch	=	new Elasticsearch();
    	$filter		=	array();
    	$filter['current_country_id'] = array('=' => $country_id);
    	$search_conditions = array(
    			'filter' => $filter,
    			'keyword' => false,
    			'country_id'	=>	$country_id
    	);
    	$Elasticsearch->buildSearchIndex($search_conditions, false, $limit, $offset);
    	exit;
    }
    public function actionUpdateIndexAll(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->updateIndexAllUser();
    }
    public function actionUpdateNotification($limit = 5000){
    	XNotifications::model()->updateNotification($limit);
    }
    public function actionUpdateMapping(){
    	$Elasticsearch	=	new Elasticsearch();
    	$Elasticsearch->updateIndexAllUser();
    }
    public function actionUpdateFriendlist(){
    	set_time_limit(0);
    	
    	$cmd = Yii::app()->db_search->createCommand();
    	$cmd->select("(YEAR(CURDATE()) - `birthday_year`) AS age, s.*");
    	$cmd->from('user_data_search s');
    	$dataUser = $cmd->queryAll();
    	
    	$Elasticsearch	=	new Elasticsearch();
    	
    	foreach ($dataUser AS $row) {
    		$my_friendlist	=	Friendship::model()->getAllFriendID($row['user_id']);
    		$my_friendlist_ids	=	implode(',', $my_friendlist);
    		
    		//update index
    		$Elasticsearch->update(array('friendlist' => $my_friendlist_ids), $row['current_country_id'], $row['user_id']);
    	}
    	
    }
}
