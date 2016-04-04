<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/group_chat.css" />
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/chat_group.js'></script>
	
	<div>
      <div id='chat-area'>
        <div>
          <div id='room-name'></div>
          <div id='room-topic'></div>
        </div>
        <div id='chat'>
        </div>

        <textarea id='input'></textarea>
      </div>
    
      <div id='participants'>
        <ul id='participant-list'>
        </ul>
      </div>
	</div>