$(document).ready(function(){
	$('li.bookmark a').click(function(){
		var class_name	=	$(this).attr('class');
		var user_id		=	$(this).attr('user_id');
		
		switch(class_name){
			case 'unbookmark': 
				Bookmark.delete_bm(user_id);
			break;
			case 'bookmark': 
				Bookmark.add_bm(user_id);
			break;			
		}
	});
});
var Bookmark = {
	delete_bm: function(id){
	  	$(this).loading();
	  	var url = "/bookmark/delete/id/" + id;
		$.getJSON( url, function( res ) {
			if(res.status  == 'true'){				
				Util.popAlertSuccess(tr('You removed one bookmarked profile'), 300);
				setTimeout(function () {
					$( ".pop-mess-succ" ).pdialog('close');
				}, 3000);
				$('a.unbookmark').addClass('bookmark');
				$('a.bookmark').removeClass('unbookmark');
			}
			$(this).unloading();
		});
	}, 
	add_bm: function(id){
		//var rand = Math.floor((Math.random()*100)+1);
		if(id){
			$(this).loading();
			var url = "/bookmark/add/id/" + id;
			$.getJSON( url, function( res ) {
				if(res.status == 'true'){
					Util.popAlertSuccess(tr('You have one more bookmarked profile'), 300);
					setTimeout(function () {
						$( ".pop-mess-succ" ).pdialog('close');
					}, 3000);
					$('a.bookmark').addClass('unbookmark');
					$('a.unbookmark').removeClass('bookmark');
				} else {
					//add error
				}
				$(this).unloading();
			});
		}
	}, 
	build_add_button: function(id){
		return '<a title="" href="javascript:void(0);" onclick="Bookmark.add_bm('+ id +');" class="bookmark_cong">bookmark</a>';
	},
	build_remove_button: function(id){
		return '<a title="" href="javascript:void(0);" onclick="Bookmark.delete_bm('+ id +');" class="bookmark_tru">bookmark</a>';
	},
	show_more: function(){
		$(this).loading();
		var page = $('#bookmarks').attr('page');
		if(page != 'end'){
			var url = window.location.href + '?page=' + page;
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					if(data == 'end'){
						$('.block_loading').hide();
						//@todo: show popup error
					} else {
						//get next_page
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							$('.block_loading').hide();
						} else {
							$('#bookmarks').attr('page', next_page);
						}
						
						$('#bookmarks').append(data);
						//Bookmark.scroll();
						
					}
					$(this).unloading();
				},
				dataType: 'html'
			});
		} else {
			$('.block_loading').hide();
			$(this).unloading();
		}
	},
	scroll: function () {
		$(".main .col-right .members .list").mCustomScrollbar("destroy");
		sprScroll('.main .col-right .members .list');
		$('.main .col-right .members .list').mCustomScrollbar('scrollTo', 'bottom');
	},
};






		
