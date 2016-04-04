<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/xmpp.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat/plun.cookie.js"></script>
				<div class="col-feed col-left">
					<div class="block news-feed chat">
						<div class="title">
							<h2>Chat</h2>
						</div>
						<div class="cont feed-list">
							<?php if(!empty($friend_list)) {?>
							<div class="chat-search clearfix">
                                    	<form class="frmSearchChat" action="#" method="post" name="frmSearchChat">
                                        	<input type="text" class="form-control" placeholder="Search"/>
                                            <button type="button" class="btnSearch">Go!</button>
                                        </form>
									</div>
							<ul class="feed-list-item roster-area">
									
								<?php if(isset($to)){
									
								}
									
									?>
								<?php foreach ($friend_list as $key => $friend) {?>
									<?php 
									if(Yii::app()->user->id == $friend->inviter_id ) {
										$fUser = $friend->invited;
									}else{
										$fUser = $friend->inviter;
									}
									?>
									<li class="item" id="<?php echo Util::chat_domain_to_id($fUser->username);?>-<?php echo Util::chat_domain_to_id(Yii::app()->params['XMPP']['server']);?>">
										<div class="feed clearfix roster-contact offline">
											<a href="javascript:void(0);" title="" class="ava"><img src="<?php echo $fUser->getAvatar();?>" alt="" border=""/></a>
											<div class="roster-name"><h4><?php echo $fUser->getDisplayName();?></h4></div>
											<div class="roster-jid"><?php echo $fUser->username;?>@<?php echo Yii::app()->params['XMPP']['server'];?></div>
											<span id="chat-status" class="status">&nbsp;</span>
										</div>
									</li>
									<!-- single news feed item -->
									<!--
									<li class="item" id="<?php //echo $fUser->username;?>-test-plun-asia">
										<div class="feed clearfix">
											<a href="javascript:void(0);" title="" class="ava"><img src="<?php //echo $fUser->getAvatar();?>" alt="" border=""/></a>
	                                        <h4><?php //echo $fUser->getDisplayName();?></h4>
											<span id="chat-status" class="status-on">&nbsp;</span>
										</div>
									</li>
									-->
								<?php } ?>
							</ul>
							<?php } ?>
						</div>
						<!-- news feed list -->
						<div class="padding"></div>
					</div>
					<!-- news feed -->
				</div>
				<!-- left column -->
				<div class="col-right">
					 <div id='chat-area' class="chat-area">
				      <ul class="head-tab"></ul>
				    </div>
				    
                	<div class="chat-more">
                        <!--a class="more" href="javascript:void(0);" title="" data-toggle="dropdown">more</a-->
                        <div class="setting-top">
                            <div class="setting-top-board">
                                <!--ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="inline-text">Tommy Nguyen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="inline-text">Tommy Nguyen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="inline-text">Tommy Nguyen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="inline-text">Tommy Nguyen</span>
                                        </a>
                                    </li>
                                </ul-->
                            </div>
                        </div>
                    </div>
					<!-- block-chat area -->
				</div>
				<!-- right column -->
				
<script>
	<?php if(isset($to->profile)) { ?>
		Gab.open_chat_by_url("<?php echo $to->username;?>@" + XMPP_SERVER, "<?php echo $to->profile->firstname .' '.$to->profile->lastname;?>");
	<?php } ?>
</script>
