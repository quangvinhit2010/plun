<?php 
Yii::import('backend.extensions.yii-eauth.EAuthUserIdentity');
Yii::import('backend.extensions.google-api-v2.apiClient');
Yii::import('backend.extensions.google-api-v2.contrib.apiOauth2Service');
		
class FrontendController extends Controller {
	public function actionIndex(){
		$inviteModel = new InviteModel();
		$inviteForm = new InviteForm();
		$user = Yii::app()->user;
		if ($user->isGuest){
			$this->render('guest');
		}else{
			$this->render('index', array('inviteForm'=>$inviteForm));
		}
	}
	
	public function actionGetFriends($connect_type){
		$invite = new  InviteOpenId();
		if(!empty($connect_type)){
			switch ($connect_type){
				case "twitter":
					if (!empty($_SERVER['QUERY_STRING'])){
					    if (isset($_REQUEST['oauth_token']) && Yii::app()->session['oauth_token'] !== $_REQUEST['oauth_token']) {
                            Yii::app()->session['oauth_status'] = 'oldtoken';
                        }
                        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
                        $twitter = Yii::app()->twitter->getTwitterTokened(Yii::app()->session['oauth_token'], Yii::app()->session['oauth_token_secret']);
                    
                        /* Request access tokens from twitter */
                        try {
                            $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
                        } catch (Exception $e) {
                        }
                    
                        /* Save the access tokens. Normally these would be saved in a database for future use. */
                        Yii::app()->session['access_token'] = $access_token;
                    
                        /* Remove no longer needed request tokens */
                        unset(Yii::app()->session['oauth_token']);
                        unset(Yii::app()->session['oauth_token_secret']);
                    
                        if (200 == $twitter->http_code) {
                            /* The user has been verified and the access tokens can be saved for future use */
                            Yii::app()->session['status'] = 'verified';
                            //get an access twitter object
                            $twitter = Yii::app()->twitter->getTwitterTokened($access_token['oauth_token'],$access_token['oauth_token_secret']);
                            //get friends ids
                            $friends= $twitter->get("friends/ids");
                            $out = $this->render('twitter_friends',array('friends'=>$friends, 'twitter'=>$twitter), false);
                        }
					}else{
						$invite->loginOpendId($connect_type);
					}
					break;
				case "facebook":
					$fb_uid = Yii::app()->facebook->getUser();
					if (!empty($_SERVER['QUERY_STRING'])){
						if (!isset($_REQUEST['success'])){
							$url = "/invitation/frontend/getFriends/connect_type/" . $connect_type . "?success=1&" . $_SERVER['QUERY_STRING'];
							$script = "<script type='text/javascript'> self.close(); window.opener.getFriends('$url'); </script>";
							echo $script;
							exit();
						}else{
							$result = array('status' => false);
							$data = $invite->getFriendsFacebook();
							if ($data){
								$result = array('status' => true, 'type' => 'facebook');
								$result['html'] = ($this->renderPartial('facebook_friends', array('friends' => $data['friends']), true));
								$result['paging'] = ($data['paging']);
							}
							echo json_encode($result);
						}
					}else{
						$invite->loginOpendId($connect_type);
					}
					break;
				case "google":
					if (!empty($_GET['oauth_token'])) {
						$friends = $invite->getFriendsGoogle();	
						$out = ($this->renderPartial('google_friends', array('friends' => $friends), true));					
						$url = "/invitation/frontend/getFriends/connect_type/" . $connect_type . "?success=1&" . $_SERVER['QUERY_STRING'];						
						$script = "<script type='text/javascript'> self.close(); window.opener.$('#g_friends .friends').html('".$out."');</script>";
						echo $script;
						exit();
					}else{
						$invite->loginOpendId($connect_type);
					}
					break;
				case "yahoo":
				    $this->render('yahoo_friends',array(), false);
				    break;
			}
		}
		exit();
	}
	
