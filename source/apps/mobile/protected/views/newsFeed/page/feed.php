<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/newsfeed/common.js');
Yii::app()->clientScript->registerScript('MyNewsFeed_init', "NewsFeed.MyNewsFeed_init();", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('PostStatus', "NewsFeed.PostStatus();", CClientScript::POS_END);
$limitViewProfileInDay = Yii::app()->user->getFlash('limitViewProfileInDay');

if(!empty($limitViewProfileInDay) && $limitViewProfileInDay == true){
    Yii::app()->clientScript->registerScript('newsfeed', "NewsFeed.limitViewProfileInDay(\"".Lang::t('general', 'Sorry! You have exceeded your daily profile view limit of 30.')."\", 400);", CClientScript::POS_END);
    Yii::app()->user->setFlash('limitViewProfileInDay', false);
}
?>
<div class="feed_explore">
  <ul>
    <li class="mar_rig_10"><a href="<?php echo Yii::app()->homeUrl;?>"><?php echo Lang::t('general', 'Explore'); ?></a></li>
    <li class="active"><a href="#"><?php echo Lang::t('general', 'Feeds'); ?></a></li>
  </ul>
</div>

<div class="pad_left_10 pad_top_10">                  
  	<div class="type_status">
  	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'wall-status-form',
		'action' => $this->usercurrent->createUrl('//newsFeed/postWall'),
	)); ?>
        <?php echo $form->textArea(new WallStatus(),'status', array('class' => 'cmt-post-status', 'placeholder' => Lang::t('newsfeed', 'Type your status').'...')); ?>
    <?php $this->endWidget(); ?>
    </div>
  	
  	<div class="left list_status">
        <?php if ($activities['data']) {?>
		<ul class="feed-list-item">
			<?php 
			foreach ($activities['data'] as $data) {
			?>
			<!-- single news feed item -->
			<li class="item">
			    <span class="marginline margin-top"></span>
				<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                        'data'=>$data,
                ));?>
			    <span class="marginline margin-bottom"></span>
			</li>
			<!-- single news feed item -->
			<?php }?>
		</ul>
		<?php 
		$time = '';
		if(!empty($activities['data'][0])){
		    $time = $activities['data'][0]->timestamp;
		}
		?>
		<div style="display: none;" class="feedLasted" data-url="<?php echo $this->user->createUrl('/newsFeed/feedUpdate')?>" data-time="<?php echo $time;?>"></div>
	<?php }else{ ?>
		<ul class="feed-list-item">
		    <li>
		        <p class="no-request-friends"><?php echo Lang::t("newsfeed", 'No news feed')?></p>
		    </li>
		</ul>
	<?php }?>
    </div>
</div>

<?php if($show_more): ?>
    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset" id="newsfeed_offset" />
    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" />
    <div class="block_loading showmore show-more-searchresult"><a href="javascript:void(0);" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);"><span></span></a></div>
<?php endif; ?>
    

<?php if(Yii::app()->user->getFlash('forgotChangPasss')){?>
<script type="text/javascript">
    jQuery('document').ready(function(){
    	Util.popAlertSuccess('<?php echo Lang::t('forgot', 'Your password has been changed!')?>', 400);
		setTimeout(function () {
			window.location = '<?php echo Yii::app()->homeUrl;?>';
		}, 3000);
    });
</script>
<?php }?>
