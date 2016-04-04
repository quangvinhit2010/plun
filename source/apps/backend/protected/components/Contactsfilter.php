<?php
class Contactsfilter {
	public static function facebook_filter($contacts_from_apiresult){
		$count	=	0;
		$dbrow	=	array();
		
		foreach($contacts_from_apiresult AS $row){
			$dbrow[$row['id']]	=	Util::partString($row['name'], 0, 18);
			$count++;			
		}	
		if($count > 0){
			asort($dbrow);
			$tmp = array();
			foreach ($dbrow as $k => $v)
				$tmp[] = array('id' => $k, 'displayname' => $v);
			return array('result' => $tmp, 'total' => $count);
		}else{
			return false;
		}		
	}
	public static function google_filter($contacts_from_apiresult, $offset = 0, $limit = 10){
		$count	=	0;
		$dbrow	=	array();		
		foreach($contacts_from_apiresult AS $row){
			if(isset($row['gd$email'])){
				$email	=	$row['gd$email'][0]['address'];
				if(empty($row['title']['$t'])){
					$displayname	=	$email;
				}else{
					$displayname	=	$row['title']['$t'];
				}
				//$dbrow[]	=	array('email' => $email, 'displayname' => Util::partString($displayname, 0, 18));
				$dbrow[$email] = Util::partString($displayname, 0, 18);
				$count++;
			}			
		}
		if($count > 0){
			asort($dbrow);
			$tmp = array();
			$i = 0;
			foreach ($dbrow as $k => $v)
				if($i >= $offset && $i < $offset + $limit && $i< $count){
					//$tmp[] = array('email' => $k, 'displayname' => $v);
					$tmp[] = $k;
				}
			return array('result' => $tmp, 'total' => $count);
		}else{
			return false;
		}		
	}
	public static function yahoo_filters($contacts_from_apiresult) {
		$email_list	=	array();
		foreach ($contacts_from_apiresult AS $row){
			$email_list[]		=	$row['email'];
		}
		return $email_list; 
	}
	/*
	public static function yahoo_filters($contacts_from_apiresult) {

		$count	=	0;
		$dbrow	=	array();
		$tmpdb = array();
		foreach($contacts_from_apiresult AS $row){
			
			//process for fields
			$avatar	=	'';
			$email	=	'';
			$yahooid	=	'';
			$name	=	'';
			$row_approved	=	false;
			
			foreach($row->fields AS $field){
				if(is_object($field)){
					if($field->type == 'guid' && isset($field->profile)){
						$avatar	=	$field->profile->image->imageUrl;
					}
					if($field->type == 'email'){
						$email	=	$field->value;
						$row_approved	=	true;
					}				
					if($field->type == 'guid'){
						$yahooid	=	$field->value;
					}				
					if($field->type == 'name'){
						if(isset($field->value->givenName)){
							$name	=	$field->value->givenName;
						}
					}
				}
			}
			//set default for display name
			if(empty($name)){
				$name	=	$email;
			}
			if($row_approved){
				$count++;
				//$dbrow[$email]	=	array('email' => $email, 'avatar' => $avatar, 'displayname' => Util::partString($name, 0, 18), 'id' => $yahooid);
				$dbrow[$row->id]	=	$email;
				$tmpdb[$row->id]	=	array('email' => $email, 'avatar' => $avatar, 'displayname' => Util::partString($name, 0, 18), 'id' => $yahooid);
			}
		}
		
		if($count > 0){
			asort($dbrow);
			$tmp = array();
			foreach ($dbrow as $k => $v)
				//$tmp[] = array('id' => $k, 'displayname' => $tmpdb[$k]['displayname'], 'email' => $tmpdb[$k]['email']);
				$tmp[] = $tmpdb[$k]['email'];		
			return array('result' => $tmp, 'total' => $count);
		}else{
			return false;
		}
	}
	*/
}