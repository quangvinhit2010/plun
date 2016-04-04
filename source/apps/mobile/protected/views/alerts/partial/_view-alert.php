<?php
if(!empty($datas)){
foreach ($datas as $data):
	$output = $data->getNotificationData();
    $data->read();
	if(!empty($output)){
?>
    	
        <li>
            <i></i>
            <span></span>
            <?php echo $output->message;?> - <span class="time"><?php echo (!empty($data->timestamp)) ? date('H:i A', $data->timestamp) : ''?></span>
        </li>
<?php 
	}
endforeach;
}
?>