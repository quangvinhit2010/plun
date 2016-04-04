<?php
session_start();
header('content-type', 'plain/text');
// activate full error reporting
//error_reporting(E_ALL & E_STRICT);

include '../XMPPHP/BOSH.php';
//print "<pre>";

#Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
#If this doesn't work, are you running 64-bit PHP with < 5.2.6?
//$conn = new XMPPHP_BOSH('test.plun.asia', 5280, 'alex0511', 'macketui', '', 'test.plun.asia', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_DEBUG);
$conn = new XMPPHP_BOSH('test.plun.asia', 5222, 'alex0511', 'f5cdc91cb70ea71a8e9c61c7604d1aab4dd017d72f1398b9ec38596f0821a30580e9e76d75556747e0dd271e9cf13d86d99dc54b8e0f37915068900c6c8e5839', '', 'test.plun.asia', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_DEBUG);
$conn->autoSubscribe();


try {
	/* if(isset($_SESSION['messages'])) {
		foreach($_SESSION['messages'] as $msg) {
			print $msg;
			flush();
		}
	} */
	$conn->connect('http://test.plun.asia:7070/http-bind/', 1, true);
	
	#while(true) {
			$payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'),5);
			/* foreach($payloads as $event) {
				$pl = $event[1];
				switch($event[0]) {
					case 'message': 
						if(!isset($_SESSION['messages'])) $_SESSION['message'] = Array();
						$msg = "---------------------------------------------------------------------------------\n{$pl['from']}: {$pl['body']}\n";
						print $msg;
						$_SESSION['messages'][] = $msg;
						flush();
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
			} */
	#}
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}
$conn->saveSession();


echo '<pre>';
print_r($_SESSION);
echo '</pre>';

print "</pre>";
//print "<img src='http://xmpp.org/images/xmpp.png' onload='window.location.reload()' />";
exit();
?>
