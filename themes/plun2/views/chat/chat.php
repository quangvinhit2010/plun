<?php 
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/resources/html/css/chat.css');
?>
<div id="chat-wrap">
	<div id="messageNotification" style="display: none;"></div>
	<div id="online-list-box" class="box" data-time="<?php echo time(); ?>" data-img="//<?php echo CParams::load ()->params->img_webroot_url; ?>">
		<div class="title-wrap">
			<span class="title" href="javascrupt:;"><span id="online-icon"></span>Online user<a id="online-list-toggle" href="javascript:;"></a></span>
		</div>
		<div class="box-content-wrap">
			<div id="online-list" class="box-content">
				<div class="box-content-inside">
				<?php
					foreach(Yii::app()->user->listOnline() as $online):
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
				?>
					<span id="cli-<?php echo $online['username'] ?>" class="online-user">
						<span class="avatar-wrap">
							<img src="<?php echo $avatar ?>" />
						</span>
						<span class="name"><?php echo $online['username'] ?></span>
					</span>
				<?php endforeach; ?>
				</div>
			</div>
			<div class="input-wrap">
				<span id="search-online-user"></span>
				<div id="search-online-field-wrap">
					<input class="chat-input" type="text" placeholder="<?php echo Lang::t('chat', 'Search') ?>" />
				</div>
			</div>
		</div>
	</div>
	<div id="chat-box-list"></div>
	<div id="chat-box-invisible">
		<div id="chat-box-invisible-list">
			<ul></ul>
			<span class="arrow"></span>
		</div>
		<div id="chat-box-invisible-holder"><span id="chat-box-invisible-icon"></span><span class="num">0</span></div>
	</div>
</div>
<script type="text/javascript">
	var XMPP_BIND = "<?php echo Yii::app()->params['XMPP']['http-bind'];?>";
	var XMPP_SERVER = "<?php echo Yii::app()->params['XMPP']['server'];?>";
	var XMPP_JID = "<?php echo Yii::app()->user->data()->username;?>";
	var XMPP_JKEY = "<?php echo Yii::app()->user->data()->chat_key;?>";
</script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/scripts/strophe.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/scripts/chat.js"></script>
