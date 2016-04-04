<?php 
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'deleteConversation', 'content'=>'xxxx xxxx'));
?>



<!-- This code must be installed within the body tags -->
<script type="text/javascript">
    var lhnAccountN = "22805";
    var lhnButtonN = 35;
    var lhnChatPosition = 'default';
    var lhnInviteEnabled = 1;
    var lhnWindowN = 0;
    var lhnDepartmentN = 0;
</script>
<a href="http://www.livehelpnow.net/products/live-chat-system" target="_blank" style="font-size:10px;" id="lhnHelp">Live Chat Software</a>
<script src="//www.livehelpnow.net/lhn/widgets/chatbutton/lhnchatbutton-current.min.js" type="text/javascript" id="lhnscript"></script>


<script type="text/javascript">
    var lhnAccountN = "22805";
    var lhnInviteEnabled = 1;
    var lhnWindowN = 0;
    var lhnDepartmentN = 0;
</script>
<script src="//www.livehelpnow.net/lhn/widgets/helpouttab/lhnhelpouttab-current.min.js" type="text/javascript" id="lhnscriptho"></script>



<!--Start Ticketing Software by http://www.livehelpnow.net  -->

<a onclick="window.open('http://www.livehelpnow.net/lhn/TicketsVisitor.aspx?lhnid=22805','Ticket','left=' + (screen.width - 550-32) / 2 + ',top=50,scrollbars=yes,menubar=no,height=550,width=450,resizable=yes,toolbar=no,location=no,status=no');return false;" href="http://www.livehelpnow.net/lhn/TicketsVisitor.aspx?lhnid=22805">
<img src="https://www.livehelpnow.net/lhn/images/153b35_submit_ticket_1.gif" border=0 width=153 height=35></a>

<br />
<a title="Ticketing Software" href="http://www.livehelpnow.net/products/ticket_system/" style="font-size:10px;">Ticketing Software</a>

<!--end Ticketing Software by http://www.livehelpnow.net  -->

											
<script type="text/javascript">
	$(document).ready(function(){
		$( ".popup-alert.deleteConversation" ).pdialog({
			title: tr('Message'),
			buttons: [
						{
						  text: tr("OK"),
						  class: 'active',
						  click: function() {
							  Util.popAlertSuccess('xxxx', 300);
						  }
						},
						{
						  text: tr("Cancel"),
						  click: function() {
							  alert('Cancel !');
						  }
						},
					  ],
		});
	});
</script>
