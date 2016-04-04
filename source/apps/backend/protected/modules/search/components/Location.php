<?php
class Location {
	static function getLongitude_Latitude_v2($address){
	  
	  $address_map =str_replace(' ','+',trim($address));
	 	
	  $request = @file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address_map . "&sensor=false");
	  $json = json_decode($request, true);
	  if($json['status'] == 'OK' ){
	   $latitude =  $json['results'][0]['geometry']['location']['lat'] ;
	   $longitude = $json['results'][0]['geometry']['location']['lng'] ;
	   $arr_data =  array('latitude'=>$latitude,'longitude'=>$longitude);
	  }else{
	   $arr_data =  array('latitude'=>0,'longitude'=>0);
	  }
	
	  return $arr_data;
	 }
}