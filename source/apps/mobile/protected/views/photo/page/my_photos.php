<?php $this->renderPartial('partial/photo_top_menu', array('type' => $type));?>

<div class="pad_left_10 pad_top_10">                  
	<div class="left list_photo">
		<?php 
			switch ($type) {
			    case Photo::PUBLIC_PHOTO:
			        $this->renderPartial('partial/my_photo/_public', array('public_photos' => $public_photos));
			        break;
			    case Photo::VAULT_PHOTO:
			        $this->renderPartial('partial/my_photo/_vault', array('vault_photos' => $vault_photos));
			        break;
			    case Photo::PRAVITE_PHOTO:
			        //$this->renderPartial('partial/my_photo/_private', array('private_photos' => $private_photos));
			        break;
			}
		
		?>
		<?php ?>
		<?php //$this->renderPartial('partial/my_photo/_vault', array('vault_photos' => $vault_photos));?>
		<?php //$this->renderPartial('partial/my_photo/_private', array('private_photos' => $private_photos));?>
		
	</div>
</div>