<div class="pop-activation">
    <?php if($opt == 1){?>
    <p>
        <label><?php echo Lang::t('register', 'You have to verify your email {email} to use this feature. Please check your mail box.', array('{email}'=>$user->profile->email));?></label>        
    </p>
    <?php }elseif($opt == 2){?>
    <p>
        <label><?php echo Lang::t('register', 'Please enter your new email below');?></label>
        <input type="text" id="emailReSend" name="" data-url="<?php echo $user->createUrl('/register/resendActivation');?>" value="<?php echo $user->profile->email;?>" size="33"/>
    </p>
    <?php }?>
</div>