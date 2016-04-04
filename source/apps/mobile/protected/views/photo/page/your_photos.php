				<div class="profile-photo">
                    	<div class="list-block-photo">
                    		<?php $this->renderPartial('partial/your_photo_public', array('public_photos' => $public_photos, 'user' => $user));?>
                    		<?php $this->renderPartial('partial/your_photo_vault', array('vault_photos' => $vault_photos, 'user' => $user));?>
                    		<?php //$this->renderPartial('partial/your_photo_private', array('private_photos' => $private_photos, 'user' => $user));?>
				</div>
