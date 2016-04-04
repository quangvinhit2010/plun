<?php if(isset($photos['data'])) {?>
	<?php foreach ($photos['data'] as $photo) {?>
	<li class="item" id="<?php echo $photo->id; ?>">
		<a class="ava" title="" href="javascript:void(0);" lis_me="true" data-photo-id="<?php echo $photo->id;?>" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
			<?php echo $photo->getImageThumbnail();?>
			<span class="ava-bg"></span>
		</a>
		<div class="ulti ulti-done">
			<ul>
				<li><a class="btn-del" href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);"><i class="ismall ismall-x"></i></a></li>
			</ul>
		</div>
		<div class="chiase_hinhanh"><a href="javascript:void(o);" photoid="<?php echo $photo->id;?>" smallimg="<?php echo $photo->getImageThumbnail(true);?>" onclick="Photo.sharePhoto(this);"><?php echo Lang::t('photo', 'Share Photo To Everyone'); ?></a></div>
		
	</li>
	<?php } ?>
<?php } ?>
<div style="display: none;" id="next_page" page="<?php echo $photos['next_page'];?>"></div>