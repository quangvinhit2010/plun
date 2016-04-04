<?php 
class AccessTokenController extends Controller
{
	public function beforeAction($action) {
			$oauth = new PDOOAuth2();
			$oauth->clearAccessToken();
		return parent::beforeAction($action);
	}
	//Access Token Generator (param: client_id and client_secret
	public function actionIndex(){
		if(Yii::app()->request->isPostRequest){
			$client_id = Yii::app()->request->getPost('client_id');
			$client_secret = Yii::app()->request->getPost('client_secret');
			$oauth = new PDOOAuth2();
			if(isset($client_id) && isset($client_secret)){
				$client_id = Util::decryptRandCode($client_id);
				$client_secret = Util::decryptRandCode($client_secret);
				$client = Clients::model()->findByAttributes(array('client_id'=>$client_id));
				$check = $oauth->checkClientCredentials($client_id, $client_secret);
				if($check == true && $client->client_ip === Yii::app()->request->userHostAddress){
					$tokens = $oauth->createAccessToken($client_id);
					if(isset($tokens)){
						echo CJSON::encode($tokens);
						Yii::app()->end();
					} else {
						return false;
					}
				}
			} else {
				$oauth->errorJsonResponse(OAUTH2_HTTP_BAD_REQUEST, OAUTH2_ERROR_INVALID_REQUEST);
			}
		}
	}
}

?>