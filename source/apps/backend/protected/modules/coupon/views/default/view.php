<?php $this->pageTitle=Yii::app()->name; ?>
<div class="event-content">
	<div class="title"><?php echo $event->title; ?></div>
	<?php if(isset($code)) :?>
		<div class="code">
			<div class="giftcode">
				<span class="output" id="output"><?php echo $code->code;?></span>
				<button class="btnSelect">Select</button>
			</div>
		</div>
		<div class="note">
			<?php echo $event->note;?>
		</div>
	<?php else: ?>
		<div class="notify">Đã hết giftcode cho event này.</div>	
	<?php endif; ?>	
</div>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<script type="text/javascript">
	$(function(){
		$('.btnSelect').click(function(){
			SelectText('output');
		});
	});
	function SelectText(element) {
	    var doc = document;
	    var text = doc.getElementById(element);    
	    if (doc.body.createTextRange) {
	        var range = document.body.createTextRange();
	        range.moveToElementText(text);
	        range.select();
	    } else if (window.getSelection) {
	        var selection = window.getSelection();        
	        var range = document.createRange();
	        range.selectNodeContents(text);
	        selection.removeAllRanges();
	        selection.addRange(range);
	    }
	}
</script>