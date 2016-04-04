<?php
	$time = time();
	foreach($backgrounds as $k=>$b):
?>
<div class="img-wrap">
	<a href="<?php echo $this->createUrl('default/UpdateBackground', array('id'=>$b->id)) ?>">
		<img src="<?php echo '/uploads/background/' . $b->file_name . '.jpg?' . $k.$time ?>" />
	</a>
	<a title="Delete" class="delete" href="<?php echo $this->createUrl('default/DeleteBackground', array('id'=>$b->id)) ?>">X</a>
</div>
<?php endforeach; ?>
<script>
	$('#popup a:first').click(function(){
		var self = $(this);
		var url = self.attr('href');
		var formWrap = $('#'+$('#overlay').data('current')).closest('.form-wrap');
		formWrap.find('> form > img').show();
		$('#overlay').trigger('click');
		$.get( url, function( data ) {
			formWrap.find('> form > img').hide();
			var img = formWrap.find('> img');
			d = new Date();
			img.attr('src', img.data('src') + '?' + d.getTime());
		});
		return false;
	});
	$('#popup a.delete').click(function(){
		var self = $(this);
		var url = self.attr('href');
		self.closest('.img-wrap').remove();
		$.get( url, function( data ) {});
		return false;
	});
</script>