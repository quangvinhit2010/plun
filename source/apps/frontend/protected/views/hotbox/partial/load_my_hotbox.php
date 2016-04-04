	<div class="col-right col-right-hotnew">
		<div class="pint-style hotbox-new">
		<?php if($my_hotboxs) { ?>
			<ul class="list" id="hotboxs">
				<?php foreach ($my_hotboxs as $key => $my_hotbox) {?>
					<?php if($hotbox->id != $my_hotbox->id) {?>
					<li id="hotbox-<?php echo $my_hotbox->id;?>" class="item hotbox-load hotbox-<?php echo $my_hotbox->getType();?> <?php echo $my_hotbox->getAuthorElement();?> <?php echo $my_hotbox->getFeature();?>" >
						<span class="hotbox-ico"><i class="i36 i36-<?php echo $my_hotbox->getType();?>"></i></span>
						<div class="photo clearfix">
							<?php if(isset($my_hotbox->images)) { ?>
								<ul>
								<?php foreach ($my_hotbox->images as $thumb_key => $image) {?>
									<?php if(empty($my_hotbox->feature->hotbox_id)) {?>
										<?php if($thumb_key < 1) {?>
											<li><a class="load" href="<?php echo $my_hotbox->createUrl();?>" rel="<?php echo $my_hotbox->id;?>" data-slug="<?php echo $my_hotbox->slug;?>" title="">
											<?php echo $image->getImageThumb(array('alt' => $image->id, 'border' => ''));?></a>
											</li>
										<?php } ?>
									<?php } else { ?>
										<?php if($thumb_key < 1) {?>
											<li><a class="load" href="<?php echo $my_hotbox->createUrl();?>" rel="<?php echo $my_hotbox->id;?>" title="" data-slug="<?php echo $my_hotbox->slug;?>">
											<?php echo $image->getImageDetail(array('alt' => $image->id, 'border' => ''));?></a>
											</li>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								</ul>
							<?php } ?>
						</div>
						<div class="headtitle">
							<a class="load" href="<?php echo $my_hotbox->createUrl();?>" rel="<?php echo $my_hotbox->id;?>" data-slug="<?php echo $my_hotbox->slug;?>"><?php echo $my_hotbox->title;?></a>
						</div>
						<!-- 
						<div class="cont">
							<?php if($my_hotbox->getType() == 'event'){?>
								<p class="small-loc"><b><?php echo Lang::t('hotbox', 'Location');?>: </b>Hanoi, Vietnam<p>
								<p class="small-date"><b><?php echo Lang::t('hotbox', 'Date');?>: </b><?php echo date('d/m/Y', $my_hotbox->created);?><p>
							<?php } ?>
						</div>
						 -->
						 <div class="cont">
							<div class="social">
								<ul>
									<li>
										<i class="ismall ismall-like"></i><span class="inline-text"><?php echo Lang::t('hotbox', 'Like');?> (<?php echo $my_hotbox->getLikeCount() ;?>)</span>
									</li>
									<li>
										<i class="ismall ismall-comment"></i><span class="inline-text"><?php echo Lang::t('hotbox', 'Comment');?> (<?php echo $my_hotbox->getCommentCount();?>)</span>
									</li>
								</ul>
							</div>
						 </div>
						 
						 <div class="author">
						 	<?php if($my_hotbox->author->username == 'plunasia') { ?>
								<a class="ava" title="" href="javascript:void(0);">
									<img src="<?php echo $hotbox->author->getAvatar(); ?>">
								</a>
								<div class="info">
									<p class="name"><span><?php echo $my_hotbox->author->getDisplayName();?></span></p>
									<span class="date"><?php echo date('d/m/Y', $hotbox->created);?></span>
								</div>
							<?php } else { ?>
								<a class="ava" title="" href="<?php echo $my_hotbox->author->getUserUrl();?>">
									<img src="<?php echo $my_hotbox->author->getAvatar(); ?>">
								</a>
								<div class="info">
									<p class="name"><span><a href="<?php echo $my_hotbox->author->getUserUrl();?>"><?php echo $my_hotbox->author->getDisplayName();?></a></span></p>
									<span class="date"><?php echo date('d/m/Y', $my_hotbox->created);?></span>
								</div>
							<?php } ?>
						</div>
					</li>
					<?php } ?>
				<?php } ?>
			</ul>
		<?php } ?>
		</div>
	</div>
	<!-- right column -->