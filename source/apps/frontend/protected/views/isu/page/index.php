<div class="main-wrap isu">
<?php $this->renderPartial('partial/filter');?>
<div class="col-full isu-new">
	<?php if(isset($isus)){ ?>
		<div class="pint-style" id="isu-list">
			<ul class="list" id="isus" page="<?php echo $next_page;?>">
					<?php foreach ($isus as $key => $value) {?>
					<li class="item isu-load <?php echo $value->getAuthorElement();?>" id="isu-<?php echo $value->id;?>">
						<div class="headtitle">
							<a class="ajax_isu" rel="<?php echo $value->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id));?>"><?php echo $value->title;?></a>
							<p class="des"><?php echo Lang::t('isu', 'About');?> : <?php echo date('Y-m-d H:i', $value->date);?> - <?php echo date('Y-m-d H:i', $value->end_date);?><br/>
							 <?php echo Lang::t('isu', 'Location');?> : <?php echo $value->getLocation();?></p>
						</div>
						<div class="cont">
							<?php if(isset($value->image)) {?>
							<p class="image">
								<a class="ajax_isu" rel="<?php echo $value->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id));?>" rel="<?php echo $value->id;?>">
								<?php echo $value->getImageThumb(array('alt' => $value->title, 'border' => ''));?>
								</a>
							</p>
							<?php } ?>
							<div class="des"> <?php echo Util::partString($value->body, 0, 90);?> </div>
						</div>
						<div class="author">
							<a href="javascript:void(0);" title="" class="ava"><?php echo $value->user->getAvatar(true); ?></a>
							<div class="info">
								<p class="name"><span><?php echo $value->user->getUserLink(array('target' => '_blank'));?></span></p>
								<span class="date"><?php echo Util::getElapsedTime($value->created);?></span>
							</div>
						</div>
					</li>
					<?php } ?>
					<!-- single ISU item -->
				<!-- single ISU item -->
			</ul>
		</div>
		<!-- pinterest style -->
		<?php if($pages->pageCount > 1) {?>
			<div class="more-wrap" style="text-align: center;">
				<a class="showmore" onclick="ISU.show_more();" href="javascript:void(0);"><?php echo Lang::t('general', 'Show More'); ?></a>
			</div>
		<?php } ?>
	<?php } ?>
	
</div>
</div>
<div class="popupBoxShow_hotbox_isu" style="display:none;"></div>
<div class="popupBoxShow_isu_reply" style="display:none;"></div>
<div class="popupBoxShow_isu_forward" style="display:none;"></div>