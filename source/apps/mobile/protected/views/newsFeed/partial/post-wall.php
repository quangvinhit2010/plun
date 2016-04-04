<?php
?>
<li class="item"><span class="marginline margin-top"></span>
	<?php

$this->renderPartial("//newsFeed/partial/_view-newsfeed", 
        array(
                'data' => $data
        ));
?>
    <span class="marginline margin-bottom"></span></li>
<script type="text/javascript">
$('.feedLasted').attr('data-time', '<?php echo $data->timestamp;?>');
</script>