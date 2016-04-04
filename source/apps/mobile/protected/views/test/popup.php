<?php 
$this->widget('mobile.widgets.UserPage.PopupAlert', array('class'=>'deleteConversation', 'content'=>'Dau xanh rau ma'));
?>
<script type="text/javascript">
$( ".popup-alert.deleteConversation" ).pdialog({
	title: tr('Message'),
	buttons: [
				{
				  text: tr("OK"),
				  click: function() {
					  Util.popAlertSuccess('xxxx', 300);
				  }
				},
				{
				  text: tr("Cancel"),
				  click: function() {
					  alert('Cancel roi ma oi !');
				  }
				},
			  ],
});
</script>