<?php 
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->theme->baseUrl . '/resources/css/jquery.autocompletefb.css');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.bgiframe.min.js');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocomplete.js');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocompletefb.js');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/common.js', CClientScript::POS_BEGIN);

$get = Yii::app()->cache->get('viewProfiles_'.Yii::app()->user->id);
$arrProfiles = array();
$total = 0;
if(!empty($get)){
    $arrProfiles = json_decode($get);
    $total = count($arrProfiles);
}

?>
<div class="members feed-hidden">
    <h3>
    	<a href="javascript:void(0);" class="btn-top btn-hide">
    		<i class="imed imed-arrow-left"></i>
			<span class="text"><?php echo Lang::t('general', 'Hide'); ?></span>
		</a>
        <a class="btn-top btn-open-feed" href="javascript:void(0);"><span class="text"><?php echo Lang::t('general', 'Show'); ?></span><i class="imed imed-arrow-right"></i></a>
        <p><span class="result_total">0</span> <?php echo Lang::t('general', 'member_online_now', array('{html_tag_begin}' => '<a class="area" href="javascript:void(0);" title="">', '{html_tag_end}' => '</a>')); ?> <a href="javascript:void(0);" class="reference" title=""></a>
        
        </p>    
        <?php $this->widget('frontend.widgets.popup.Findhim', array()); ?>
    </h3>
    <div class="list clearfix suggest-user-settings">
            <ul>
               
            </ul>
			<div class="more-wrap">
               <a class="showmore show-more-searchresult" style="display: none;" onclick="showmore_searchresult();"><?php echo Lang::t('general', 'Show More'); ?></a>
            </div>
    </div>
    <!-- members list -->
</div>
<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="0" />
<input type="hidden" name="showmore_type" id="showmore_type" value="usersetting" />
<!-- Use for geolocation
<input type="hidden" name="lat" id="lat" />
<input type="hidden" name="lng" id="lng" />
 -->

