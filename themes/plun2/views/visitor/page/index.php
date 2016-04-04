<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/visitor.js?t='.time(), CClientScript::POS_END);
?>

<div class="container pheader min_max_1024 clearfix hasBanner_160 visitor_page"> 
    <div class="explore left">
        <div class="list_explore">
            <div class="online_num">
                <div class="left title"><?php echo Lang::t('general', 'Visitor');?> <span class="icon_common icon_discript"></span></div>
            </div> 
            <div class="list_visitor loadingItem" data-url="<?php echo $this->usercurrent->createUrl('/visitor/listItem');?>">
                
            </div>
        </div>
        <?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){        
        objCommon.tooltipPlun({
            el: '.visitor_page .online_num .title span.icon_common',
            posiRight: true,
            titleTip: '<?php echo Lang::t('visitor', 'Visitors let you see who visited you and when.')?>'
        });
    });
</script>