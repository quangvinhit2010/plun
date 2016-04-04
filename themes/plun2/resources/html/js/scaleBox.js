var wRealBox = 0;
function boxScale(wRealBox, $parentWrap){
	var $Parent = $parentWrap.find('> ul'),
		$boxImg = $Parent.find('> li'),
		wParent = $parentWrap.outerWidth(),
		numMargin = 10,//gia tri margin left or right items
		numCol = Math.floor(wParent/(wRealBox+numMargin)),// so collumn vs width hien tai cua parent element
		wRest = wParent - (wRealBox + numMargin)*numCol,
		wBoxAdd = wRealBox + numMargin - wRest;
	wRealBox = wRealBox - Math.floor(wBoxAdd/numCol);

	if(wParent > (wRealBox+numMargin)*(numCol + 1) ){
		wRealBox += (wParent - (wRealBox+numMargin)*(numCol + 1))/(numCol + 1) + numMargin/(numCol + 1);
	}
	
	$boxImg.css({
		width: wRealBox+'px'
	});

	$('.list_explore .list_user ul li .wrap-img a').css({
		width: wRealBox+'px',
		height: wRealBox+'px'
	});
	
	setTimeout(function(){
		$boxImg.css({
			visibility: 'visible'
		}).hide().fadeIn(300);
	},50);
	
}
var test = setInterval(function(){
	if($('.wrap_scale_box').length > 0){
		var $parentWrap = $('.wrap_scale_box');
		wRealBox = $parentWrap.find('> ul > li').outerWidth();
		boxScale(wRealBox, $parentWrap);	
		clearInterval(test);
	}else{
		clearInterval(test);
	}
}, 100);
$(window).resize(function(e){
	var $parentWrap = $('.wrap_scale_box').length > 0 ? $('.wrap_scale_box') : null;
	setTimeout(function(){
		boxScale(wRealBox, $parentWrap);
	},200);
	
});