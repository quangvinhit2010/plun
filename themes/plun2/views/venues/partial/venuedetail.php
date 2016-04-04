
<div class="title">
    	<h4><?php echo $venue_detail->title; ?></h4>
        <p><?php echo $venue_detail->address; ?></p>
        <p>
        	<?php echo Lang::t('venue', '{totalvisitors} users were here', array('{totalvisitors}' => $venue_detail->total_visit)); ?>
        </p>
        <?php if($my_venue != $venue_id): ?>
        	<a href="javascript:void(0);" onclick="Location.CheckinFromDetail(<?php echo $venue_id; ?>);" class="checkinfromdetail"><i class="icon_common"></i><?php echo Lang::t('venue', 'I am here now'); ?></a>
        <?php endif; ?>
    </div>
    <div class="content scrollPopup">
    	<ul class="clearfix listwhocheckin">
    		<?php 
				foreach ($venue_data['data'] AS $row):
			?>
            <li>
                <a class="left" href="<?php echo $row->member->getUserUrl();?>"><img width="50" height="50" src="<?php echo $row->member->getAvatar() ?>"></a>
                <div class="clearfix list_r_u">
                    <div class="right c_item_box">
                        <div class="_tempH"></div>
                        <div class="c_item">
                        	<a class="icon_common icon_msg_user quick-chat" href="javascript:void(0);" data-id="<?php echo $row->member->username; ?>"></a>
                        </div>
                    </div>
                    <div class="n_u">
                        <div class="c_item_box">
                            <div class="_tempH"></div>
                            <div class="c_item">
                                <a href="<?php echo $row->member->getUserUrl();?>">
       						    <?php if(isset($online_data['online'][$row->member->id])){ ?>
                                	<label class="online"></label>
                                <?php }else{ ?>
                                    <label class="offline"></label>
                                <?php } ?>
                                <?php echo $row->member->username; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php 
				endforeach;
			?>
        </ul>
        <?php if($venue_data['total'] > $limit): ?>
        <div class="footer_loadmore">
        	<a href="javascript:void(0);" class="moreUserLike loadingItem whocheckinshowmore" onclick="Location.showMoreDetailCheckin();"><?php echo Lang::t('venue', 'More'); ?></a>
        </div>
        <?php endif; ?>
        <input type="hidden" name="whocheckin_offset" id="whocheckin_offset" value="<?php echo sizeof($venue_data['data']); ?>">
		<input type="hidden" name="whocheckin_limit" id="whocheckin_limit" value="<?php echo $limit; ?>">
		<input type="hidden" name="whocheckin_venue_id" id="whocheckin_venue_id" value="<?php echo $venue_id; ?>">
		<input type="hidden" name="whocheckin_total" id="whocheckin_total" value="<?php echo $total; ?>">
    </div>