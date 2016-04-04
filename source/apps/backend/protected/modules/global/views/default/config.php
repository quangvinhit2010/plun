<?php $this->pageTitle=Yii::app()->name; ?>

<h2>Config Global</h2>
<div class="config">
	<form method="post" action="" id="config-form">
	<?php 
	if(!empty($_config_vars)){
	?>
			<?php 
			foreach ($_config_vars as $key=>$value){
			?>
				<div class="row">
					<label for="<?php echo $key;?>"><?php echo ucfirst($key);?></label>		
					<textarea rows="2" cols="30" id="<?php echo $key;?>" name="<?php echo $key;?>"><?php echo $value;?></textarea>	
				</div>
			<?php 
			}
			?>
	<?php 
	}
	?>
	
	
	<h2>Add new variable</h2>
	<div class="row">
		<label for="variable">Variable</label>		
		<input id="variable" name="newitem[variable]" value="" />		
	</div>
	<div class="row">
		<label for="value">Value</label>		
		<textarea rows="2" cols="30" name="newitem[value]"></textarea>	
	</div>
	<input type="submit" value="Save">
	</form>
</div>

<script type="text/javascript">
$('#variable').blur(function() {
	var text = $(this).val();
	x = text.replace(/[^a-z0-9\s]/gi, '_').replace(/[_\s]/g, '_');
	$(this).val(x);
});

</script>