	public function actionInvite(){
		$request = Yii::app()->request;
		$user = Yii::app()->user;
		$InviteForm = $request->getParam('InviteForm');
		
		if(!empty($InviteForm) && !$user->isGuest){
				$exist = UsrInvitations::model()->findByAttributes(array('email'=>$InviteForm['email'], 'user_id'=>$user->id));
				if(empty($exist) && !empty($user->id)){
					$inv = new UsrInvitations();
					$inv->user_id = $user->id;
					$inv->email = trim($InviteForm['email']);
					$inv->created = time();
					$inv->validate();
					if(!$inv->hasErrors()){
						if($inv->save()){
// 							$body = $this->renderPartial('email_invite',array('user' => $user), true);

						    $userdata = $user->data();
						    $userSetting = $userdata->profile_settings;
						    $role	=	ProfileSettingsConst::getSexRoleLabel();
						    $relationship	=	ProfileSettingsConst::getRelationshipLabel();
						    $arr = array(
						            'yo'=> !empty($userSetting->birthday) ? Util::getAge(date('d/m/Y', $userSetting->birthday)).' y.o' : '',
						            'sex_role'=> !empty($userSetting->sex_role) ? $role[$userSetting->sex_role] : '',
						            'relationship'=> !empty($userSetting->relationship) ? $relationship[$userSetting->relationship] : '',
						    );
							$body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/join-us',array('user'=>$userdata, 'lblProfile'=>implode(' | ', $arr)), true);
							$subject	=	Lang::t('invitation', 'Join me on PLUN');
							return Mailer::send ( $InviteForm['email'],  $subject, $body);;
						}
					}
				}
			
		}
	}
	
	public function actionGiftcode(){
		$inviteModel = new InviteModel();
		$InviteBonus = $inviteModel->getGiftcode(5966);
		$this->render('giftcode', array('InviteBonus'=>$InviteBonus));
	}
	
