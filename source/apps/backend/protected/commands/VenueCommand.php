<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class VenueCommand extends CConsoleCommand {
    public function actionBuildIndex($offset = 0, $limit = 10000){
    	$venue	=	new venues();
    	$criteria=new CDbCriteria;
    	$criteria->addCondition("published = 1");
    	$venue_data	=	CmsVenues::model()->findAll($criteria);
    	$venue->createVenuesIndex();
    	foreach($venue_data AS $row){
    		$venue->addVenues($row->id);
    	}
    }
}
