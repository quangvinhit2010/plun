<?php
class InviteOpenId {
	protected $gOauth;
	public function __construct(){
		$sv = Yii::app()->eauth->services;
		$this->gOauth = new GmailOath($sv['google_oauth']['client_id'], $sv['google_oauth']['client_secret'], null, false, Yii::app()->createAbsoluteUrl('invitation/frontend/getFriends/connect_type/google'));		
	}
	
	public function loginOpendId($connect_type){
		$loginUrl = "";
		switch ($connect_type){
			case "twitter":
			    $twitter = Yii::app()->twitter->getTwitter();
			    $request_token = $twitter->getRequestToken();
			    Yii::app()->session['oauth_token'] = $token =  $request_token['oauth_token'];
			    Yii::app()->session['oauth_token_secret'] = $request_token['oauth_token_secret'];
			    if($twitter->http_code == 200){
			        //get twitter connect url
			        $loginUrl = $twitter->getAuthorizeURL($token);
			        //send them
			    }
				break;
			case "facebook":
				$loginUrl = Yii::app()->facebook->getLoginUrl(
					array(
							'scope'         => 'email, offline_access, publish_stream, publish_actions, user_birthday, user_about_me, user_status, read_friendlists, friends_status',
							'redirect_uri'  => Yii::app()->request->hostInfo . '/invitation/frontend/getFriends/connect_type/facebook',
					)
				);
				break;
			case "google":
				$oGetContacts = new GmailGetContacts();
				$oRequestToken = $oGetContacts->get_request_token($this->gOauth, false, true, true);
			    $_SESSION['oauth_token'] = $oRequestToken['oauth_token'];
			    $_SESSION['oauth_token_secret'] = $oRequestToken['oauth_token_secret'];
				$loginUrl = 'https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token='.$this->gOauth->rfc3986_decode($oRequestToken['oauth_token']);
				break;
			case "yahoo":
				break;
		}
		echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
		exit();
	}
	
	public function getFriendsGoogle(){
		$iMaxResults = 1000; // max results
		$oGetContacts = new GmailGetContacts();
		if ($_GET && $_GET['oauth_token']) {
		    // decode request token and secret
		    $sDecodedToken = $this->gOauth->rfc3986_decode($_GET['oauth_token']);
		    $sDecodedTokenSecret = $this->gOauth->rfc3986_decode($_SESSION['oauth_token_secret']);
		    // get 'oauth_verifier'
		    $oAuthVerifier = $this->gOauth->rfc3986_decode($_GET['oauth_verifier']);
		    // prepare access token, decode it, and obtain contact list
		    $oAccessToken = $oGetContacts->get_access_token($this->gOauth, $sDecodedToken, $sDecodedTokenSecret, $oAuthVerifier, false, true, true);
		    $sAccessToken = $this->gOauth->rfc3986_decode($oAccessToken['oauth_token']);
		    $sAccessTokenSecret = $this->gOauth->rfc3986_decode($oAccessToken['oauth_token_secret']);
		    $aContacts = $oGetContacts->GetContacts($this->gOauth, $sAccessToken, $sAccessTokenSecret, false, true, $iMaxResults);
		} 
		if (!empty($aContacts)){
			return $aContacts;
		}
		return false;
	}
	
	public function getFriendsFacebook(){
		$fb_uid = Yii::app()->facebook->getUser();
		
		if (!$fb_uid){
			return false;
		}
		
		$index = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
		$limit = 10;
		$friends = array();
		
		$chunk = Yii::app()->facebook->api(
			"/me/friends", 'GET',
			array(
				'fields' => 'id,name,gender',
				'offset' => $index * $limit,
				'limit'  => $limit
			) 
		);
		
		foreach ($chunk['data'] as $fri){
			$friends['friends'][] = array('name' => $fri['name'],
				'id' => $fri['id'],
				'avatar' => "https://graph.facebook.com/".$fri['id']."/picture"
			);
		}
		
		$chunk = Yii::app()->facebook->api(
			"/me/friends", 'GET',
			array(
				'offset' => 0,
				'limit'  => 5000 
			) 
		);
		
		$pager = new InviteLinkPager();
		$pager->initPager(count($chunk['data']), $limit);
		$friends['paging'] = $pager->getLinks();

		return $friends;
	}
}

?>