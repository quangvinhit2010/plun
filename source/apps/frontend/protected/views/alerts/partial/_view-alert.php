<?php
if(!empty($datas)){
foreach ($datas as $data):
	$output = $data->getNotificationData();
    $data->read();
	if(!empty($output)){
?>
    	
        <li>
            <span></span>
            <?php echo $output->message;?> - <label><?php echo (!empty($data->timestamp)) ? date('H:i A', $data->timestamp) : ''?></label>
        </li>
<?php 
	}
endforeach;
}
?>