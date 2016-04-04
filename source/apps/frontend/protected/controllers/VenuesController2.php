<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class VenuesController extends Controller {
    public function actionSuggest(){
    	$keyword	=	Yii::app()->request->getParam('q', false);
    	$offset = Yii::app()->request->getParam('offset', 0);
    	
    	$venues	=	new venues();
		$data_search	=	$venues->search($keyword, array('published' => '1'));
		
    	$dbrows	=	array();
    	foreach($data_search['data'] AS $row){
    		$row	=	$row['_source'];
    		$dbrows[]	=	array(
    				'id' => $row['venue_id'],
    				'text' => $row['title']
    		);
    	}
    	$dbrows[]	=	array(
    			'id' => 0,
    			'text'	=>	$keyword,
    			'keyword' => Lang::t('search', 'Select Venue').": $keyword",
    	);
    	echo CJSON::encode($dbrows);
    	Yii::app()->end();
    	    	
    }     
    
}
?>
