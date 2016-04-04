<?php
	$listImage = array_diff(scandir(YiiBase::getPathOfAlias('pathroot').SysBanner::PATH), array('..', '.'));
	$usedImages = array();
	foreach($banners as $banner) {
		$usedImages[] = $banner->file_name;
	}
?>
<?php foreach($listImage as $image): ?>
<div class="img-wrap">
	<a href="#" class="update-banner" data-path="<?php echo SysBanner::PATH ?>" data-file="<?php echo $image ?>">
		<img class="banner-image" src="<?php echo SysBanner::PATH . $image ?>" />
	</a>
	<?php if(!in_array($image, $usedImages)): ?>
	<a title="Delete" class="delete" href="<?php echo $this->createUrl('sysBanner/deleteBackground', array('file_name'=>str_replace('.jpg', '', $image))) ?>">X</a>
	<?php endif; ?>
</div>
<?php endforeach; ?>
<script>
	$(".delete").click(function(){
		var self = $(this);
		var url = self.attr('href');
		self.closest('.img-wrap').remove();
		$.get( url, function( data ) {});
		return false;
	});
	$(".update-banner").click(function(){
		uploadSuccess($(this).data('path'), $(this).data('file'), "");
		$('#overlay').hide();
		return false;
	});
</script>