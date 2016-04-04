var position = 0;
var header_h = 0;
var up = false;
var myScroll = null;
var IsIChrome = navigator.userAgent.search("CriOS") >= 0 ? true : false;
var IsISafari = /safari/.test(navigator.userAgent.toLowerCase());
var IsIos = /(iPhone|iPod|iPad).*AppleWebKit/i.test(navigator.userAgent);
var timeout = null;
var expand = 0;

$(function () {
    //refreshScroller();
    //updateScrollerPosition();
    header_h = $("#wrapper_header").height();
   // if (IsIos) $("#wrapper_header").css("box-shadow", "0px 2px 5px black");
});

//document.addEventListener("touchmove", touchMove, false);
//document.addEventListener("scroll", Scroll, false);

function touchMove() {
    if (expand == 0) {
        var scroll = $(window).scrollTop();
        if (scroll > position) {
            if (scroll > header_h + 50) {
                $("#wrapper_header").hide();
                //$(".wrap_header").css("height", "100px");
                //$(".menuhtml").show();
                $("#wrapper_header").addClass("animated fadeInDown");
                //updateScrollerPosition();    
                iden1 = 0;
                up = false;
            }
        } else if (position > scroll + 5) {
            if ($(window).scrollTop() > header_h) {
                    if (iden1 == 0) {
                        //timeout = setTimeout(function () {
                            $("#wrapper_header").css("position", "fixed");
                            if (IsIChrome) {
                                $(".empty").show();
                            }
                            $("#wrapper_header").show();

                            iden1 = 1;
                            //myScroll.refresh();
                            up = true;
                        //}, 400);
                    } 
                
            }
            else {
                //$("#wrapper_header").css("top", "0");
                $(".empty").hide();
                //$("#wrapper_header").css("position", "");
                //$("#wrapper_header").removeClass("animated");
                //$("#wrapper_header").removeClass("fadeInDown");
                up = false;
            }
        }
        if (up && scroll <= 50) {
            $(".empty").hide();
        }
        position = scroll;
    }
}

function Scroll() {
    if (expand == 0) {
        var scroll = $(window).scrollTop();
        if (scroll > position) {
            if (scroll > header_h + 50) {
                $("#wrapper_header").hide();
                //$(".menuhtml").show();
                //$(".wrap_header").css("height", "100px");
                //updateScrollerPosition();    
                iden1 = 0;
                up = false;
            }
        } else if (position > scroll + 5) {
            if ($(window).scrollTop() > header_h) {
                if (iden1 == 0) {
                    $("#wrapper_header").css("position", "fixed");
                    if (IsIChrome) {
                        //                            $("#wrapper_header").css("top", "40px");
                        $(".empty").show();
                    }
                    $("#wrapper_header").show();
                    //$("#wrapper_header").addClass("animated fadeInDown");
                    iden1 = 1;
                    //myScroll.refresh();
                    up = true;
                }
            }
            else {
                //                    $("#wrapper_header").css("top", "0");
                //$("#wrapper_header").css("position", "");
                //$(".empty").hide();
                //$("#wrapper_header").removeClass("animated");
                //$("#wrapper_header").removeClass("fadeInDown");
                
                up = false;
            }
        }
        if (up && scroll <= 50) {
            $(".empty").hide();
        }
        position = scroll;
    }
}



function restoreMenuState() {
    $("#wrapper_header").animate({ "top": "0" }, 500);
    up = false;
}

var timeoutMenu = null;
function touchend(e) {
    if (IsIChrome) {
        
            if (timeoutMenu != null) clearTimeout(timeoutMenu);
            //timeoutMenu = setTimeout(restoreMenuState, 4000);
        
    }
}


//$(document).bind('touchmove', touch).bind('touchend', touchend).bind('scroll', touch);

/*window.onorientationchange = function () {
    var width = 480;
    if (typeof window.orientation != 'undefined') {
        if ($(window).width() >= 480) {
            $("#header").addClass('header_wide');
            $("#header_float").addClass('header_wide');
        }
        else {
            $("#header").removeClass('header_wide');
            $("#header_float").removeClass('header_wide');
        }
        fix_height = $(window).height();
        if (fix_height <= 320) {            
        $("#wrapper_header").css({ "position": "absolute", "top": "0" });
        }
        else {
        $("#wrapper_header").css({ "position": "fixed"});
        if ($(window).scrollTop() > 100) {
                
        }
        }
    }
}*/



function refreshScroller() {
    if (myScroll != null) {
        myScroll.destroy();
    }
    myScroll = new iScroll('menu', { hScroll: true, vScroll: false, hScrollbar: false, vScrollbar: false });
}

$(window).resize(function () {
    if (myScroll != null)
        myScroll.refresh();
});

