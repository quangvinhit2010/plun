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
</li>
<div style="display: none;" id="is_show_more" is_show_more="<?php echo $is_show_more;?>"></div>