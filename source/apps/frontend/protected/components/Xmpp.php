<?php
class Xmpp {
	
	public $bindServer = '';
	public $server     = '';
	public $jid        = '';
	public $bareJid    = '';
	public $fullJid    = '';
	public $rid        = 0;
	public $sid        = '';
	
	public function __construct($bindServer, $server) {
		$this->bindServer = $bindServer;
		$this->server     = $server;
	}
	
	public function connect($jid, $password) {
		$this->jid      = $jid;
		$this->sid      = $this->_requestSid();
		$this->_requestStream();
		
		$this->bareJid = $this->jid.'@'.$this->server;
		$base64 = base64_encode($this->bareJid.chr(0).$this->jid.chr(0).$password);
		
		$this->_requestAuth($base64);
		$this->_requestRestart();
		$this->_requestBind();
		$this->_requestAuthSession();
		$this->_requestPresence();
		$this->_requestAutoSave();
	}
	
	private function _requestSid() {
		
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' to='".$this->server."' xml:lang='en' wait='60' hold='1' content='text/xml; charset=utf-8' ver='1.6' xmpp:version='1.0' xmlns:xmpp='urn:xmpp:xbosh'/>";
		
		$this->rid++;
		
		$response = $this->_sendBody($body);
		$xml      = new SimpleXMLElement($response);
		$sid      = (string) $xml['sid'];
		
		return $sid;
	}
	
	private function _requestStream() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'/>";
		$this->_sendBody($body);
	}
	
	private function _requestAuth($base64) {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'><auth xmlns='urn:ietf:params:xml:ns:xmpp-sasl' mechanism='PLAIN'>".$base64."</auth></body>";
		$this->_sendBody($body);
	}
	
	private function _requestRestart() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."' to='".$this->server."' xml:lang='en' xmpp:restart='true' xmlns:xmpp='urn:xmpp:xbosh'/>";
		$this->_sendBody($body);
	}
	
	private function _requestBind() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'><iq type='set' id='bind_auth' xmlns='jabber:client'><bind xmlns='urn:ietf:params:xml:ns:xmpp-bind'/></iq></body>";
		$response = $this->_sendBody($body);
		$xml      = new SimpleXMLElement($response);
		
		if(isset($xml->message))
			$this->_requestBind();
		else
			$this->fullJid = (String)$xml->iq->bind->jid;
	}
	
	private function _requestAuthSession() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'><iq type='set' id='session_auth' xmlns='jabber:client'><session xmlns='urn:ietf:params:xml:ns:xmpp-session'/></iq></body>";
		$this->_sendBody($body);
	}
	private function _requestPresence() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'><presence xmlns='jabber:client'/></body>";
		$this->_sendBody($body);
	}
	private function _requestAutoSave() {
		$body = "<body rid='".$this->_getRid()."' xmlns='http://jabber.org/protocol/httpbind' sid='".$this->sid."'><iq type='set' id='autoSave' xmlns='jabber:client'><auto save='true' xmlns='urn:xmpp:archive'/></iq></body>";
		$this->_sendBody($body);
	}
	
	public function _sendBody($body) {
		
		$curlInit = curl_init($this->bindServer);
		curl_setopt($curlInit, CURLOPT_HEADER, 0);
		curl_setopt($curlInit, CURLOPT_POST, 1);
		curl_setopt($curlInit, CURLOPT_POSTFIELDS, $body);
		curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curlInit);
		curl_close($curlInit);
		
		return $response;
	}
	
	private function _getRid() {
		$this->rid++;
		return $this->rid;
	}
}