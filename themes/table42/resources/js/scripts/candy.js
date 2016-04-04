$(function(){
	Candy.init();
	Candy.upgradeVip();
	Candy.transferToUser();
});

var Candy = {
	init: function () {
		$('.btn_convert_money').tooltip({
			title: '<div class="tip_exchange"><label></label><p><b>Chuyển thành tiền mặt:</b> Bạn cần tối thiểu 1000 candy và chịu chiết khấu 10% để thực hiện chuyển khoản.</p></div>', 
			html: true
		});
	},
	setVipPlan: function(package_id){
		objCommon.loading();
		var data	=	{
			package_id: package_id
		};
		$.ajax({
			type: "POST",
			url: '/vip/upgradevip',
			data: data,
		    success: function(data){
				if(data == '1'){
			    	Util.popAlertSuccess(tr('Bạn Đã Thành VIP'), 300);
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000);  	
			        
					var el = new objCommon.showEffectPopup('.vip_candy a');
					el.closeEffect();
					
					window.location = "/";
					
				}
				
				if(data == '2'){
			    	Util.popAlertSuccess(tr('Vui Lòng Nạp Thêm Candy'), 300);
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000);  					
				}
				
				if(data == '0'){
			    	Util.popAlertSuccess(tr('Dữ Liệu không Hợp Lệ'), 300);
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000); 					
				}				
				
				objCommon.unloading();
		    },
		    dataType: 'html'
		});
		

		
	},
	chooseVipPlan: function(package_id){
		$( ".popup-alert.confirmTransfer .frame_content" ).html(tr('Do you want to buy this VIP package?'));
		$( ".popup-alert.confirmTransfer" ).pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            buttons: {
                "Submit": {
                    text: tr("Yes"),
                    class: 'active',
                    click: function(){
                        $( this ).dialog( "close" );
                        Candy.setVipPlan(package_id);
                    }
                },
                "Cancel": {
                    text: tr("No"),
                   
                    click: function(){
                        $( this ).dialog( "close" );
                    }
                }
            }
		});			
	},
	upgradeVip: function () {
		$(document).on('click','.vip_candy a', function(e){
			e.preventDefault();
			var el = new objCommon.showEffectPopup(this);
			var _url = $(this).attr('data-url');
			el.openEffect();
			$.ajax({
				type: "GET",
				url: _url,
			        success: function(data){
					el.showAjaxLoaded(data);
			        },
			        dataType: 'html'
			});
		});
	},
	transferToUser: function () {
		$(document.body).on('click', '.tang_candy .right .but', function() {
			$( ".popup-alert.confirmTransfer .frame_content" ).html(tr('Are you sure you want to transfer xxx Candy to xxxx?'));
			$( ".popup-alert.confirmTransfer" ).pdialog({
				title: tr('Tặng Candy'),
				buttons: [
					{
					  text: tr("OK"),
					  click: function() {
						  
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
			return false;
		});
		
	},
	history: function () {
		$(document.body).on('click', '.candy_info .history', function() {
			objCommon.loading();	
			var _url = $(this).attr('data-url');
			$.ajax({
				type: "POST",
				url: _url,
				dataType: 'html',
				success: function( response ) {					
					$('.content_candy').html(response);
					objCommon.unloading();
				}
			});
			return false;
		});
		
	},
}
