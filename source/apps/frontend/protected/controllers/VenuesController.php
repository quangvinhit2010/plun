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
    	$html	=	'';
    	foreach($data_search['data'] AS $row){
    		$row	=	$row['_source'];
    		$url	=	Yii::app()->createUrl('venues/whocheckin', array('venue_id' => $row['venue_id']));
    		if($row['visit_counter'] > 0){
    			$total	=	Lang::t('venue', '{totalvisitors} users were here', array('{totalvisitors}' => "<a data-url=\"{$url}\" href=\"javascript:void(0);\">{$row['visit_counter']}</a>"));
    			
    			$html	.=	"<li><p class=\"left\"><span data-id=\"{$row['venue_id']}\"><strong>{$row['title']}</strong></span> - {$total}</p></li>";
    		}else{
    			$html	.=	"<li><p class=\"left\"><span data-id=\"{$row['venue_id']}\"><strong>{$row['title']}</strong></span></p></li>";
    		}
    	}
    	//

    	echo $html;
    	Yii::app()->end();
    	    	
    }     
    public function actionWhocheckin(){
    	$venue_id	=	Yii::app()->request->getParam('venue_id', false);
    	$showmore	=	Yii::app()->request->getParam('showmore', false);
    	$offset		=	Yii::app()->request->getParam('offset', 0);
    	$limit		=	Yii::app()->request->getParam('limit', 20);
    	
    	$venue_data	=	CmsVenuesHistory::model()->getVisit($venue_id, $offset, $limit);
    	   	
    	
    	$elasticsearch	=	new Elasticsearch();
    	$online_data	=	$elasticsearch->checkOnlineStatus($venue_data['uid']);
    	
    	if(Yii::app()->request->isAjaxRequest && $showmore){
    		$html	=	$this->renderPartial('partial/whocheckinshowmore',
    				array(
    						'venue_data' => $venue_data,
    						'total'		=>	$venue_data['total'],
    						'limit'		=>	$limit,
    						'online_data'	=>	$online_data,
    						'offset'	=>	$offset,
    						'venue_id'	=>	$venue_id
    				), true, false);
    	}else{
    		$html	=	$this->renderPartial('partial/whocheckin',
    		array(
    				'venue_data' => $venue_data,
    				'total'		=>	$venue_data['total'],
    				'online_data'	=>	$online_data,
    				'limit'		=>	$limit,
    				'offset'	=>	$offset,
    				'venue_id'	=>	$venue_id
    		), true, false);
    	}
    	
    	echo $html;
    	exit;
    }
    public function actionGetVenueDetail(){
    	$venue_id	=	Yii::app()->request->getParam('venue_id', false);
    	$showmore	=	Yii::app()->request->getParam('showmore', false);
    	$offset		=	Yii::app()->request->getParam('offset', 0);
    	$limit		=	Yii::app()->request->getParam('limit', 20);
    	$my_venue	=	false; 
    	
    	$venue_data	=	CmsVenuesHistory::model()->getVisit($venue_id, $offset, $limit);    	
    	
    	$venue_detail	=	CmsVenues::model()->findByPK($venue_id);
    	$model_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    	
    	
    	$elasticsearch	=	new Elasticsearch();
    	$online_data	=	$elasticsearch->checkOnlineStatus($venue_data['uid']);
    	
    	if($model_location){
    		$my_venue	=	$model_location->venue_id;
    	}
    	
    	if(Yii::app()->request->isAjaxRequest && $showmore){
    		$html	=	$this->renderPartial('partial/venuedetailshowmore',
    				array(
    						'venue_detail'	=>	$venue_detail,
    						'model_location'	=>	$model_location,
    						'online_data'	=>	$online_data,
    						'venue_data' => $venue_data,
    						'total'		=>	$venue_data['total'],
    						'limit'		=>	$limit,
    						'offset'	=>	$offset,
    						'my_venue'	=>	$my_venue,
    						'venue_id'	=>	$venue_id
    				), true, false);
    	}else{
    		$html	=	$this->renderPartial('partial/venuedetail',
    				array(
    						'venue_detail'	=>	$venue_detail,
    						'model_location'	=>	$model_location,
    						'venue_data' => $venue_data,
    						'total'		=>	$venue_data['total'],
    						'limit'		=>	$limit,
    						'online_data'	=>	$online_data,
    						'my_venue'	=>	$my_venue,
    						'offset'	=>	$offset,
    						'venue_id'	=>	$venue_id
    				), true, false);
    	}
    	 
    	echo $html;
    	exit;
    } 
    public function actionCheckinfromdetail(){
    	$venue_id = Yii::app()->request->getPost('venue_id', false);
    	$venue	=	new venues();
    	$venues_row	=	CmsVenues::model()->findByPk($venue_id);
    	
    	if($venues_row){
    		
    		//update current venue
    		$location_model = UsrProfileLocation::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
    		$location_model->venue_id	=	$venue_id;
    		$location_model->save();
    		
	    	//log history
	    	if($venue_id && Yii::app()->user->id){
	    		$venue_history	=	new CmsVenuesHistory();
	    		$venue_history->venue_id	=	$venue_id;
	    		$venue_history->date_created=	time();
	    		$venue_history->type=	CmsVenuesHistory::VISITOR_TYPE_CHECKIN;
	    		$venue_history->user_id		=	Yii::app()->user->id;
	    		$venue_history->ip			=	$_SERVER['REMOTE_ADDR'];
	    		if($venue_history->save()){
	    			$venues_row	=	CmsVenues::model()->findByPk($venue_id);
	    			if($venues_row){
	    				$venues_row->total_visit	=	CmsVenuesHistory::model()->getTotalVisit($venue_id);
	    				$venues_row->save();
	    				$venue->updateVenues($venue_id);
	    			}
	    		}
	    	}
	    	//log for newsfeed
	    	$log = Activity::model()->log(
	    			Activity::LOG_CHECK_IN, array(
	    					'{userfrom}' => Yii::app()->user->data()->username,
	    					'{venue}' => $venues_row->title,
	    					'{venue_id}' => $venue_id,
	    					'{message}'	=>	''
	    			), Yii::app()->user->id, Yii::app()->user->data()->username, $venue_id
	    	);
    	}
    	
    	
    }
}
?>