	public function actionStatus(){
		$code = Yii::app()->request->getParam('code');
//		$code = Util::encryptRandCode(1);
		$hisID = Util::decryptRandCode($code);
		if (!empty($hisID)){
			$history = InviteHistory::model()->findByPk($hisID);
			if(!empty($history) && $history->status == 1){
				$history->status = 2;
				$history->save();
			}
		}
		$url = Yii::app()->createUrl('/invitation/frontend');
		$this->redirect($url);
	}
	//view more google friends
	public function actionGetFriendslistgoogle(){
		$usercurrent = Yii::app()->user->data();
		
		$offset	=	Yii::app()->request->getPost('offset');
		$limit	=	Yii::app()->params['googleapi']['limit'];
		
        $session=new CHttpSession;
		$session->open();
		
		$client = new apiClient();
		$client->setClientId(Yii::app()->params['googleapi']['client_id']);
		$client->setClientSecret(Yii::app()->params['googleapi']['client_secret']);
		$client->setRedirectUri(Yii::app()->createAbsoluteUrl('/invitation/frontend/GetFriendsGoogle'));
		$client->setUseObjects(true);	
		$client->setScopes('https://www.google.com/m8/feeds');	
		if(json_decode($session->get('google_token'))){
			$client->setAccessToken($session->get('google_token'));
		}		
		$req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=500");
		$val = $client->getIo()->authenticatedRequest($req);	
		
		//get contacts from api google
		$response		=	$val->getResponseBody();
		$api_response	=	json_decode($response, true);
		$contacts	=	array('total'	=>	0);
        if(isset($api_response['feed']['entry'])){
			//convert api response to php array
			$contacts	=	Contactsfilter::google_filter($api_response['feed']['entry']);
		}
		$result = array('status' => true, 'type' => 'google');
		
		if($contacts['total']){
			$search = new LucenseSearch();
			$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
				
			$result['total']	=	sizeof($searchfriends_in_system['data']);
			$result['limit']	=	$limit;
			$result['next_offset']	=	$offset + $limit; // for next offset
			if($offset >= $result['total']){
				$result['end']	=	'1';
			}else{
				$result['end']	=	'0';
			}
			$result['html']	= $this->renderPartial('google_friends_seemore',array('contacts'	=>	$searchfriends_in_system['data'], 'offset' => $offset, 'limit' => $limit, 'total' => $result['total']), true);
		}
		echo json_encode($result);
		exit;		
	}	
	//view more google contact
	public function actionGetContactGoogle(){
		$usercurrent = Yii::app()->user->data();
		$invitation_sent	=	UsrInvitations::model()->getInvitationSent($usercurrent->id);
		
		$offset	=	Yii::app()->request->getPost('offset');
		$limit	=	Yii::app()->params['googleapi']['limit'];
		
        $session=new CHttpSession;
		$session->open();
		
		$client = new apiClient();
		$client->setClientId(Yii::app()->params['googleapi']['client_id']);
		$client->setClientSecret(Yii::app()->params['googleapi']['client_secret']);
		$client->setRedirectUri(Yii::app()->createAbsoluteUrl('/invitation/frontend/GetFriendsGoogle'));
		$client->setUseObjects(true);	
		$client->setScopes('https://www.google.com/m8/feeds');	
		if(json_decode($session->get('google_token'))){
			$client->setAccessToken($session->get('google_token'));
		}		
		$req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=500");
		$val = $client->getIo()->authenticatedRequest($req);	
		
		//get contacts from api google
		$response		=	$val->getResponseBody();
		$api_response	=	json_decode($response, true);
		$contacts	=	array('total'	=>	0);
        if(isset($api_response['feed']['entry'])){
			//convert api response to php array
			$contacts	=	Contactsfilter::google_filter($api_response['feed']['entry']);
		}
		$result = array('status' => true, 'type' => 'google');
		
		if($contacts['total']){
			$search	=	new Elasticsearch();
			$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
				
			$contacts_email	=	array_diff($contacts['result'], $searchfriends_in_system['email_friends']);				 
			$result['total']	=	sizeof($contacts_email);
			$result['limit']	=	$limit;
			$result['next_offset']	=	$offset + $limit; // for next offset
			if($offset >= $result['total']){
				$result['end']	=	'1';
			}else{
				$result['end']	=	'0';
			}
			$result['html']	= $this->renderPartial('google_contacts',array('contacts'	=>	$contacts_email, 'offset' => $offset, 'limit' => $limit, 'total' => $result['total'], 'invitation_sent' => $invitation_sent), true);
		}
		echo json_encode($result);
		exit;		
	}
	/**
	 * get contacts list from Google account
	 */	
	public function actionGetFriendsGoogle(){
		$limit	=	Yii::app()->params['googleapi']['limit'];
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $usercurrent = Yii::app()->user->data();
        
        $invitation_sent	=	UsrInvitations::model()->getInvitationSent($usercurrent->id);
        
        $session=new CHttpSession;
		$session->open();
		
		$client = new apiClient();
		$client->setClientId(Yii::app()->params['googleapi']['client_id']);
		$client->setClientSecret(Yii::app()->params['googleapi']['client_secret']);
		$client->setRedirectUri(Yii::app()->createAbsoluteUrl('/invitation/frontend/GetFriendsGoogle'));
		$client->setUseObjects(true);	
		$client->setScopes('https://www.google.com/m8/feeds');	
		if(json_decode($session->get('google_token'))){
			$client->setAccessToken($session->get('google_token'));
		}
		
		//if user press cancel buttom
		if (isset($_REQUEST['error'])){
			echo '<script type="text/javascript"> self.close();</script>';
			exit;
		}

        //return result from ajax request
		if(isset($_REQUEST['result'])){
			$req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=500");
			$val = $client->getIo()->authenticatedRequest($req);

			//get contacts from api google
			$response		=	$val->getResponseBody();
			$api_response	=	json_decode($response, true);
			$contacts	=	array('total'	=>	0);

            if(isset($api_response['feed']['entry'])){
				//convert api response to php array
				$contacts	=	Contactsfilter::google_filter($api_response['feed']['entry']);
			}
			$result = array('status' => false);
			if($contacts['total']){
				//$search = new LucenseSearch();
				//$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
				$search	=	new Elasticsearch();
				$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
				$contacts_email	=	array_diff($contacts['result'], $searchfriends_in_system['email_list']);
				
				//paging
			    //$page	=	isset($_GET['page'])	?	intval($_GET['page'])	:	1;
				//$pager = new InviteLinkPager();
				//$pager->initPager($contacts['total'], Yii::app()->params['googleapi']['limit']);
				
				 $result = array('status' => true, 'type' => 'google');
				 
				 $result['html'] = ($this->renderPartial('google_friends', 
				 	array(
				 		//'total' => $contacts['total'],
				 		//'page' => $page, 
				 		'contacts'	=>	array_slice($contacts_email, 0, $limit),
				 		'invitation_sent'	=>	$invitation_sent,
				 		'city_info' => $city_in_cache->getListCity(),
				 		'country_info' => $country_in_cache->getListCountry(), 
				 		'limit'	=> $limit,
				 		'friends' => $searchfriends_in_system), true
				 	)
				 );
				//$result['paging'] = $pager->getLinks();
			}
			echo json_encode($result);	
			exit;
		}
		
		//check google authentication
		try {
			//get google login token
			if ($client->authenticate()) {
				$session->add('google_token', $client->getAccessToken());
			}
		} catch (Exception $e) {
			
		}
			//end check facebook authentication
		
		if (empty($_REQUEST['result'])){
			echo '<script type="text/javascript"> self.close(); window.opener.getFriends("/invitation/frontend/GetFriendsGoogle/result/success", "google");</script>';
		}		
		exit;		
	}
	
