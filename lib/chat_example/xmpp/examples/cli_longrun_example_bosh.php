<?php

// activate full error reporting
//error_reporting(E_ALL & E_STRICT);

include '../XMPPHP/BOSH.php';

#Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
#If this doesn't work, are you running 64-bit PHP with < 5.2.6?
//$conn = new XMPPHP_BOSH('server.tld', 5280, 'username', 'password', 'xmpphp', 'server.tld', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_VERBOSE);
$conn = new XMPPHP_BOSH('test.plun.asia', 5222, 'alex0511', 'f5cdc91cb70ea71a8e9c61c7604d1aab4dd017d72f1398b9ec38596f0821a30580e9e76d75556747e0dd271e9cf13d86d99dc54b8e0f37915068900c6c8e5839', '', 'test.plun.asia', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_INFO);
$conn->autoSubscribe();

try {
    //$conn->connect('http://server.tld:5280/xmpp-httpbind');
	$conn->connect('http://test.plun.asia:7070/http-bind/', 1, true);
	$conn->saveSession();
	print_r($_SESSION);
    while(!$conn->isDisconnected()) {
    	$payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'));
    	foreach($payloads as $event) {
    		$pl = $event[1];
    		switch($event[0]) {
    			case 'message': 
    				print "---------------------------------------------------------------------------------\n";
    				print "Message from: {$pl['from']}\n";
    				if($pl['subject']) print "Subject: {$pl['subject']}\n";
    				print $pl['body'] . "\n";
    				print "---------------------------------------------------------------------------------\n";
    				$conn->message($pl['from'], $body="Thanks for sending me \"{$pl['body']}\".", $type=$pl['type']);
    				if($pl['body'] == 'quit') $conn->disconnect();
    				if($pl['body'] == 'break') $conn->send("</end>");
    			break;
    			case 'presence':
    				print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
    			break;
    			case 'session_start':
    			    print "Session Start\n";
			    	$conn->getRoster();
    				$conn->presence($status="Cheese!");
    			break;
    		}
    	}
    }
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}
