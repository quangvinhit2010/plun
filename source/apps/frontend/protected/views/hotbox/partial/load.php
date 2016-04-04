<?php if(isset($hotbox)) {?>
	<?php $this->renderPartial('partial/filter');?>
	<div class="col-feed col-left">
		<div class="block news-feed hotbox_detail">
			<a href="#" class="btn-del-hotbox"></a>
			<div class="detail_hotbox">
				<h2><?php echo $hotbox->title;?></h2>
				<!-- 
				<?php if($hotbox->type == Hotbox::EVENT && isset($hotbox->events)) {?>
				
					<?php 
						$event =  HotboxEvent::model()->findByAttributes(array('hotbox_id' => $hotbox->id));
					?>
					<div class="date_location">
						<b><?php echo Lang::t('hotbox', 'Title');?>:</b> <?php echo $event->title;?>
						<b><?php echo Lang::t('hotbox', 'Date');?>:</b> <?php echo date('d/m/Y', $event->start);?> - <?php echo date('d/m/Y', $event->end);?>   
					</div>
				<?php } ?>
				 -->
				<?php if($hotbox->author_id == Yii::app()->user->id && $hotbox->status == Hotbox::PENDING) {?>
					<div class="public_hotbox"><?php echo Lang::t('hotbox', 'This post is pending for approval');?></div>
				<?php } ?>
				<div class="post_by">
					<?php if($hotbox->author->username == 'plunasia') { ?>
						<a href="javascript:void(0);" class="avatar_post">
							<img src="<?php echo $hotbox->author->getAvatar(); ?>" align="absmiddle" width="24px" height="24px">
						</a> 
						<b>PLUN Asia</b>
					<?php } else {?>
						<a href="<?php echo $hotbox->author->getUserUrl();?>" class="avatar_post">
							<img src="<?php echo $hotbox->author->getAvatar(); ?>" align="absmiddle" width="24px" height="24px">
						</a> 
						<b><a href="<?php echo $hotbox->author->getUserUrl();?>"><?php echo $hotbox->author->getDisplayName();?></a></b>
					<?php } ?>
					<span><?php echo Util::getElapsedTime($hotbox->created);?></span>
                    <?php if($hotbox->author_id == Yii::app()->user->id && $hotbox->status == Hotbox::PENDING) {?>
	                    <div class="pos_reply_forward">
	                    	<ul>
	                        	<li class="edit"><a href="<?php echo Yii::app()->createUrl('//hotbox/edit', array('id' => $hotbox->id));?>"><?php echo Lang::t('hotbox', 'Edit');?></a></li>
	                            <li class="delete"><a onclick="Hotbox.remove(<?php echo $hotbox->id;?>);"><?php echo Lang::t('hotbox', 'Delete');?></a></li>
							</ul>
						</div>
					<?php } ?>
				</div>
				
				<div class="content">
						<?php if(isset($hotbox->images)) { ?>
							<div class="zoom_pics">
								<?php foreach ($hotbox->images as $key => $image) {?>
									<?php if($key == 0) { ?>
										<?php if($hotbox->thumbnail_id > 0) {?>
											<?php if($image->getThumbnailImage($hotbox->thumbnail_id, 2, array()) != null) {?>
												<a href="javascript:void(0);"><?php echo $image->getThumbnailImage($hotbox->thumbnail_id, 2, array('alt' => $image->id, 'border' => '', 'align' => 'absmiddle'));?></a>
											<?php } else {?>
												<a href="javascript:void(0);"><?php echo $image->getImageDetail(array('alt' => $image->id, 'border' => '', 'align' => 'absmiddle'));?></a>
											<?php } ?>
										<?php } else { ?>
												<a href="javascript:void(0);"><?php echo $image->getImageDetail(array('alt' => $image->id, 'border' => '', 'align' => 'absmiddle'));?></a>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							
							<?php if(count($hotbox->images) > 1) {?>
								<div class="mask_pics">
									<span><a href="javascript:void(0);"><label><?php echo Lang::t('hotbox', 'View gallery');?></label></a></span>                                     
								</div> 
							<?php } ?>
							</div>
						<?php } ?>
					<p><?php echo $hotbox->body;?></p>
				</div>
				<div class="share_box">
					Share <input id="share-content" type="text" value="<?php echo $hotbox->createShareUrl(true);?>" onclick="this.select()">
				</div>
				<?php $this->renderPartial('partial/comment', array('hotbox' => $hotbox, 'comments' => $comments, 'is_like' => $is_like));?>
			</div>
		</div>
		<!-- news feed -->
		<?php //$this->renderPartial('partial/gallery', array('hotbox' => $hotbox));?>
		<?php $this->renderPartial('partial/this_hotbox', array('hotbox' => $hotbox));?>
		
	</div>
	<!-- left column -->
	<?php $this->renderPartial('partial/load_my_hotbox', array('hotbox' => $hotbox, 'my_hotboxs' => $my_hotboxs));?>
<?php } ?>
