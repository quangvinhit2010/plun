var Bookmark = {
	delete_bm: function(id){
		if(id){
			$( ".popup-alert.remove_bookmark .frame_content" ).html(tr('Are you sure to remove this bookmark?'));
			$( ".popup-alert.remove_bookmark" ).pdialog({
				title: tr('Bookmark'),
				buttons: [
							{
							  text: tr("Ok"),
							  click: function() {
								$( ".popup-alert.remove_bookmark" ).pdialog( "close" );
							  	$(this).loading();
							  	var url = "/bookmark/delete/id/" + id;
								$.getJSON( url, function( res ) {
									if(res.status  == 'true'){
										if($('.members').length > 0){
											$('.members .list ul #'+id).remove();
											$('.members h3 p b').html(res.bookmark_count);
										} else {
											$('#boookmark-icon').find('a').remove();
											$('#boookmark-icon').html(Bookmark.build_add_button(id));
										}
										$( ".popup-alert.remove_bookmark" ).dialog( "destroy" );
										
										Util.popAlertSuccess(tr('You removed one bookmarked profile'), 400);
										setTimeout(function () {
											$( ".pop-mess-succ" ).pdialog('close');
										}, 3000);
										
										
									}
									$(this).unloading();
								});
							  }
							},
							{
							  text: tr("Cancel"),
							  click: function() {
								  $( this ).pdialog( "close" );
							  }
							},
						  ],
			});
			
		}
	}, 
	add_bm: function(id){
		//var rand = Math.floor((Math.random()*100)+1);
		if(id){
			$(this).loading();
			var url = "/bookmark/add/id/" + id;
			$.getJSON( url, function( res ) {
				if(res.status == 'true'){
					$('#boookmark-icon').find('a').remove();
					$('#boookmark-icon').html(Bookmark.build_remove_button(id));
					
					Util.popAlertSuccess(tr('You have one more bookmarked profile'), 400);
					setTimeout(function () {
						$( ".pop-mess-succ" ).pdialog('close');
					}, 3000);
					
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
		return '<a title="" href="javascript:void(0);" onclick="Bookmark.delete_bm('+ id +');" class="bookmark_tru btnOkPopup">bookmark</a>';
	},
	show_more: function(){
		$(this).loading();
		var page = $('#bookmarks').attr('page');
		if(page != 'end'){
			var url = window.location.href + '&page=' + page;
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					if(data == 'end'){
						$('.list .more-wrap').hide();
						//@todo: show popup error
					} else {
						//get next_page
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							$('.list .more-wrap').hide();
						} else {
							$('#bookmarks').attr('page', next_page);
						}
						
						$('#bookmarks').append(data);
						Bookmark.scroll();
						
					}
					$(this).unloading();
				},
				dataType: 'html'
			});
		} else {
			$('.list .more-wrap').hide();
			$(this).unloading();
		}
	},
	scroll: function () {
		$(".main .col-right .members .list").mCustomScrollbar("destroy");
		sprScroll('.main .col-right .members .list');
		$('.main .col-right .members .list').mCustomScrollbar('scrollTo', 'bottom');
	},
};






		
