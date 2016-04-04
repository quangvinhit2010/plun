<script src="<?php echo Yii::app()->params['NodeJs']['socket_url'];?>/socket.io/socket.io.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){  
// 	var data = {};
// 	var name = 'vinh';
	
// 	data[name] = [];
// 	data[name].push({id: "7", name: "Douglas Adams", type: "comedy"});
// 	data[name].push({id: "8", name: "Douglas Adams", type: "comedy"});
	
// 	data.xxx = [];
// 	data.xxx.push({id: "7", name: "Douglas Adams", type: "comedy"});
// 	data.xxx.push({id: "8", name: "Douglas Adams", type: "comedy"});
	
// 	for(var index in data.vinh) { 
// 	   console.log(data.vinh[index]);
// 	}
// 	console.log(data);
// return false;
	
	console.log(io);
	if (typeof io != 'undefined' ){
	    var socket  = io.connect('<?php echo Yii::app()->params['NodeJs']['socket_url'];?>');
	    console.log(socket);
	    $("#chat").hide();
	    $("#userName").focus();
	    $("form").submit(function(event){
	        event.preventDefault();
	    });
	
	    $("#join").click(function(){
	        var userName = $("#userName").val();
	        if (userName != "") {
	            console.log('join');
	            socket.emit("join", userName);
	            $("#login").detach();
	            $("#chat").show();
	            $("#msg").focus();
	            ready = true;
	        }
	    });
	
	    $("#userName").keypress(function(e){
	        if(e.which == 13) {
	            var userName = $("#userName").val();
	            if (userName != "") {
	                socket.emit("join", userName);
	                ready = true;
	                $("#login").detach();
	                $("#chat").show();
	                $("#msg").focus();
	            }
	        }
	    });
	
	    socket.on("update", function(msg) {
	        if(ready)
	            $("#msgs").append("" + msg + "");
	    })
	
	    socket.on("update-people", function(people){
	        if(ready) {
	            $("#people").empty();
	            $.each(people, function(clientid, userName) {
	                $('#people').append(""+ userName + "");
	            });
	        }
	    });
	
	    socket.on("chat", function(who, msg){
	        if(ready) {
	            $("#msgs").append("<br/>" + who + " says: " + msg + "");
	        }
	    });
	
	    socket.on("disconnect", function(){
	        $("#msgs").append(" The server is not available ");
	        $("#msg").attr("disabled", "disabled");
	        $("#send").attr("disabled", "disabled");
	    });
	
	
	    $("#send").click(function(){
	        var msg = $("#msg").val();
	        socket.emit("send", msg, 'vinh');
	        $("#msg").val("");
	    });
	
	    $("#msg").keypress(function(e){
	        if(e.which == 13) {
	            var msg = $("#msg").val();
	            socket.emit("send", msg, 'vinh');
	            $("#msg").val("");
	        }
	    });
	}
});
</script>
<div class="wrapper_body">
<div class="wrapper_container left">
<div class="container">

	<div class="row">
	  <div class="span2">
	      <ul id="people" class="unstyled"></ul>
	  </div>
	  <div class="span4">
	      <ul id="msgs" class="unstyled">
	  </div>
	</div>
	<div class="row">
	    <div class="span5 offset2" id="login">
	        <form class="form-inline">
	              <input type="text" class="input-small" placeholder="Your name" id="userName">
	            <input type="button" name="join" id="join" value="Join" class="btn btn-primary">
	        </form>
	    </div>
	
	    <div class="span5 offset2" id="chat">
	        <form id="2" class="form-inline">
	              <input type="text" class="input" placeholder="Your message" id="msg">
	            <input type="button" name="send" id="send" value="Send" class="btn btn-success">
	        </form>
	    </div>
	  </div>
	</div>

</div>
</div>
</div>

