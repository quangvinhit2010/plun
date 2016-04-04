<?php if(!empty($objs)){?>
<div class="list_user_like">
    <ul class="userlike">
        <?php foreach ($objs as $obj){?>
            <li>
                <a href="<?php echo $obj->user->getUserUrl();?>"><img width="32" src="<?php echo $obj->user->getAvatar();?>"></a>
                <p><a href="<?php echo $obj->user->getUserUrl();?>"><?php echo $obj->user->getDisplayName();?></a></p>            
            </li>
        <?php }?>
    </ul>
</div>
<?php }?>