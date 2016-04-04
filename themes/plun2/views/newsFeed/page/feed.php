<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/newsfeed.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/navigation.js');
if(Yii::app()->user->hasFlash('limitViewProfileInDay')){	
	$txt = Lang::t('general', 'Sorry! You have exceeded your daily profile view limit of 30.');
    Yii::app()->clientScript->registerScript('newsfeedLimitViewProfileInDay', "NewsFeed.limitViewProfileInDay(\"$txt\", 400);");
    Yii::app()->user->setFlash('limitViewProfileInDay', false);
}

$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>''));

Yii::app()->clientScript->registerScript('suggestFriends',"
        $(document).ready(function(){
            $('.list_explore .list_user ul li').boxResizeImg({
                wrapContent: true
            });
            $('.list-preview-photo').colorbox({
			        	  slideshowAuto: false,
			        	  fixed: true,
			        	  scrolling: false,
			        	  innerHeight: true,
			        	  scalePhotos: true,
			        	     maxWidth: '100%',
			        	  maxHeight: '95%'
		    });
			
		});
        window.onload = function(){
            $('.sticky_column').fixed_col_scroll();
        }",
CClientScript::POS_END);
?>
<div class="container pheader wrap_scroll clearfix explores_page">
<?php if (!empty($this->user)) { ?>
    <div class="wrap-feed left">
        <div class="shadow_top"></div>
        <div class="feed sticky_column">
            <div class="title">
                            <h3 class="left"><?php echo Lang::t('newsfeed', 'News Feed'); ?></h3>
                            <a class="post_link right" href="javascript:void(0);"><?php echo Lang::t('newsfeed', 'Post Status'); ?></a>
                            <?php 
                            $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'wall-status-form',
								'action' => $this->usercurrent->createUrl('//newsFeed/postWall'),
							)); ?>
                            <div class="post_status" style="display: none;">                            
                                <div class="icon_arrow_popup_status"></div>
                            	<div class="input-wrap">
                            		<?php echo $form->textArea(new WallStatus(),'status', array('class' => 'status', 'placeholder' => Lang::t('newsfeed', 'Type your status').'...', 'rows' => 6, 'cols' => 43)); ?>
                                </div>
                                <div class="input-foot">
									<span class="chars"><?php echo Lang::t('general', 'You have {count} characters remaining', array('{count}'=>'<strong id="chars">500</strong>'))?></span>
									<div class="newfeed-emo-wrap">
										<div class="emoticons-item"><span class="emo emo-1"></span><span class="emo emo-2"></span><span class="emo emo-3"></span><span class="emo emo-4"></span><span class="emo emo-5"></span><span class="emo emo-6"></span><span class="emo emo-7"></span><span class="emo emo-8"></span><span class="emo emo-9"></span><span class="emo emo-10"></span><span class="emo emo-11"></span><span class="emo emo-12"></span><span class="emo emo-13"></span><span class="emo emo-14"></span><span class="emo emo-15"></span><span class="emo emo-16"></span><span class="emo emo-17"></span><span class="emo emo-18"></span><span class="emo emo-19"></span><span class="emo emo-20"></span><span class="emo emo-21"></span></div>
										<span class="emoticons"></span>
									</div>
									<div class="btn-wrap-cancel">
                                        <button type="reset" class="btn"><?php echo Lang::t('newsfeed', 'Cancel')?></button>
                                     </div>
                                    <div class="btn-wrap">
										<button data="" class="btn"><?php echo Lang::t('newsfeed', 'Post')?></button>
									</div>									
								</div>
                            </div>
                            <?php $this->endWidget(); ?>
            </div>        	
        	<div class="content">
        			<?php if ($total_newsfeed) {?>
                		<ul>
                			
                		</ul>
                		<div style="display: none;" class="feedLasted" data-url="<?php echo $this->user->createUrl('/newsFeed/feedUpdate')?>" data-time=""></div>
            		<?php }else{ ?>
                		<div class="no-status_feed">
								<a class="icon_post_status" href="javascript:void(0);" onclick="NewsFeed.showFindhim();"><i class="icon_common"></i><span><?php echo strtoupper(Lang::t('general', 'No content to show'));?></span></a>
                		        <div>
                                	<a class="btn_big btn_shadow btn_gray" href="javascript:void(0);" onclick="NewsFeed.showFindhim();"><?php echo Lang::t('general', 'Search for friends')?></a>
                                </div>
                        </div>
            		<?php }?>
                        
            	    <!-- news feed list -->
                    <input type="hidden" value="0" name="newsfeed_offset" id="newsfeed_offset" />
                    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
                    <?php if($show_more): ?>
            		<div class="pagging">
                    	<a style="display: none;" class="showmore" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);" href="javascript:void(0);"><ins></ins></a>
                    </div>
                    <?php endif; ?>
        	</div>
        	<div class="clear"></div>
        </div>
    </div>
    <div class="explore left explore_page">
        <div class="clearfix sticky_column wrap_myexplore">
            <?php
            if($this->user->isMe()){
                CController::forward('/search/index', false);
            }else{
                CController::forward('/photo/ListPhoto', false);
            }
            ?>
            <?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
        </div>
        <!-- members area -->
    </div>
<?php } ?>
</div>

<div id="popup_birthday" class="popup_genneral" style="display:none;">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/birthday-banner.jpg" />
</div>

<script type="text/javascript">
    jQuery('document').ready(function(){
        <?php if(!empty($this->e_user['birthday']) && date('d-m', $this->e_user['birthday']) == date('d-m', time())):?>
        setTimeout(function(){
            $('#popup_birthday').pdialog({
                open: function(event, ui) {
                    $("body").css({ overflow: 'hidden' });
                    objCommon.no_title(this); // config trong file jquery-ui.js
                    objCommon.outSiteDialogCommon(this);
                },
                width: 860,
                dialogClass: 'test'
            });
        },1500);
        <?php endif;?>

    	NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);
		<?php if(Yii::app()->user->getFlash('forgotChangPasss')){?>
	    	Util.popAlertSuccess('<?php echo Lang::t('forgot', 'Your password has been changed!')?>', 400);
			setTimeout(function () {
				window.location = '<?php echo Yii::app()->homeUrl;?>';
			}, 3000);
		<?php }?>
    });
</script>
<div id="popupUserCheckIn" class="popup_general" style="display:none;">
    
</div>      
                        <?php 
                        Yii::app()->clientScript->registerScript('checkin_newsfeed',"
							$(document).on('click','.popupListCheckIn',function(){
								objCommon.loading();
								$.ajax({
									type: 'POST',
									url: $(this).attr('href'),
									dataType: 'html',
									success: function(res){
										$('#popupUserCheckIn').html('');
										$('#popupUserCheckIn').html(res);
										if($('#popupUserCheckIn .scrollPopup ul li').length > 5)
										    objCommon.sprScroll('#popupUserCheckIn .scrollPopup');
										else
										    $('#popupUserCheckIn .scrollPopup').css('height','auto');
										$('#popupUserCheckIn').pdialog({
											open: function(){
												objCommon.outSiteDialogCommon(this);
												objCommon.no_title(this);
											},
											width: 370
										});
										objCommon.unloading();
									}
								});
								return false;	
							});
							     ",
                        CClientScript::POS_END);
                        ?>
