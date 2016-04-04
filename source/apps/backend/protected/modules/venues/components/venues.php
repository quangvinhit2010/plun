<?php
require 'Elasticsearch/autoload.php';
/**
 * Authentication component
 *
 * Manages user logins and permissions.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake.libs.controller.components
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


class venues {
	private $search_engine	=	null;
	private $index_name_venues	=	'list_venues';
	private $index_name_venues_tmp	=	'list_venues_tmp';
	
	public function __construct(){
		$prefix_key	=	Yii::app()->params->Elastic['prefix_key'];
		$this->index_name_venues	=	$prefix_key . $this->index_name_venues;
		$params['hosts'] = array (
				Yii::app()->params->Elastic['host_http']
		);
		$this->search_engine	=	new Elasticsearch\Client($params);
	}	
	static public function alias($txt = null) {
            $marTViet   =   array(
            "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
    		"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
    		,"ế","ệ","ể","ễ",
    		"ì","í","ị","ỉ","ĩ",
    		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
    		,"ờ","ớ","ợ","ở","ỡ",
    		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
    		"ỳ","ý","ỵ","ỷ","ỹ",
    		"đ",
    		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
    		,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
    		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
    		"Ì","Í","Ị","Ỉ","Ĩ",
    		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
    		,"Ờ","Ớ","Ợ","Ở","Ỡ",
    		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
    		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
    		"Đ"
		);

		$marKoDau =   array(
    		"a","a","a","a","a","a","a","a","a","a","a"
    		,"a","a","a","a","a","a",
    		"e","e","e","e","e","e","e","e","e","e","e",
    		"i","i","i","i","i",
    		"o","o","o","o","o","o","o","o","o","o","o","o"
    		,"o","o","o","o","o",
    		"u","u","u","u","u","u","u","u","u","u","u",
    		"y","y","y","y","y",
    		"d",
    		"A","A","A","A","A","A","A","A","A","A","A","A"
    		,"A","A","A","A","A",
    		"E","E","E","E","E","E","E","E","E","E","E",
    		"I","I","I","I","I",
    		"O","O","O","O","O","O","O","O","O","O","O","O"
    		,"O","O","O","O","O",
    		"U","U","U","U","U","U","U","U","U","U","U",
    		"Y","Y","Y","Y","Y",
    		"D"
		);
		$alias	=	str_replace($marTViet, $marKoDau, trim($txt));
		return $alias;
	}
	public function load($venue_id){
		$search_params	=	array();
		$search_params['index']	=	$this->index_name_venues;
		$search_params['type']	=	$this->index_name_venues;
		//check index
		$check_index	=	array(
				'index'	=>	$this->index_name_venues,
				'type'	=>	$this->index_name_venues
		);
		if(!$this->checkIndexExist($check_index)){
			$this->createVenuesIndex();
		}
		
		$filter	=	array();
		$filter['ids']	=	array(
					'type' => $this->index_name_venues, 
					'values'	=>	is_array($venue_id)?	$venue_id	:	array($venue_id)
		);
		
		$search_params['body']['query']['filtered']['filter']	=	$filter;
		
		$index_data	=	$this->search_engine->search($search_params);
		return $index_data;
	}
	public function search($keyword, $conditions = array(), $offset = 0, $limit = 20){
		$search_params	=	array();
		$search_params['index']	=	$this->index_name_venues;
		$search_params['type']	=	$this->index_name_venues;
			
		//filter
		$filter	=	array();

		foreach ($conditions AS $field => $value){
			$filter['and']['filters']['and'][]	=	array('term' => array($field => $value));
		}
		
		$query	=	array();
		$query['multi_match']	=	array(
				'query'	=>	$keyword,
				'fields'	=>	array('title', 'title_nosymbol'),
				'minimum_should_match'	=>	'50%',
		);
		$search_params['body']['query']['filtered']['query']	=	$query;		
		$search_params['body']['query']['filtered']['filter']	=	$filter;
		
		//set offset and limit for paging
		$search_params['from']	=	$offset;
		$search_params['size']	=	$limit;
		
		//order search result
		
		$index_data	=	$this->search_engine->search($search_params);
		
		uasort($index_data['hits']['hits'], function($a, $b){
			if ($a['_source']['top_venue'] == $b['_source']['top_venue']) {
				return 0;
			}
			return ($a['_source']['top_venue'] > $b['_source']['top_venue']) ? -1 : 1;			
		});
		
		return array('data' => $index_data['hits']['hits'], 'total' => $index_data['hits']['total']);
	}
	public function deleteVenues($venues_id){
		$params = array();
		$params['index']     = $this->index_name_venues;
		$params['type']     = $this->index_name_venues;
		$params['id']    = $venues_id;
		$params['ignore']	=	array('404');
		$this->search_engine->delete($params);		
	}
	public function updateVenues($venues_id){
		$venues_row	=	CmsVenues::model()->findByPk($venues_id);
		$check_index	=	array(
			'index'	=>	$this->index_name_venues,
			'type'	=>	$this->index_name_venues,
			'id'	=>	$venues_id
		);
		if(!$this->checkIndexExist($check_index)){
			$this->addVenues($venues_id);
		}else{		
			$body	=	array(
					'venue_id'			=>	$venues_row->id,
					'title' 			=>  $venues_row->title,
					'title_nosymbol'	=>	$venues_row->title_nosymbol,
					'address'			=>	$venues_row->address,
					'tags' 				=>  $venues_row->tags,
					'longitude'			=>	$venues_row->longitude,
					'latitude'			=>	$venues_row->latitude,
					'location'			=>  array('lat' => $venues_row->latitude, 'lon' => $venues_row->longitude),
					'published'			=>	$venues_row->published,
					'top_venue'			=>	$venues_row->top_venue,
					'country_id'		=>	$venues_row->country_id,
					'state_id'			=>	$venues_row->state_id,
					'city_id'			=>	$venues_row->city_id,
					'district_id'		=>	$venues_row->district_id,
					'top_venue'			=>	$venues_row->top_venue,
					'visit_counter'		=>	$venues_row->total_visit,
					'cat_id'			=>	$venues_row->cat_id,
					'locality_id'		=>	$venues_row->locality_id
			);
			
			if(!empty($venues_row->thumbnail)){
				$body['thumbnail']	=	"/{$venues_row->thumbnail_path}/detail200x200/{$venues_row->thumbnail}";
			}else{
				$body['thumbnail']	=	'';
			}
					
			$params = array();
			$params['body']['doc']  = $body;
			$params['index']     = $this->index_name_venues;
			$params['type']     = $this->index_name_venues;
			$params['id']    = $venues_id;
			$params['ignore']	=	array('404');
			$this->search_engine->update($params);		
		}
	}
	public function addVenues($venues_id){
		$venues_row	=	CmsVenues::model()->findByPk($venues_id);
		$check_index	=	array(
			'index'	=>	$this->index_name_venues,
			'type'	=>	$this->index_name_venues
		);
		if(!$this->checkIndexExist($check_index)){
			$this->createVenuesIndex();
		}	
		$body	=	array(
			'venue_id'			=>	$venues_row->id,
			'title' 			=>  $venues_row->title,
			'title_nosymbol'	=>	$venues_row->title_nosymbol,
			'address'			=>	$venues_row->address,
			'tags' 				=>  $venues_row->tags,
			'longitude'			=>	$venues_row->longitude,
			'latitude'			=>	$venues_row->latitude,
			'location'			=>  array('lat' => $venues_row->latitude, 'lon' => $venues_row->longitude),
			'published'			=>	$venues_row->published,
			'country_id'		=>	$venues_row->country_id,
			'state_id'			=>	$venues_row->state_id,
			'city_id'			=>	$venues_row->city_id,
			'district_id'		=>	$venues_row->district_id,
			'top_venue'			=>	$venues_row->top_venue,
			'visit_counter'		=>	$venues_row->total_visit,
			'cat_id'			=>	$venues_row->cat_id,
			'locality_id'		=>	$venues_row->locality_id
		);
		if(!empty($venues_row->thumbnail)){
			$body['thumbnail']	=	"/{$venues_row->thumbnail_path}/detail200x200/{$venues_row->thumbnail}";
		}else{
			$body['thumbnail']	=	'';
		}
				
		$params = array();
		$params['body']  = $body;
		$params['index']     = $this->index_name_venues;
		$params['type']     = $this->index_name_venues;
		$params['id']    	= $venues_id;
		$params['ignore']	=	array('404');
		$params['refresh']	=	false;
	
		$check	=	$this->search_engine->index($params);
	}	
	public function createVenuesIndex(){
		
		$indexParams['index']  	= $this->index_name_venues;
		$indexParams['type']     = $this->index_name_venues;
		// Index Settings
		$indexParams['body']['settings']['number_of_shards']   = 10;
		$indexParams['body']['settings']['number_of_replicas'] = 2;
		$indexParams['body']['settings']['refresh_interval'] = -1;
		
		// Create the index
		$this->search_engine->create($indexParams);		
		
		//set mappings
		$myTypeMapping = 				array(
			'properties'	=>	array(
					'venue_id' => array(
							'type' => 'integer',
					),
					'cat_id' => array(
							'type' => 'integer',
					),					
					'locality_id' => array(
							'type' => 'integer',
					),
					'title' => array(
						'type' => 'string',
					),
					'title_nosymbol' => array(
						'type' => 'string',
					),
					'address' => array(
							'type' => 'string',
					),	
					'tags' => array(
							'type' => 'string',
					),									
					'thumbnail' => array(
							'type' => 'string',
					),					
					'published' => array(
							'type' => 'integer',
					),	
					'country_id' => array(
							'type' => 'integer',
					),	
					'state_id' => array(
							'type' => 'integer',
					),
					'city_id' => array(
							'type' => 'integer',
					),																						
					'district_id' => array(
						'type' => 'integer',
					),
					'longitude' => array(
						'type' => 'double',
					),
					'latitude' => array(
						'type' => 'double',
					),	
					'location'	=>	array(
							'type' => 'geo_point'
					),				
					'top_venue' => array(
							'type' => 'integer',
					),		
					'visit_counter' => array(
							'type' => 'integer',
					)																							
			)
		);
		$indexParams	=	array();
		$indexParams['index'] = $this->index_name_venues;
		$indexParams['type']  = $this->index_name_venues;
		$indexParams['body'][$this->index_name_venues] = $myTypeMapping;
		$this->search_engine->indices()->putMapping($indexParams);		
	}
	public function updateCounter($venue_id){
		
		$indexParams['index']  	= $this->index_name_venues;
		$indexParams['type']     = $this->index_name_venues;
		// Index Settings
		$indexParams['body']['settings']['number_of_shards']   = 10;
		$indexParams['body']['settings']['number_of_replicas'] = 2;
		$indexParams['body']['settings']['refresh_interval'] = -1;
	
		$params = array();
		$params['body']['script']  = "ctx._source.visit_counter = max((int)ctx._source.visit_counter + 1, 0)";
		$params['index']     = $this->index_name_venues;
		$params['type']     = $this->index_name_venues;
		if($user_id){
			$params['id']    = $venue_id;
		}
		//refesh data sau 1s
		$params['refresh']	=	1;
		$params['retry_on_conflict']	=	10;
		$params['ignore']	=	array('404', '409');
		$this->search_engine->update($params);
				
	}	
	public function checkIndexExist($index){
		$params	=	array();
		$params['index'] = isset($index['index'])	?	$index['index']	:	false;
		$params['type'] = isset($index['type'])	?	$index['type']	:	false;
		$params['id'] = isset($index['id'])	?	$index['id']	:	false;
		$check_index_exist	=	$this->search_engine->exists($params);
		return $check_index_exist;
	}		
}
