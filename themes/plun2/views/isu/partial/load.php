<?php CController::forward('/isu/index/page/1/type/all/render/renderPartial', false); ?>
<script type="text/javascript">
$(document).ready(function(){
	if(true) {
		objCommon.loading();
		$.get('/isu/load', {id: <?php echo $isu->id;?>}, function(response){
			$( ".popup_isu_detail" ).html(response);
			
			if($('.pics_isu img').length > 0){
				$('.pics_isu img').load(function(){
					$( ".popup_isu_detail" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
							objCommon.outSiteDialogCommon(this);
                            objCommon.no_title(this);
						},
						pclose: function() {
							$( ".popup_isu_forward" ).remove();
						},
						width: 700
					});

				}).error(function(){
					$( ".popup_isu_detail" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
							objCommon.outSiteDialogCommon(this);
                            objCommon.no_title(this);
						},
						pclose: function() {
							$( ".popup_isu_forward" ).remove();
						},
						width: 700
					});

				});
			}else{
				$( ".popup_isu_detail" ).pdialog({
					open: function(){
						objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
						objCommon.outSiteDialogCommon(this);
                        objCommon.no_title(this);
					},
					pclose: function() {
						$( ".popup_isu_forward" ).remove();
					},
					width: 700
				});

			}
			objCommon.unloading();
		});
		
		
	}
});

</script>