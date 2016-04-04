<?php
$mobileMediaFilter = new MobileMediaFilter();

if(!empty($datas)){
foreach ($datas as $data):
	$output = $data->getNotificationData();
    $data->read();
	if(!empty($output)){
?>
    	
        <li>
            <span class="dotListAlert"></span>
            <?php echo $mobileMediaFilter->filter($output->message) ?> - <label><?php echo (!empty($data->timestamp)) ? date('H:i A', $data->timestamp) : ''?></label>
        </li>
<?php 
	}
endforeach;

}
?>