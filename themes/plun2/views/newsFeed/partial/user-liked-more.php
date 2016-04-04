<?php if(!empty($objs)){?>
	<?php foreach ($objs as $obj){?>
		<li>
			<a class="left" href="<?php echo $obj->user->getUserUrl();?>"><img width="50" height="50" src="<?php echo $obj->user->getAvatar();?>"></a>
			<div class="clearfix list_r_u">
				<div class="right c_item_box">
					<div class="_tempH"></div>
					<div class="c_item">
						<?php if($obj->user->isFriendOf(Yii::app()->user->id)):?>
<!-- 							<a href="javascript:void(0);" class="btn_friend icon_common" title="Friend"></a> -->
						<?php else:?>
<!-- 							<a href="javascript:void(0);" class="btn_add_friend icon_common" title="Add Friend"></a> -->
						<?php endif;?>
						<a data-id="<?php echo $obj->user->username;?>" class="icon_common icon_msg_user quick-chat" href="javascript:void(0);"></a>
					</div>
				</div>
				<div class="n_u">
					<div class="c_item_box">
						<div class="_tempH"></div>
						<div class="c_item">
							<a href="<?php echo $obj->user->getUserUrl();?>"><?php echo $obj->user->getDisplayName();?></a>
						</div>
					</div>
				</div>
			</div>
		</li>
	<?php }?>
	<?php if($total > ($offset + CParams::load()->params->news_feed->limit_view_liked)){?>
		<li><a href="javascript:void(0);" data-url="<?php echo $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => $oid, 'type' => Like::LIKE_ACTIVITY)); ?>" data-offset="<?php echo $offset + CParams::load()->params->news_feed->limit_view_liked;?>" class="moreUserLike loadingItem">more</a></li>
	<?php }?>    
<?php }?>