	//view more yahoo friends
	public function actionGetFriendslistyahoo(){
		Yii::import('backend.extensions.yahoo-yos-social.YahooSession');
		
		$YahooSession	=	new YahooSession();
		
		$yahoo_auth	=	$YahooSession->hasSession(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], Yii::app()->params['yahooapi']['yahoo_app_id']);
		$auth_url = $YahooSession->createAuthorizationUrl(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], YahooUtil::current_url() . "?in_popup");
		
		$offset	=	Yii::app()->request->getPost('offset');
		$limit	=	Yii::app()->params['yahooapi']['limit'];
		
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $usercurrent = Yii::app()->user->data();
		
        if (!$yahoo_auth) {
            $this->redirect($auth_url);
        } else 
       {
	            $session = $YahooSession->requireSession(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], Yii::app()->params['yahooapi']['yahoo_app_id']);
	            $user = $session->getSessionedUser(); 
	            //get first contact to fetch total contact
		        $first_contact	=	$user->getContacts(0,1);
		        $contacts	=	array('total'	=>	0);
				if($first_contact->contacts->total){
					$contacts	=	$user->getContacts(0,$first_contact->contacts->total);
					//convert api response to php array
					$contacts	=	Contactsfilter::yahoo_filters($contacts->contacts->contact);
				}	        
				
				$result = array('status' => false);
				if($contacts['total']){
					$search	=	new Elasticsearch();
					$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
						
					$result['total']	=	sizeof($searchfriends_in_system['data']);
					$result['limit']	=	$limit;
					$result['next_offset']	=	$offset + $limit; // for next offset
					if($offset >= $result['total']){
						$result['end']	=	'1';
					}else{
						$result['end']	=	'0';
					}
					$result['html']	= $this->renderPartial('yahoo_friends_seemore',array('contacts'	=>	$searchfriends_in_system['data'], 'offset' => $offset, 'limit' => $limit, 'total' => $result['total']), true);
				}
				echo json_encode($result);
				exit;
        }
      	exit;		
	}
	//view more yahoo contact
	public function actionGetContactYahoo(){
				
		Yii::import('backend.extensions.yahoo-yos-social.YahooSession');
		
		$YahooSession	=	new YahooSession();
		
		$yahoo_auth	=	$YahooSession->hasSession(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], Yii::app()->params['yahooapi']['yahoo_app_id']);
		$auth_url = $YahooSession->createAuthorizationUrl(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], YahooUtil::current_url() . "?in_popup");
		
		$offset	=	Yii::app()->request->getPost('offset');
		$limit	=	Yii::app()->params['yahooapi']['limit'];
		
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $usercurrent = Yii::app()->user->data();
		
        $invitation_sent	=	UsrInvitations::model()->getInvitationSent($usercurrent->id);
        
        if (!$yahoo_auth) {
            $this->redirect($auth_url);
        } else 
       {
	            $session = $YahooSession->requireSession(Yii::app()->params['yahooapi']['yahoo_key'], Yii::app()->params['yahooapi']['yahoo_secret'], Yii::app()->params['yahooapi']['yahoo_app_id']);
	            $user = $session->getSessionedUser(); 
	            //get first contact to fetch total contact
		        $first_contact	=	$user->getContacts(0,1);
		        $contacts	=	array('total'	=>	0);
				if($first_contact->contacts->total){
					$contacts	=	$user->getContacts(0,$first_contact->contacts->total);
					//convert api response to php array
					$contacts	=	Contactsfilter::yahoo_filters($contacts->contacts->contact);
				}	        
				
				$result = array('status' => false);
				if($contacts['total']){
					$search	=	new Elasticsearch();
					$searchfriends_in_system	=	$search->findFriendByEmail($contacts['result'], array($usercurrent->id));
						
					$contacts_email	=	array_diff($contacts['result'], $searchfriends_in_system['email_friends']);				 
					$result['total']	=	sizeof($contacts_email);
					$result['limit']	=	$limit;
					$result['next_offset']	=	$offset + $limit; // for next offset
					if($offset >= $result['total']){
						$result['end']	=	'1';
					}else{
						$result['end']	=	'0';
					}
					$result['html']	= $this->renderPartial('yahoo_contacts',array('contacts'	=>	$contacts_email, 'offset' => $offset, 'limit' => $limit, 'total' => $result['total'], 'invitation_sent' => $invitation_sent), true);
				}
				echo json_encode($result);
				exit;
        }
      	exit;		
	}	
	/**
	 * get contacts list from Yahoo account
	 */
	public function actionGetFriendsYahoo(){
		$offset = (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : 0;
		$limit = Yii::app()->params['yahooapi']['limit'];
		
		Yii::import('backend.extensions.yahoo-oauth-q.YahooOauthQ');
		$OAUTH_CONSUMER_KEY = Yii::app()->params['yahooapi']['yahoo_key'];
		$OAUTH_CONSUMER_SECRET = Yii::app()->params['yahooapi']['yahoo_secret'];
		$OAUTH_DOMAIN = Yii::app()->params['yahooapi']['oauth_domain'];
		$OAUTH_APP_ID = Yii::app()->params['yahooapi']['yahoo_app_id'];
		
		$yahooOauthQ = new YahooOauthQ($OAUTH_CONSUMER_KEY, $OAUTH_CONSUMER_SECRET, $OAUTH_APP_ID, $OAUTH_DOMAIN);
		
		
		$limit	=	Yii::app()->params['yahooapi']['limit'];
        $city_in_cache = new CityonCache();
        $country_in_cache   =   new CountryonCache();
        $usercurrent = Yii::app()->user->data();
        
        $invitation_sent	=	UsrInvitations::model()->getInvitationSent($usercurrent->id);
		
        if(!isset($_REQUEST['step'])) {
        	$call_back_url = $this->createAbsoluteUrl('/invitation/frontend/GetFriendsYahoo', array('step'=>'setaccesstoken'));
        	$openid_url = $yahooOauthQ->getOpenIDUrl($call_back_url);
            $this->redirect($openid_url);
        } else 
        {
        	if($_REQUEST['step']=='setaccesstoken') {
        		$yahooOauthQ->setRequestToken($_REQUEST['openid_oauth_request_token']);
        		
        		echo '<script type="text/javascript"> self.close(); window.opener.getFriends("/invitation/frontend/GetFriendsYahoo/step/getcontact", "yahoo");</script>';
        	} else if($_REQUEST['step']=='getcontact') {
        		
        		$contacts 	= $yahooOauthQ->getYahooContacts($offset, 1000);
				
        		//$contacts	=	Contactsfilter::yahoo_filters($contacts);
				$result = array('status' => false);
				if(sizeof($contacts)){
					
					$search	=	new Elasticsearch();
					$searchfriends_in_system	=	$search->findFriendByEmail($contacts, array($usercurrent->id));

					
					
					$contacts_email	=	array_diff(Contactsfilter::yahoo_filters($contacts), $searchfriends_in_system['email_list']);
					
					 $result = array('status' => true, 'type' => 'yahoo');
					 
					 $result['html'] = ($this->renderPartial('google_friends', 
					 	array(
					 		//'total' => $contacts['total'],
					 		//'page' => $page, 
					 		'contacts'	=>	array_slice($contacts_email, 0, 1000),
					 		'invitation_sent'	=>	$invitation_sent,
					 		'city_info' => $city_in_cache->getListCity(),
					 		'country_info' => $country_in_cache->getListCountry(), 
					 		'limit'	=> $limit,
					 		'friends' => $searchfriends_in_system), true
					 	)
					 );
				}
				echo json_encode($result);
				exit;
				
				
				
        	}
        }
      	exit;
	}	
}

?>