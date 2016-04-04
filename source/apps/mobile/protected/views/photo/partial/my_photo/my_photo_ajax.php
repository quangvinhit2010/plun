<?php if(isset($photos['data'])) {?>
	<?php foreach ($photos['data'] as $key => $photo) {
			if($is_me){
				$lis_me	=	'true';
			}else{
				$lis_me	=	'false';
			}
		?>
		<li  id="<?php echo $photo->id;?>" <?php echo (($key % 3) == 2) ? 'class="end"' : '';?> >
			<a lis_me="<?php echo $lis_me; ?>" share_photo="true" href="javascript:void(0);" data-photo-id="<?php echo $photo->id;?>" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
				<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle'));?>
			</a>
		</li>
	<?php } ?>
<?php } ?>
<div style="display: none;" id="next_page" page="<?php echo $photos['next_page'];?>"></div>