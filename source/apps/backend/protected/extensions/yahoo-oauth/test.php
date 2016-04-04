<?php
  require("lib/Yahoo.inc");
  
  // When you replaced with your own keys here and once run this should go to Yahoo login page else some thing went wrong in your app registration and keys

  // Your Consumer Key (API Key) goes here.
  define('CONSUMER_KEY', "");

  // Your Consumer Secret goes here.
  define('CONSUMER_SECRET', "");

  // Your application ID goes here.
  define('APPID', "");

  $session = YahooSession::requireSession(CONSUMER_KEY,CONSUMER_SECRET,APPID);
?>