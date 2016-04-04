// JavaScript Document

var isWindowPhone = /Windows/i.test(navigator.userAgent.toLowerCase());
if(isWindowPhone){
	//alert('WindowPhone is true');
	$('head').append('<link rel="stylesheet" href="css/mobile_win_phone.css" type="text/css" />');
}


