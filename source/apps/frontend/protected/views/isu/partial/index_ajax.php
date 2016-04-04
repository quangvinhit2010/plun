<?php if(isset($isus)) { ?>
	<?php foreach ($isus as $key => $value) {?>
		<li class="item isu-load" id="isu-<?php echo $value->id;?>">
			<div class="headtitle">
				<a class="ajax_isu" rel="<?php echo $value->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id));?>"><?php echo $value->title;?></a>
				<p class="des"><?php echo Lang::t('isu', 'About');?> <?php echo date('d/m/Y', $value->date);?> <br/> <?php echo $value->location;?></p>
			</div>
			<div class="cont">
				<?php if(isset($value->image)) { ?>
					<p class="image">
						<a class="ajax_isu" rel="<?php echo $value->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id));?>" rel="<?php echo $value->id;?>">
						<?php echo $value->getImageThumb(array('alt' => $value->title, 'border' => ''));?>
						</a>
					</p>
    			<?php } ?>
				<div class="des"><?php  echo Util::partString($value->body, 0, 200);?></div>
			</div>
			<div class="author">
				<a href="javascript:void(0);" title="" class="ava"><?php echo $value->user->getAvatar(true); ?></a>
				<div class="info">
					<p class="name"><span><?php echo $value->user->getDisplayName();?></span></p>
					<span class="date"><?php echo Util::getElapsedTime($value->created);?></span>
				</div>
			</div>
		</li>
	<?php } ?>
	<div style="display: none;" id="next_page" page="<?php echo $next_page;?>"></div>
<?php } ?>