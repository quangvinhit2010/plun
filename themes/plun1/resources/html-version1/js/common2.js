var startNum = 0;
var scrollNum = function(i) {
    $(".menu li").removeClass("current");
    $(".menu li").eq(i).addClass("current");
    if (i > startNum) {
        $(".abc").stop().animate({"top": -($(".small").height() * i) - 50}, 300, function() {
            $(".abc").animate({"top": -($(".small").height() * i)}, 500);
        });
    } else if (i < startNum) {
        $(".abc").stop().animate({
            "top": -($(".small").height() * i) + 50
        }, 300, function() {
            $(".abc").animate({"top": -($(".small").height() * i)
            }, 500);
        });
    } else {
        $(".abc").stop().animate({
            "top": -($(".small").height() * i)
        }, 300);
    }
    startNum = i;
};
var scrollFunc = function(e) {
    e = e || window.event;
    var mousevalue;
    if (e.wheelDelta) { //IE/Opera/Chrome 
        mousevalue = e.wheelDelta / -120;
    } else if (e.detail) { //Firefox 
        mousevalue = e.detail / 3;
    }
    newNum = startNum + mousevalue;
    if (newNum < 0) {
        newNum = 0;
        return;
    } else if (newNum >= $(".menu li").length) {
        newNum = $(".menu li").length - 1;
        return;
    } else {
        scrollNum(newNum);
    }
};
document.getElementById("allmain").onmousewheel = scrollFunc; //IE/Opera/Chrome 
if (document.getElementById("allmain").addEventListener) {
    document.getElementById("allmain").addEventListener('DOMMouseScroll', scrollFunc, false);
}