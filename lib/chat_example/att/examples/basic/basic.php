<?php



/* if( !(isset($_COOKIE['boshJid'])
        &&
    isset($_COOKIE['boshSid'])
        &&
    isset($_COOKIE['boshRid'])
        &&
    isset($_COOKIE['boshUrl']))){ */
    
	
	require '../../lib/XmppBosh.php';

    $boshUrl = 'http://test.plun.asia:5222/http-bind'; // BOSH url
    $domain = 'test.plun.asia';                    // XMPP host

    
    
    
    
    $xmppBosh = new XmppBosh($domain, $boshUrl,  '', false, false);
    $node = 'alex0511';         // Without @example.com
    $password = 'f5cdc91cb70ea71a8e9c61c7604d1aab4dd017d72f1398b9ec38596f0821a30580e9e76d75556747e0dd271e9cf13d86d99dc54b8e0f37915068900c6c8e5839';
    $xmppBosh->connect($node, $password);
   
	
    $boshSession = $xmppBosh->getSessionInfo();

    setcookie('boshJid', $boshSession['jid'], 0, '/');
    setcookie('boshSid', $boshSession['sid'], 0, '/');
    setcookie('boshRid', $boshSession['rid'], 0, '/');
    setcookie('boshUrl', $boshSession['url'], 0, '/');
/* } */

?>
<html>
    <head>
        <title>Basic XMPP connection</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
        <script type="text/javascript" src='https://raw.github.com/carhartl/jquery-cookie/master/jquery.cookie.js'></script>
        <script type="text/javascript" src='../../js/XmppBosh.js'></script>
        <script type="text/javascript" src='basic.js'></script>
    </head>

    <body>
        <button id="disconnect">Disconnect</button>
        <br>

        <div id="log">
        </div>
    </body>
</html>