	<div class="main-wrap">
		<!-- header but display at bottom -->
			<?php $this->renderPartial('partial/filter');?>
				<!-- page filter -->
				<div class="col-full">
					<?php if(isset($hotboxs)){ ?>
						<div class="pint-style hotbox-new" id="hb-list">
							<ul class="list" id="hotboxs" page="<?php echo $next_page;?>">
								<?php foreach ($hotboxs as $key => $hotbox) {?>
									<li id="hotbox-<?php echo $hotbox->id;?>" class="item hotbox-load hotbox-<?php echo $hotbox->getType();?> <?php echo $hotbox->getAuthorElement();?> <?php echo $hotbox->getFeature();?>">
										<span class="hotbox-ico"><i class="i36 i36-<?php echo $hotbox->getType();?>"></i></span>
										<div class="photo clearfix">
											<?php if(isset($hotbox->images)) { ?>
												<ul>
												<?php foreach ($hotbox->images as $thumb_key => $image) {?>
													<?php if(empty($hotbox->feature->hotbox_id)) {?>
														<?php if($thumb_key < 1) {?>
															<li><a class="load" href="<?php echo $hotbox->createUrl();?>" rel="<?php echo $hotbox->id;?>" title="">
															<?php echo $image->getImageThumb(array('alt' => $image->id, 'border' => ''));?></a>
															</li>
														<?php } ?>
													<?php } else { ?>
														<?php if($thumb_key < 1) {?>
															<li><a class="load" href="<?php echo $hotbox->createUrl();?>" rel="<?php echo $hotbox->id;?>" title="">
															<?php echo $image->getImageDetail(array('alt' => $image->id, 'border' => ''));?></a>
															</li>
														<?php } ?>
													<?php } ?>
												<?php } ?>
												</ul>
											<?php } ?>
										</div>
										<div class="headtitle">
											<a class="load" href="<?php echo $hotbox->createUrl();?>" rel="<?php echo $hotbox->id;?>" data-slug="<?php echo $hotbox->slug;?>"><?php echo $hotbox->title;?></a>
										</div>
										<div class="cont">
											<div class="social">
												<ul>
													<li>
														<i class="ismall ismall-like"></i><span class="inline-text"><?php echo Lang::t('hotbox', 'Like');?> (<?php echo $hotbox->getLikeCount() ;?>)</span>
													</li>
													<li>
														<i class="ismall ismall-comment"></i><span class="inline-text"><?php echo Lang::t('hotbox', 'Comment');?> (<?php echo $hotbox->getCommentCount();?>)</span>
													</li>
												</ul>
											</div>
										</div>
										<div class="author">
											<?php if($hotbox->author->username == 'plunasia') { ?>
												<a class="ava" title="" href="javascript:void(0);">
													<img src="<?php echo $hotbox->author->getAvatar(); ?>">
												</a>
												<div class="info">
													<p class="name"><span><?php echo $hotbox->author->getDisplayName();?></span></p>
													<span class="date"><?php echo date('d/m/Y', $hotbox->created);?></span>
												</div>
											<?php } else { ?>
												<a class="ava" title="" href="<?php echo $hotbox->author->getUserUrl();?>">
													<img src="<?php echo $hotbox->author->getAvatar(); ?>">
												</a>
												
												<div class="info">
													<p class="name"><span><a href="<?php echo $hotbox->author->getUserUrl();?>"><?php echo $hotbox->author->getDisplayName();?></a></span></p>
													<span class="date"><?php echo date('d/m/Y', $hotbox->created);?></span>
												</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
						<!-- pinterest style -->
						<?php if($pages->pageCount > 1) {?>
							<div class="more-wrap" style="text-align: center;">
								<a class="showmore" onclick="Hotbox.show_more();" href="javascript:void(0);"><?php echo Lang::t('general', 'Show More'); ?></a>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		
