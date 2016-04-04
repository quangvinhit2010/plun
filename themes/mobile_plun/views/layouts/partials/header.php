<?php 
$user = Yii::app()->user->data();
if(!empty($user)){
?>
<div id="wrapper_header">
    <div class="width_common">
      <div class="logoplun">
        <div class="btn_control_col_left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/graphics/img_left_panel.png" width="51" height="35" alt=""></div>
        <div class="function">
        	<ul>
                <li><a title="Check in" class="icon_function iconmenu_checkin" href="<?php echo $user->createUrl('//location/checkin');?>"></a></li>
            	<li class="nav-msg">
            		<?php 
						$offlineMessages = OfflineMessages::model()->getOfflineMessages($this->usercurrent->id, $this->usercurrent->username);
						$totalOfflineMessage = count(array_filter(explode(',', $offlineMessages)));
						
						$offlineMapEncrypt = array();
						if($totalOfflineMessage) {
							$temp = explode(',', $offlineMessages);
							foreach($temp as $t) {
								$offlineMapEncrypt[$t] = Util::encrypt($t);
							}
						}
					?>
            		<a data-avatar-url="<?php echo Yii::app()->createUrl('/site/avatar') ?>"
            			data-chat-url="<?php echo $this->createUrl('messages/cw') ?>"
            			data-update-url="<?php echo $this->createUrl('messages/updateOffline') ?>"
            			data-map="<?php echo htmlentities(json_encode($offlineMapEncrypt)) ?>"
            			data-offline-message="<?php echo $offlineMessages ?>"
            			href="<?php echo $user->createUrl('//messages/index', array('t'=>time())) ?>" class="icon_function iconmenu_message" title="<?php echo Lang::t('general', 'Message');?>"></a>
            		<?php if($totalOfflineMessage): ?>
						<span style="display: inline;" class="count"><?php echo $totalOfflineMessage ?></span>
					<?php else:?>
	            		<span style="display: none;" class="count"></span>
					<?php endif;?>
					<script type="text/javascript">
						var XMPP_BIND = "<?php echo Yii::app()->params['XMPP']['http-bind'];?>";
						var XMPP_SERVER = "<?php echo Yii::app()->params['XMPP']['server'];?>";
						var XMPP_JID = "<?php echo Yii::app()->user->data()->username;?>";
						var XMPP_JKEY = "<?php echo Yii::app()->user->data()->chat_key;?>";
					</script>
					<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/strophe.js"></script>
					<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat.js?v=8"></script>
            	</li>
                <li class="nav-friend">
                	<a href="<?php echo $user->createUrl('//friend/request');?>" class="icon_function iconmenu_friend" title="<?php echo Lang::t('general', 'Friends');?>"></a>
                	<?php if(!empty($this->e_user['total_addfriend_request'])):?>
						<span style="display: inline;" class="count"><?php echo $this->e_user['total_addfriend_request']; ?></span>
					<?php else:?>
	            		<span style="display: none;" class="count"></span>
					<?php endif;?>
				</li>
                <li class="nav-photo">
                	<a href="<?php echo $user->createUrl('//photo/index');?>" class="icon_function iconmenu_photo" title="<?php echo Lang::t('general', 'Photo');?>"></a>
                	<?php if(!empty($this->e_user['total_photo_request'])):?>
						<span style="display: inline;" class="count"><?php echo $this->e_user['total_photo_request']; ?></span>
					<?php else:?>
	            		<span style="display: none;" class="count"></span>
					<?php endif;?>
                </li>
                <li class="nav-alert">
                	<a href="<?php echo $user->createUrl('//alerts/index');?>" class="icon_function iconmenu_alert" title="<?php echo Lang::t('general', 'Alerts');?>"></a>
                	<?php if(!empty($this->e_user['total_alert'])):?>
						<span style="display: inline;" class="count"><?php echo $this->e_user['total_alert']; ?></span>
					<?php else:?>
	            		<span style="display: none;" class="count"></span>
					<?php endif;?>
				</li>
            </ul>
        </div>
      </div>
      <div class="clear">&nbsp;</div>
    </div>
  </div>
<div id="new-messages-wrap">
	<ul id="new-messages" class="list-conversation">
	</ul>
	<a class="see-all" href="<?php echo $user->createUrl('//messages/index') ?>"><?php echo Lang::t('messages', 'See all message') ?></a>
</div>
<?php 
}
?>