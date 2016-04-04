<div class="data">
    <?php if ($activities) {?>
    <ul class="feed-list-update">
    	<?php 
    	foreach ($activities as $data) {
    	?>
    	<!-- single news feed item -->
    	<li class="item">
    	    <span class="marginline margin-top"></span>
    		<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                    'data'=>$data,
            ));?>
    	    <span class="marginline margin-bottom"></span>
    	</li>
    	<?php }?>
    </ul>
    <?php }?>
    <?php 
	$time = '';
	if(!empty($activities[0])){
	    $time = $activities[0]->timestamp;
	}
	?>
    <div class="feedLasted" data-time="<?php echo $time;?>"></div>
</div>