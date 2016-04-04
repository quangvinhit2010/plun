<?php

require_once ('OAuth2/OAuth2.inc');
Yii::import('backend.modules.api.models.*');

/**
 * OAuth2 Library PDO DB Implementation.
 */
class PDOOAuth2 extends OAuth2 {
  /**
   * Little helper function to add a new client to the database.
   *
   * Do NOT use this in production! This sample code stores the secret
   * in plaintext!
   *
   * @param $client_id
   *   Client identifier to be stored.
   * @param $client_secret
   *   Client secret to be stored.
   * @param $redirect_uri
   *   Redirect URI to be stored.
   */
  public function addClient($client_id, $client_secret, $redirect_uri) {
    try {
    	$client = new Clients();
    	$client->client_id = $client_id;
    	$client->client_secret = $client_secret;
    	$client->redirect_uri = $redirect_uri;
    	$client->save();
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::checkClientCredentials().
   *
   * Do NOT use this in production! This sample code stores the secret
   * in plaintext!
   */
  public function checkClientCredentials($client_id, $client_secret = NULL) {
    try {
    	$client = new Clients();
    	$result = $client->findByAttributes(array('client_id' => $client_id));
	    if(isset($result) && $result->client_status == 1 && $result->client_secret == $client_secret){
			return true;
		} else {
			//$this->errorJsonResponse(OAUTH2_HTTP_FOUND, OAUTH2_ERROR_INVALID_CLIENT);
			echo CJSON::encode(array('error' => OAUTH2_ERROR_INVALID_CLIENT));
			
		}
      
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::getRedirectUri().
   */
  protected function getRedirectUri($client_id) {
    try {
		$client = new Clients();
    	$result = $client->findByAttributes(array('client_id' => $client_id));
	      if ($result === FALSE)
	          return FALSE;

      return isset($result->redirect_uri) && $result->redirect_uri ? $result->redirect_uri : NULL;
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::getAccessToken().
   */
  public function getAccessToken($oauth_token) {
  	$tokens = new Tokens();
    try {
    	$tokens = new Tokens();
    	$result = $tokens->findByAttributes(array('oauth_token' => $oauth_token));
      	return $result !== FALSE ? $result : NULL;
      	
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::setAccessToken().
   */
  protected function setAccessToken($oauth_token, $client_id, $expires, $scope = NULL) {
    try {
    	$tokens = new Tokens();
    	$tokens->oauth_token = $oauth_token;
    	$tokens->client_id = $client_id;
    	$tokens->expires = $expires;
    	$tokens->scope	= $scope;
    	$tokens->save();
    	
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }
  
  /**
   * Implements OAuth2::clearAccessToken().
   */
  public function clearAccessToken() {
    try {
    	//clear token
    	Tokens::model()->deleteAll('expires < '.(time() - 3600));
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Overrides OAuth2::getSupportedGrantTypes().
   */
  protected function getSupportedGrantTypes() {
    return array(
      OAUTH2_GRANT_TYPE_AUTH_CODE,
    );
  }

  /**
   * Overrides OAuth2::getAuthCode().
   */
  protected function getAuthCode($code) {
    try {
      	$auth_codes = new AuthCodes();
    	$result = $auth_codes->findByAttributes(array('code' => $code));
      	return $result !== FALSE ? $result : NULL;
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Overrides OAuth2::setAuthCode().
   */
  protected function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL) {
    try {
    	$auth_codes = new AuthCodes();
    	$auth_codes->code = $code;
    	$auth_codes->client_id = $client_id;
    	$auth_codes->redirect_uri = $redirect_uri;
    	$auth_codes->expires	= $expires;
    	$auth_codes->scope	= $scope;
    	$auth_codes->save();
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }
}
