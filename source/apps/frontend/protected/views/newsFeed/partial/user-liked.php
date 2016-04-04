<?php if(!empty($objs)){?>
<ol>
    <?php foreach ($objs as $obj){?>
        <li><a href="<?php echo $obj->user->getUserUrl();?>"><?php echo $obj->user->getDisplayName();?></a></li>
    <?php }?>
    <?php if($total > 5){?>
        <li><a href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/moreUserLiked", array('oid' => $oid, 'type' => Like::LIKE_ACTIVITY)); ?>" class="moreUserLike">and more...</a></li>
    <?php }?>
</ol>
<?php }?>