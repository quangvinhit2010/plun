<?php if (!empty($this->user)) { ?>
    <div class="col-feed col-left">
        <?php 
        if($this->user->isMe()){
            CController::forward('/photo/myrequest', false);
        }else{
            CController::forward('/NewsFeed/index', false); 
        }
        
        ?>
        <!-- news feed -->
    </div>
    <div class="col-right">
        <?php 
        CController::forward('/photo/PhotoMoreList', false);
        ?>
        <!-- members area -->
    </div>
<?php } ?>