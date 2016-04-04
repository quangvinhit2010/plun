<div id="request_popup" style="display: none">
	<div class="all_photo_accept">
		<ul class="list_photo_setting">
		</ul>                        
	</div>
	<div class="all_accept_decline left">
		<div class="left">
			<span></span> <a href="javascript:void(0);" onclick="Photo.close_popup_request();" class="all_request"><?php echo Lang::t('photo', 'Back');?></a>
		</div>
		<div class="right">
			<a onclick="Photo.accept_all_photo_request();" class="accept_all active" href="javascript:void(0);"><?php echo Lang::t('photo', 'Accept All');?></a>
			<a onclick="Photo.decline_all_photo_request();" class="decline_all" href="javascript:void(0);"><?php echo Lang::t('photo', 'Decline All');?></a>
		</div>
	</div>
</div>
