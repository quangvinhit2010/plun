<?php if (!empty($this->user)) { ?>
    <div class="col-feed col-left">
        <?php CController::forward('/NewsFeed/index', false); ?>
        <!-- news feed -->
    </div>
    <div class="col-right">
        <?php 
        if($this->user->isMe()){
//             CController::forward('/friend/find', false);
            CController::forward('/photo/ListPhoto', false);
        }else{
            CController::forward('/photo/ListPhoto', false);
        }
        ?>
        <!-- members area -->
    </div>
<?php } ?>
