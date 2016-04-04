$(function(){
});

var Communities = {
	comInit: function () {
		Communities.comCreate();
	},	
	comCreate: function () {
		$(document.body).on('click',".createCommunity", function() {			
			$('.communityCreatePop').pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
				title: tr('Create'),                    
                open: function(){},
                width: 445
			});
		});
		
		$(document.body).on('click',".btnCommunityCreate", function() {			
			objCommon.loadingInside($('.btnCom'));		
			$(this).attr('disabled', 'disabled');
			var form = $(this).closest("#community-form");
			$.ajax({
			      type: "POST",
			      url: form.attr( 'action' ),
			      data: form.serialize(),
			      success: function( response ) {
			    	  $('.btnCommunityCreate').removeAttr('disabled');
			    	  objCommon.unloadingInside();
			      }
			});
			return false;
		});
	},
	comListItem: function () {
		$( ".ListCommunities" ).exists(function(){
			var url = $(this).attr('data-url');
			var type = $(this).attr('data-type');
			$.ajax({
			      type: "POST",
			      url: url,
			      data: {'type': type},
			      success: function( response ) {
			    	  $( ".ListCommunities" ).html(response);
			      }
			});
		});
	},
}

