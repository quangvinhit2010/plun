<div id="page-login" class="page-form">
    <div class="spr-modal-wrap">
        <div class="form-contain form-login">
            <div class="form-contain-wrap">
                <!-- close button -->
                    <div class="title">
                        <p class="signin">Message</p>
                    </div>
                    <ul class="w247">
                        <li class="orange_color">
                            <?php if(Yii::app()->user->hasFlash('msgLogin')): ?>
                            	<?php echo Yii::app()->user->getFlash('msgLogin'); ?>
                            <?php endif; ?>
                        </li>          
                        </li>              
                    </ul>
            </div>
        </div>
        <!-- form container -->
        <div class="position"></div>
    </div>
    <!-- wrap -->
</div>