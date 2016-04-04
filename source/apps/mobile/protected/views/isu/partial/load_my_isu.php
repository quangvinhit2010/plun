<div class="col-right isu-new col-right-hotnew">
	<div class="pint-style" id="isu-list">
		<ul class="list">
			<?php if($my_isus['data']) { ?>
				<?php foreach ($my_isus['data'] as $key => $my_isu) {?>
					<?php if($isu->id != $my_isu->id) {?>
						<li class="item isu-load" id="isu-<?php echo $my_isu->id;?>">
							<div class="headtitle">
								<a class="ajax_isu" rel="<?php echo $my_isu->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $my_isu->id));?>"><?php echo $my_isu->title;?></a>
								<p class="des"><?php echo Lang::t('isu', 'About');?>: <?php echo date('Y-m-d', $my_isu->date);?> |<br/> <?php echo $my_isu->location;?></p>
							</div>
							<div class="cont">
								<?php if(isset($my_isu->image)) { ?>
									<p class="image">
										<a class="ajax_isu" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $my_isu->id));?>" rel="<?php echo $my_isu->id;?>">
											<?php echo $my_isu->getImageThumb(array('alt' => $my_isu->title, 'border' => ''));?>
										</a>
									</p>
		    					<?php } ?>
								<div class="des"><?php echo Util::partString($my_isu->body, 0, 90);?></div>
							</div>
							<div class="author">
								<a href="<?php echo $my_isu->user->getUserUrl();?>" title="" class="ava"><img src="<?php echo $my_isu->user->getAvatar(); ?>" alt="" border=""/></a>
								<div class="info">
									<p class="name"><span><?php echo $my_isu->user->getUserLink(array('target' => '_blank'));?></span></p>
									<span class="date"><?php echo Util::getElapsedTime($my_isu->created);?></span>
								</div>
							</div>
						</li>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<!-- single ISU item -->
		</ul>
	</div>
</div>
<script>
if (typeof ISU != 'undefined') {
	ISU.isotope();
}	
</script>