$(document).ready(function(){
	albumScroll();
	indexFaces();
	sprDropBlock();
	sprBorder();
	sprClose();
	sprSlide();
	sprh2Tips();
	sprCate();
	sprDropdown();
	albumTitle();
	toTop();
	tipSmall();
	moreSlide();
	actThumb();
	sprLike();
	cmtTabs();
	relatedAlbum();
	sprPager();
	sprShare();
	sprAlert();
	sprTopFav();
	$( "#slide_type input").button();
});
// Dropdown //
function sprDropBlock(){
			$(document).mouseup(function(event) {
				// alert($(event.target).closest(".active").get(0));
				if( $(event.target).closest(".active").get(0) == null)
				{
					$(".active .pop-notifications").fadeOut('fast');
					$(".note-area li").removeClass("active");
					$(".active .droplist").hide();
					$(".dropdown").removeClass("active");
					$(".active .top-links").hide();
					$(".top-setting").removeClass("active");
					$(".dropdown-active ul").hide();
					$(".small-dropdown").removeClass("dropdown-active");
					$(".e-block").hide();
					$(".e-block").removeClass("active");
					$(".embed-share").fadeOut('fast').removeClass("active");
					$(".window").hide();
					$(".window").removeClass("active");
					$(".others-wrap").hide();
					$(".others-wrap").removeClass("active");
				}
			});
			$(".note-area li a").click(function(){
			$(".pop-notifications").removeClass("active");
			$(this).closest("li").addClass("active").find(".pop-notifications").addClass("active").fadeToggle('fast');
			var x = $(".active .poplist li").length;
			if (x > 3 && !$(".active .poplist").hasClass("mCustomScrollbar"))
			{
				sprScroll();
			}
			});
			$(".dropdown-btn").click(function(){
				$(this).closest(".dropdown").addClass("active").find("ul").show();
			});
			$(".top-drop").click(function(){
				$(this).closest("li").addClass("active").find(".top-links").show();
			});
			$(".small-dropdown").live("click", function(event) {
				$(this).addClass("dropdown-active").find("ul").show();
			});
			$(".search-area").click(function(){
				$(this).val('');
			});
			$(".mblock .edit").click(function(){
				$(".mblock .e-block").hide();
				$(this).closest(".mblock").find(".e-block").addClass("active").show();
				$("textarea").autosize();
				$( ".checkbox").button();
			});
			$(".btn-window").click(function(){
				$(this).next(".window").addClass("active").show();
			});
			$(".others").click(function(){
				$(this).next(".others-wrap").addClass("active").show();
			});
}
// remove border last li //
function sprBorder(){
	$("div.ft-column:last").addClass("noborder");
}
// alert close //
function sprClose(){
	$(".close").click(function(){
		$(".close").closest("div").fadeOut('fast');
	});
}
function sprSlide(){
	$(".slidebox").mSlidebox({
	autoPlayTime:7000,
    animSpeed:1000,
    easeType:"easeInOutQuint",
    controlsPosition:{
    buttonsPosition:"outside",
    thumbsPosition:"outside"
    },
    pauseOnHover:false,
    numberedThumbnails:false
	});
}
function sprh2Tips(){
	$(".tips-info").hover(function(){
		$(this).find("div").fadeIn('fast');
	},function()
	{$(this).find("div").fadeOut('fast');
	});
}
function sprCate(){
	$(".category a").each(function(index){
		var newClass = "cate" + index;
		$(this).addClass(newClass);
	});
}
function sprDropdown(){
	//$(".dropdown li:last-child").addClass("last");
	$(".album-list li:first-child").addClass("first");
	$(".small-frame li:last-child").addClass("last");
	$(".index-ads3 li:last-child").addClass("last");
	$(".info-nav li:first-child").addClass("first");
	//$(".cmt-nav li:first-child").addClass("first");
	$(".gophotos li:last-child").addClass("last");
	$(".gophotos li:first-child").addClass("first");
	$(".option li:last-child").addClass("last");
	$(".bli:last-child .bli-wrap").addClass("last");
	$(".e-block li:last-child").addClass("last");
	$(".photo-nav li:first-child").addClass("first");
	$(".default-albums li:first-child").addClass("first");
}
function albumTitle(){
	$(".topranking-album a").hover(function(){
		$(this).find(".overlay").animate({bottom: '0'},200);
	},function(){
		$(this).find(".overlay").animate({bottom: '-40'},200);
	});
	$(".big-frame a").hover(function(){
		$(this).find(".overlay").animate({bottom: '0'},200);
	},function(){
		$(this).find(".overlay").animate({bottom: '-30'},200);
	});
	$(".small-frame a").hover(function(){
		$(this).find(".overlay").animate({bottom: '0'},200);
	},function(){
		$(this).find(".overlay").animate({bottom: '-30'},200);
	});
	$(".studio-album a").hover(function(){
		$(this).find(".album-info").animate({bottom: '0'},200);
	},function(){
		$(this).find(".album-info").animate({bottom: '-20'},200);
	});
	$(".sitem a").hover(function(){
		$(this).find(".overlay").animate({bottom: '0'},200);
	},function(){
		$(this).find(".overlay").animate({bottom: '-44'},200);
	});
	$(".palbum-list a").hover(function(){
		$(this).find(".overlay").animate({bottom: '0'},200);
		},function(){
		$(this).find(".overlay").animate({bottom: '-22'},200);
	});
	$(".small-face a.fitem").hover(function(){
		
		$(this).find(".overlay").animate({bottom: '0'},200);
		},function(){
		if ( !$(this).closest(".favitem").hasClass("topfav"))
		{
		$(this).find(".overlay").animate({bottom: '-22'},200);
		} else {
		$(this).find(".overlay").animate({bottom: '-40'},200);
		}
	});
}
function toTop(){
// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a.default').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		$('#back-top a.totop').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
}
function sprScroll(){
	$(".active .poplist").mCustomScrollbar({
        autoDraggerLength:true
	});
	$(".mCSB_scrollTools").css("display","none");
	$(".active .poplist").hover(
		function(){$(this).find(".mCSB_scrollTools").fadeIn('fast');},
		function(){$(this).find(".mCSB_scrollTools").fadeOut('fast');}
		);
}
function albumScroll(){	
		$(window).load(function(){
			if ($(".nalbums li").length > 8 ) {
				$(".nalbums-wrap").mCustomScrollbar({autoDraggerLength:true});
			}
			if ($(".nphotos li").length > 8) {
				$(".nphotos-wrap").mCustomScrollbar({autoDraggerLength:true});
			}
		});
}
function indexFaces(){
	$("li.class5").nextAll().wrapAll('<ul class="small-frame">');
	$("li.class1").nextUntil("ul.small-frame").wrapAll('<ul class="big-frame">');
}
function tipSmall(){
		$(".gotips").hover(
		function(){
				$(this).find(".tips-small").fadeIn('fast');
		},
		function(){
			$(this).find(".tips-small").fadeOut('fast');}	
		);
}
function moreSlide(){
	$(".info-slide a").click(function(){
		if ($(this).hasClass("clicked"))
		{
			$(this).removeClass("clicked");
		}
		else {
			$(this).addClass("clicked");
		}
		$(".more-info").slideToggle('fast');
	});
}
function actThumb(){
	$(".gophotos").each(function(index){
		if ($(this).find("ul li").length < 6)
		{
			var x = "count" + $(this).find("ul li").length;
			$(this).find("ul").addClass(x);
		} else {
			$(this).hide();
		}
	});
}
function sprLike(){
	$(".btn-like").click(function(){
		$(this).closest(".info").find(".like span").animate({
			opacity: 0.2
		}, 300, function(){
			$(this).closest(".info").find(".like span").animate({
			opacity: 1
			},300);
		});
	});
	$(".btn-like-small").click(function(){
		$(this).closest(".cmt").find(".count").animate({
			opacity: 0.2
		}, 300, function(){
			$(this).closest(".cmt").find(".count").animate({
			opacity: 1
			},300);
		});
	});
}
function cmtTabs(){
	$(".albumcmt-nav a").click(function(){
		$(".albumcmt-nav li").removeClass("current");
		if ($(this).closest("li").hasClass("pop"))
		{
			$(this).closest("li").removeClass("pop");
		}
		$(this).closest("li").addClass("current");
		var x = $(this).attr("href");
		$(".comment-col").removeClass("display");
		$(x).addClass("display");
		return false;
	});
}
function relatedAlbum(){
	if ($(".album-related .sitem").length > 4)
	{
		$(".album-slide").bxSlider({
			displaySlideQty: 4,
			moveSlideQty: 4,
			infiniteLoop: false,
			hideControlOnEnd: false
		  });
	}

}
function sprPager(){
	$(".pager .previous a").html('');
	$(".pager .next a").html('');
}
function sprShare(){
	var aUrl = window.location;
	var aTitle = $(".album-details h2 a").html();
	var parse_url = /^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/;
	var parts = parse_url.exec( aUrl );
	var e = parts[1]+':'+parts[2]+parts[3]+'/' ;
	
	$(".share-url input").attr("value",aUrl);
	$(".embed-share input").click(function(){
		this.select();
	});
	$(".embed-share textarea").click(function(){
		this.select();
		$(this).mouseup(function() {
        // Prevent further mouseup intervention
        $(this).unbind("mouseup");
        return false;
		});
	});
	$('.photo-nav').each(function(index) {
	//	$(this).find("input").remove();
		var a = '#'+(index+1);
		var b = $(this).find("img").attr("src");
		var c = a+'\n<br>'+'<a href="'+aUrl+'" target="_blank">'+'<img src="'+b+'" alt="'+aTitle+' '+a+'" border=""/></a><br><br>\n\n';
		var d = aTitle+' '+a+'\n[url="'+aUrl+'"][img]'+b+'[/img][/url]\n\n';
		$(".share-html").append(c);
		$(".share-bbcode").append(d);
	});
	$(".HTML textarea").text($(".share-html").html());
	$(".BBCODE textarea").text($(".share-bbcode").html());
	$(".share-btn a").click(function(){
		var shareClass = "." + $(this).html();
		$(".share-post").removeClass("share-active");
		$(shareClass).addClass("share-active");
	});
	$(".sprShare").click(function(){
		$(".embed-share").fadeToggle('fast').addClass("active");
	});
}
function sprAlert(){
	setTimeout(function(){
		$(".spralert").slideDown('fast');
	},1800)
}
function sprTopFav(){
	if ($(".favitem").length >= 5)
	{
	$(".favitem").each(function(index){
		if (index < 5)
		{
			$(this).addClass("topfav");
		}
		if (index == 0)
		{
			$(this).addClass("first");
		}
		if (index == 4)
		{
			$(this).addClass("fiveth");
		}
	});
	}
}