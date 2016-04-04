<?php $user = Yii::app()->user->data() ?>
<div class="content-message-wrap">
	<ul id="m-tab">
		<li><a href="<?php echo $user->createUrl('//messages/index'); ?>"><?php echo Lang::t('messages', 'Messages') ?></a></li>
		<li class="active"><a href="<?php echo $user->createUrl('//messages/friends'); ?>"><?php echo Lang::t('messages', 'Friends list') ?></a></li>
	</ul>
	<div id="search-wrap">
		<input id="search-friend-field" type="text" />
		<input id="submit-search" type="image" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/images/graphics/search.png" alt="Submit">
	</div>
	<?php if($friends): ?>
	<ul id="listFriends">
		<?php foreach($friends as $k => $online):
				$avatar = Yii::app()->request->getHostInfo()."/public/images/no-user.jpg";
				if(is_numeric($online['avatar'])){
					$photo = Photo::model()->findByAttributes(array('id'=>$online['avatar'], 'status'=>1));
					if($photo){
						$avatar = $photo->getImageThumbnail160x160(true);
					}
				} else {
					if($online['avatar'] != null){
						$avatar = VAvatar::model()->urlAvatar($online['avatar']);
					}
				}
				$username = $online['username'];
		?>
		<li class="item">
			<a href="<?php echo $this->createUrl('messages/cw', array('u'=>Util::encrypt($username))) ?>">
				<span class="left avatar"><img width="30" src="<?php echo $avatar ?>" /></span>
				<span class="chat-nickname"><?php echo $username ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
<script>chat.initFriendsPage();</script>