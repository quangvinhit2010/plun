<div class="col-feed col-left">
	<?php CController::forward('/photo/myrequest', false); ?>
</div>
<!-- left column -->
<div class="col-right">
	<div class="photos-setting">
		<?php $this->renderPartial('partial/my_photo/_public', array('public_photos' => $public_photos));?>
		<?php //$this->renderPartial('partial/my_photo/_private', array('private_photos' => $private_photos));?>
		<?php $this->renderPartial('partial/my_photo/_vault', array('vault_photos' => $vault_photos));?>
	</div>
	<!-- Photos Upload area -->
</div>
<!-- right column -->
