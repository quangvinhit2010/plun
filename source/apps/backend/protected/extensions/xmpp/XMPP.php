<?php

/**
 * XMPP Class File * 
 * 
 * @author Nam
 * EJabberSender helps send a message with Jabber protocol.
 *
 * EJabberSender encapsulates the {@link http://code.google.com/p/xmpphp/ 
 * PHP XMPP Library}.
 *
 * To use this component, you may insert the following code in a config file:
 * <pre>
 * 'components'=>array(
 *      ...
 *      'XMPP'=>array(
 *          'class' => 'application.extensions.xmpp.XMPP',
 *          'host' => 'talk.google.com',
 *          'port' => 5222,
 *          'user' => 'username',
 *          'password' => '*******',
 *          'server' => 'gmail.com'
 *      ),
 *      ...
 * )
 * </pre>
 * 
 * And in controller use the code
 * <pre>
 *      Yii::app()->XMPP->sendMessage('user@gmail.com', 'Test, test, test');
 * </pre>
 *
 */
class XMPP extends CApplicationComponent 
{
	
	/**
	 * @var string httpbind
	 */
	public $httpbind;
	/**
	 * @var string jabber host
	 */
	public $host;
	
	/**
	 * @var int port
	 */
	public $port = 5222;
	
	/**
	 * @var string user name
	 */
	public $user;
	
	/**
	 * @var string user password
	 */
	public $password;
	
	/**
	 * @var string server
	 */
	public $server;
	
	/**
	 * @var boolean use encryption
	 */
	public $useEncryption = true;
	
	/**
	 * @var XMPPHP_XMPP connection handler
	 */
	private $conn;
	
	
	public function __destruct() {
		if($this->conn)
			$this->conn->disconnect();
			$this->conn = NULL;
	}
	
	private function _connect(){
		
		if(Yii::app()->user->isGuest) {
			throw new CException( Yii::t('XMPP', 'You must login to use this extension.') );
		} else {
				
				$user = Yii::app()->user->data();
				if($user->status == 1) {
					$this->user = $user->username;
					$this->password = $user->chat_key;
					$this->host = Yii::app()->params['XMPP']['server'];
					$this->port = Yii::app()->params['XMPP']['port'];
					try {
						Yii::import('backend.extensions.xmpp.XMPPHP.XMPPHP_XMPP');
						$this->conn = new XMPPHP_XMPP($this->host, $this->port, $this->user, $this->password, false, $this->server, false, 3);
						$this->conn->useEncryption($this->useEncryption);
						$this->conn->connect();
						$this->conn->processUntil('session_start');
						$this->conn->presence($status = "Avalible");
					} catch(XMPPHP_Exception $e) {
						die($e->getMessage());
					}
				}
		}
		
		
	}
	
	
	/**
	 * This method sends message
	 * @param string $to
	 * @param string $message
	 */
	public function sendMessage($to, $message){
		$to = $to.'@'.$this->host;
		$this->_connect();
		try {
			$this->conn->message($to, $message);
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
	}
	
	
	
	public function addRoster($to){
		$to = $to.'@'.$this->host;
		$this->_connect();
		try {
			$this->conn->subscribe($to);
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
		
	}
	
	public function removeRoster($to){
		$to = $to.'@'.$this->host;
		$this->_connect();
		try {
			$this->conn->remove($to);
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
	
	}
	
	public function confirmRoster($to){
		$to = $to.'@'.$this->host;
		$this->_connect();
		try {
			$this->conn->subscribed($to);
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
	
	}
	
	public function getRosters(){
		//$this->_connect();
		$lists = YumFriendship::getFriendList(Yii::app()->user->id);
		$friends = array();
		foreach ($lists as $value){
			$friends['username'] = $value->invited->username; 
		}
		return $friends;
	}
	
	public function logout(){
		if(isset($this->conn)){
			return $this->conn->disconnect();
		}
	}
	
}

?>