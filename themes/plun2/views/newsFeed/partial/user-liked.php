<?php if(!empty($objs)){?>
<div class="feedLikedUser u_list_add">
    <div class="scrollPopup">
        <ul class="clearfix">
            <?php $this->renderPartial('//newsFeed/partial/user-liked-more', array('objs'=>$objs,'oid'=>$oid, 'total'=>$total, 'offset'=>$offset,))?>
        </ul>
    </div>
</div>
<?php }?>