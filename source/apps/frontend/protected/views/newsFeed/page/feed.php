<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/newsfeed.js');
Yii::app()->clientScript->registerScript('newsfeed', "NewsFeed.MyNewsFeed_init();", CClientScript::POS_END);
$limitViewProfileInDay = Yii::app()->user->getFlash('limitViewProfileInDay');

if(!empty($limitViewProfileInDay) && $limitViewProfileInDay == true){
    Yii::app()->clientScript->registerScript('newsfeed', "NewsFeed.limitViewProfileInDay(\"".Lang::t('general', 'Sorry! You have exceeded your daily profile view limit of 30.')."\", 400);", CClientScript::POS_END);
    Yii::app()->user->setFlash('limitViewProfileInDay', false);
}

$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>''));
?>
<?php if (!empty($this->user)) { ?>
    <div class="wrap-feed left">
        <div class="feed">
        	<div class="title">
        	    <h3 class="left"><?php echo Lang::t("newsfeed", 'News Feed')?></h2>
        	</div>
            <div class="title">
                            <h3 class="left"><?php echo Lang::t("newsfeed", 'News Feed')?></h3>
                            <a class="post_link right" href="#">Post Status</a>
                            <div class="icon_arrow_popup_status"></div>
                            <div class="post_status">                            
                            	<div class="input-wrap">
									<textarea id="" name="" cols="43" rows="6" placeholder="Type your status..." class="status">Type your status...</textarea>								
                                </div>
                                <div class="input-foot">
									<span class="chars">You have <strong id="chars">500</strong> characters remaining</span>
									<div class="btn-wrap-cancel">
                                        <button type="reset" class="btn">Cancel</button>
                                     </div>
                                    <div class="btn-wrap">
										<button data="" class="btn">Post</button>
									</div>									
								</div>
                            </div>
            </div>        	
        	<div class="cont feed-list">
            	<div class="wrap_list_feed">
        			<?php if ($activities['data']) {?>
                		<ul class="feed-list-item">
                			<?php 
                			foreach ($activities['data'] as $data) {
							//is my status
							if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_POST_WALL){
								$status_text_is_me	=	true;
							}else{
								$status_text_is_me	=	false;
							}
							
							if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_PHOTO_UPLOAD){
								$status_photo_is_me	=	true;
							}else{
								$status_photo_is_me	=	false;
							}	
													
                			?>
                			<!-- single news feed item -->
                			<li class="item status_row_<?php echo $data->id; ?>">
                			    <span class="marginline margin-top"></span>
                				<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                                        'data'=>$data,
                						'status_text_is_me'	=>	$status_text_is_me,
                						'status_photo_is_me'	=>	$status_photo_is_me
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
                        
            	    <!-- news feed list -->
                    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset" id="newsfeed_offset" />
                    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
                    <?php if($show_more): ?>
                    <div class="more-wrap-col2 show-more-newsfeed">
            			<a class="showmore" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);" href="javascript:void(0);"><?php echo Lang::t('general', 'Show More'); ?></a>
            		</div>
                    <?php endif; ?>
                    
            	</div>
        	</div>
        </div>
    </div>
    <div class="explore left">
        <?php 
        if($this->user->isMe()){
            CController::forward('/search/index', false); 
        }else{
            CController::forward('/photo/ListPhoto', false);
        }
        ?>
        <!-- members area -->
    </div>
<?php } ?>
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
