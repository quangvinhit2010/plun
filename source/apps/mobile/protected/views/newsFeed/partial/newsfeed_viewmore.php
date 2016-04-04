<?php if ($activities['data']) { ?>
    <?php
    foreach ($activities['data'] as $data) {
        ?>
        <!-- single news feed item -->
        <li class="item item_showmore">
            <?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                    'data'=>$data,
            ));?>
        </li>
        <!-- single news feed item -->
    <?php } ?>
<?php } else { ?>
<?php
}?>
<?php if(!$show_more){ ?>
<script type="text/javascript">
    if($('#newsfeed_offset_after').attr('id')){
        $('#newsfeed_offset_after').remove();
    }
    NewsFeed.hide_showmore_bt();
</script>
<?php } ?>
<input type="hidden" value="<?php echo $offset; ?>" name="newsfeed_offset_after" id="newsfeed_offset_after" /> 