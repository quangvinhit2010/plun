var Bookmark = {
	delete_bm: function(id){
		if(id){
			$( ".popup-alert.remove_bookmark .frame_content" ).html(tr('Are you sure to remove this bookmark?'));
			$( ".popup-alert.remove_bookmark" ).pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
				title: tr('Bookmark'),
				buttons: [
							{
							  text: tr("Ok"),
							  class: 'active',
							  click: function() {
								$( ".popup-alert.remove_bookmark" ).pdialog( "close" );
								objCommon.loading();
							  	var url = "/bookmark/delete/id/" + id;
								$.getJSON( url, function( res ) {
									if(res.status  == 'true'){
										$('#boookmark-icon').parent().html(Bookmark.build_add_button(id));
										$(".popup-alert.remove_bookmark" ).dialog( "destroy" );	
										$('#boookmark-icon').parent().addClass('bookmark');
										$('#boookmark-icon').parent().removeClass('unbookmark');
										
										Util.popAlertSuccess(tr('You removed one bookmarked profile'), 400);
										setTimeout(function () {
											$( ".pop-mess-succ" ).pdialog('close');
										}, 3000);
										
										
									}
									objCommon.unloading();
								});
							  }
							},
							{
							  text: tr("Cancel"),
							  click: function() {
								  $( this ).pdialog( "close" );
							  }
							}
						  ]
			});
			
		}
	}, 
	add_bm: function(id){
		//var rand = Math.floor((Math.random()*100)+1);
		if(id){
			objCommon.loading();
			var url = "/bookmark/add/id/" + id;
			$.getJSON( url, function( res ) {
				if(res.status == 'true'){
					$('#boookmark-icon').parent().html(Bookmark.build_remove_button(id));
					$('#boookmark-icon').parent().addClass('unbookmark');
					$('#boookmark-icon').parent().removeClass('bookmark');
					Util.popAlertSuccess(tr('You have one more bookmarked profile'), 400);
					setTimeout(function () {
						$( ".pop-mess-succ" ).pdialog('close');
					}, 3000);
					
				} else {
					//add error
				}
				objCommon.unloading();
			});
		}
	}, 
	build_add_button: function(id){
		return '<a class="icon_common" id="boookmark-icon" title="" href="javascript:void(0);" onclick="Bookmark.add_bm('+ id +');"></a>';
	},
	build_remove_button: function(id){
		return '<a class="icon_common" id="boookmark-icon" title="" href="javascript:void(0);" onclick="Bookmark.delete_bm('+ id +');"></a>';
	},
	show_more: function(){
		objCommon.loading();
		var page = $('#bookmarks').attr('page');
		if(page != 'end'){
			var url = window.location.href.split('#')[0] + '?page=' + page;
			console.log(url);
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					if(data == 'end'){
						$('.bookmark_list .more-wrap').hide();
						//@todo: show popup error
					} else {
						//get next_page
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							$('.bookmark_list .more-wrap').hide();
						} else {
							$('#bookmarks').attr('page', next_page);
						}
						$('#bookmarks').append(data);
					}
					$('.list_explore .list_user ul li').boxResizeImg({
			            wrapContent: true
			        });
					objCommon.unloading();
				},
				dataType: 'html'
			});
		} else {
			$('.bookmark_list .more-wrap').hide();
			objCommon.unloading();
		}
	}
};






		
