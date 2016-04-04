<?php if(is_array(Yii::app()->user->listOnline())) {?>
<div class="chat-box-area">
	<!-- chat boxed -->
	<div class="chat-content"></div>
	<div class="chat-list active">
		<div class="head">
			<div class="title"><i></i><?php echo Lang::t('chat', 'Online list');?></div>
			<a href="javascript:void(0);" class="btn-slide"><i class="arrow"></i></a>
		</div>
		<div class="list">
			<!-- online list, append from nodejs -->
			<div class="chat-online-list">
				<ul class="item-wrap">
					<?php foreach (Yii::app()->user->listOnline() as $online) {?>
					<?php 
						$avatar = null;
						if(is_numeric($online['avatar'])){
							$photo = Photo::model()->findByAttributes(array('id'=>$online['avatar'], 'status'=>1));
							if(!empty($photo->name) && file_exists($photo->path .'/thumb160x160/'. $photo->name)){
								$avatar = Yii::app()->createAbsoluteUrl($photo->path .'/thumb160x160/'. $photo->name);
							} else {
								$avatar = Yii::app()->createAbsoluteUrl('/public/images/no-user.jpg');
							}
						} else {
							$avatar = Yii::app()->createAbsoluteUrl(Yii::app()->params['uploads']['avatar']['p150x0']['p'].'/'.$online['avatar']);
						}
					?>
					<li jid="<?php echo $online['username'];?>" id="<?php echo $online['username'];?>-<?php echo Util::chat_domain_to_id(Yii::app()->params['XMPP']['server']);?>" class="item chat-contact">
						<a class="clearfix" href="javascript:void(0);">
							<div class="ava"><img width="32px" border="" height="32px" src="<?php echo $avatar;?>" alt=""></div>
							<div class="info">
								<span class="name"><?php echo $online['username'];?></span><span class="status"><i class="status-online"></i></span>
							</div>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<div class="sprnav">
				<div class="chat-search">
					<div class="input-wrap">
						<button>
							<i class="ismall ismall-search"></i>
						</button>
						<input type="text" placeholder="<?php echo Lang::t('chat', 'Search');?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- online list -->
</div>
<!-- chat area 96898989--> 
<?php } ?>

