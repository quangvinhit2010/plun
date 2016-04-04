<?php
class SocialRegister {
	static public function downloadFromUrl($url, $filePath){
		$content = file_get_contents($url);
		file_put_contents($filePath, $content);
	}
	static public function createRandomUser($firstname = ''){
		$suggest_username	=	false;
		if(!empty($firstname)){
			$firstname	=	Util::getSlug($firstname);
			$chk	=	preg_match('@([a-zA-Z0-9]*)@i', $firstname);
			if($chk){
				$suggest_username	=	substr(strtolower($firstname), 0, 10);
			}
		}
		//create random username
		if($suggest_username){
			$prefix	=	$suggest_username;
		}else{
			$prefix	=	null;
		}
		do {
			$number = Util::randomDigits(8);
			$uniqueID = substr($prefix . $number, 0, 20);
			$exists = Member::model()->findByAttributes(
					array(
						'username'	=>	$uniqueID
					)
			);
		} while ($exists);
		return $uniqueID;
	}	
}