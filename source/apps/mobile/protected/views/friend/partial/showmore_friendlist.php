<?php
foreach ($myfriend_list['dbrow'] AS $item):
    if (Yii::app()->user->id == $item->invited->id) {
        $item->invited = $item->inviter;
    }
    if (is_object($item->invited->profile_settings)) {
        $show_more_info = true;
        $sex_role = isset($sex_roles_title[$item->invited->profile_settings->sex_role]) ? $sex_roles_title[$item->invited->profile_settings->sex_role] : '';
    } else {
        $show_more_info = false;
        $sex_role = '';
    }
    ?>
    <li class="item_friendlist_showmore" style="display: none;">
        <a href="<?php echo $item->invited->getUserUrl(); ?>" class="ava">
            <img src="<?php echo $item->invited->getAvatar(); ?>?t=<?php echo time(); ?>" alt="" border=""/>
            <span class="ava-bg"></span>
            <div class="name">
                <span class="fname"><?php echo $item->invited->getDisplayName(); ?></span>
                <?php
                if ($show_more_info):
                    $country_name   =   !empty($country_info[$item->invited->profile_settings->country_id]['name'])   ?   "{$country_info[$item->invited->profile_settings->country_id]['name']}"    :   '';
                    $state_name   =   !empty($state_info[$item->invited->profile_settings->state_id]['name'])  ?  "{$state_info[$item->invited->profile_settings->state_id]['name']}, "    :   '' ;                               
                    $birthday_year = isset($item->invited->profile_settings->birthday_year) ? $item->invited->profile_settings->birthday_year : false;
                    ?>
                    <div class="more">
                        <p class="location">
                            <i class="imed imed-loc"></i>
                            <span class="text">
                                <?php echo $state_name; ?><?php echo $country_name; ?>
                            </span>
                        </p>
                        <?php if ($birthday_year): ?>
                            <p class="contact"><?php echo Lang::t('search', 'Age'); ?>: <?php echo date('Y') - $birthday_year; ?></p>
                        <?php endif; ?>
                        <p class="intro"><?php echo $sex_role; ?></p>    
                    </div>
                <?php endif; ?>
            </div>
        </a>
         <div class="un_function">
         	<a href="javascript:void(0);" onclick="unfriend('<?php echo $item->invited->id; ?>', '<?php echo $item->invited->getAliasName(); ?>');" title="Unfriend" class="unfriend"></a>
        </div>
    </li>
<?php endforeach; ?>