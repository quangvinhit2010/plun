$(function(){
	Visitor.loadVisitor();
	Visitor.showMore();
	Visitor.candyProcess();
});

var Visitor = {
	loadVisitor: function () {		
		$( ".list_visitor" ).exists(function(){
			objCommon.loadingInside($(this));
			var url = $(this).attr('data-url');
			$.ajax({
			      type: "POST",
			      url: url,
			      data: {'type': 1},
			      success: function( response ) {
			    	  $( ".list_visitor" ).html(response);
			    	  objCommon.unloadingInside();
			      }
			});
		});	
	},
	showMore: function () {
		$( ".list_visitor" ).exists(function(){
			$(document.body).on('click',".visitor_page .pagging a", function() {
				var _this = $(this);				
				var url = _this.attr('data-url');				
				var total = _this.attr('data-total');				
				$.ajax({
					type: "POST",
					url: url,
					data: {'type': 1},
					dataType: 'json',
					success: function( response ) {
						console.log(response.status);
						if(response.status == true){
							console.log(response.html);
							$( ".list_visitor ul" ).append(response.html);		
							_this.attr('data-url', response.url);
							if(total <= response.offset){
								_this.remove();
							}
						}else{
							_this.remove();
						}
					}
				});
			});
		});
	},
	candyProcess: function () {
		$(document.body).on('click',".payLimitRightToViewVisitor", function() {	
			var _this = $(this);				
			objCommon.loadingInside(_this);
			var url = _this.attr('data-url');
			$.ajax({
				type: "POST",
				url: url,
				data: {'type': 1},
				dataType: 'json',
				success: function( response ) {
					if(response.status == true){
						$( response.html).exists(function(){							
							$(response.html).pdialog({
								open: function(){
									objCommon.outSiteDialogCommon(this);									
								},
								title: tr('Create'),                    
								open: function(){},
								width: 540
							});						
							objCommon.unloadingInside();
						});
					}
				}
			});
		});
	},
	
}
