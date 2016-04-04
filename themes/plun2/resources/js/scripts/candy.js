$(function(){
	Candy.init();
	Candy.upgradeVip();
	Candy.transferToUser();
});

var Candy = {
	init: function () {
		Candy.colorBox();
		$('.btn_convert_money').tooltip({
			title: '<div class="tip_exchange"><label></label><p><b>Chuyển thành tiền mặt:</b> Bạn cần tối thiểu 1000 candy và chịu chiết khấu 10% để thực hiện chuyển khoản.</p></div>', 
			html: true
		});
		$(document).on('click', '.pagging a', function(e){
			var self = $(this);
			self.css('visibility', 'hidden');
			$('#paging-loading').css('visibility', 'visible');
			
			var url = $(this).attr('href');
			$.get(url, function(response){
				response = $(response);
				$('.list_giaodich ul').append(response.find('.list_giaodich ul').html());
				Candy.colorBox();
				if(response.find('.pagging').length > 0) {
					$('.pagging a').attr('href', response.find('.pagging a').attr('href'));
					self.css('visibility', 'visible');
					$('#paging-loading').css('visibility', 'hidden');
				} else
					$('.pagging').remove();
			});
			e.preventDefault();
		});
		$(document).on('click', '#search-button', function(){
			$('#search-loading').css('visibility', 'visible');
			$('#search-button').css('visibility', 'hidden');
			var data = $('#search-form').serialize();
			var url = $('#search-form').attr('action');
			$.get(url, data, function(response){
				response = $(response);
				$('.list_giaodich').html(response.find('.list_giaodich').html());
				Candy.colorBox();
				$('#search-loading').css('visibility', 'hidden');
				$('#search-button').css('visibility', 'visible');
			});
		});
		$('.btnGetCandyFree').click(function(e){
			objCommon.loading();
			var url = $(this).attr('href');
			$.get(url, function(response) {
				$('.btnGetCandyFree').hide();
				Util.popAlertFail(response.message, 360);
				setTimeout(function () {
					$( ".pop-mess-fail" ).pdialog('close');
				}, 2000);
				objCommon.unloading();
				if(response.error == '0') {
					var candy = Number($('.info .num').text().replace(/\,/g,'')) + response.candy;
					$('.info .num').text(candy.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				}
			}, 'json');
			e.preventDefault();
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

			var userCandy = Number($('.info .num').text().replace(/\,/g,''));
			var totalReceiver = $('#s2id_lstUser').find('.select2-search-choice').length;
			
			if(totalReceiver == 0) {
				Util.popAlertFail(tr('Please enter username of receiver'), 280);
				setTimeout(function () {
					$( ".pop-mess-fail" ).pdialog('close');
				}, 2000);
				return false;
			} else if ($('#candy').val() == '') {
				Util.popAlertFail(tr('Please choose candy amount to transfer'), 360);
				setTimeout(function () {
					$( ".pop-mess-fail" ).pdialog('close');
				}, 2000);
				return false;
			}
			
			var value = $('#captcha').val();
			var hash = $('body').data('captcha.hash');
			if (hash == null)
				hash = $('#hash').val();
			else
				hash = hash[1];
			for(var i=value.length-1, h=0; i >= 0; --i) h+=value.toLowerCase().charCodeAt(i);
			if(h != hash) {
				Util.popAlertFail(tr('The verification code is incorrect.'), 280);
				setTimeout(function () {
					$( ".pop-mess-fail" ).pdialog('close');
				}, 2000);
				return false;
			}

			var closeMessageTimeout;
			var form = $('#transfer-form');
			var totalCandy = Number($('#candy').val())*totalReceiver;
			
			if(form.data('charge-type') == CHARGE_TYPE_PERCENT) {
				totalCandy = totalCandy + (totalCandy*Number(form.data('charge'))/100);
			} else {
				totalCandy = totalCandy + (Number(form.data('charge'))*totalReceiver);
			}
			
			var message = tr('Do you want to transfer &1 Candy to &2?&3Total Candy: &4');
			message = message.replace('&1', $('#candy').val());
			message = message.replace('&2', $('#s2id_lstUser').find('.select2-search-choice > div').clone().append(', ').text().slice(0,-2));
			message = message.replace('&3', '<br/><br/>');
			message = message.replace('&4', '<span style="font-weight: bolder;">'+totalCandy+'</span>');
			$( ".popup-alert.confirmTransfer .frame_content" ).html(message);
			$( ".popup-alert.confirmTransfer" ).pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
				title: tr('Tặng Candy'),
				buttons: [
					{
					  text: tr("OK"),
					  click: function() {
						  $( this ).pdialog( "close" );
						  var data = form.serialize();
						  var url = form.attr('action');
						  $.post(url, data, function(response) {
							  if(response.error == '0') {
								  $('#lstUser').select2('data', null);
								  $('#candy').val('');
								  $('#captcha').val('');
								  $('#yw1').trigger('click');
								  
								  $('.info .num').text(response.restCandy.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								  
								  var candyUsed = Number($('#candy-used').text().replace(/\,/g,''));
								  candyUsed = candyUsed + totalCandy;
								  $('#candy-used').text(candyUsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
							  }
							  
							  Util.popAlertFail(response.message, 360);
							  setTimeout(function () {
								  $( ".pop-mess-fail" ).pdialog('close');
							  }, 2000);
						  }, 'json');
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
		$(document.body).on('click', '.list_menu_candy a.ajax-load', function() {
			$('.content.left').html('<div style=" text-align: center; padding-top: 200px; "><div class="loadingInside" style=" width: 40px; height: 40px; border-radius: 50%; border-width: 4px; "></div></div>');
			var _url = $(this).attr('data-url');
			$.ajax({
				type: "POST",
				url: _url,
				dataType: 'html',
				success: function( response ) {					
					$('.content_candy').html(response);
					Candy.colorBox();
					if($('#search-form').length > 0)
						$( ".date" ).datepicker({dateFormat: 'dd-mm-yy'});
				}
			});
			return false;
		});
		
	},
	colorBox: function() {
		$('.color-box').colorbox({
			photo:true,
			slideshowAuto: false,
			rel:'color-box',
			fixed: true,
			scrolling: false,
			innerHeight: true,
			scalePhotos: true,
		    maxWidth: '100%',
			maxHeight: '95%'
		});
	},
	purchaseProcess: function () {
		$(document.body).on('click',".payLimitRightToViewVisitor", function() {
			
		});
	},
}
