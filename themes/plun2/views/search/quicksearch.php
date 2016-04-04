<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/newsfeed.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/navigation.js');
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>''));

Yii::app()->clientScript->registerScript('QuickSearch',"
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
			objCommon.loadvirtualformSettings();
		});
        window.onload = function(){
            //$('.sticky_column').fixed_col_scroll();
        }",
CClientScript::POS_END);
?>

<div class="container pheader wrap_scroll clearfix explores_page">
  <?php if (!empty($this->user)) { ?>
  <div class="wrap-feed left sticky_column">
    <div class="feed">
      <div class="title">
        <h3 class="left"><?php echo Lang::t('newsfeed', 'News Feed'); ?></h3>
        <a class="post_link right" href="javascript:void(0);"><?php echo Lang::t('newsfeed', 'Post Status'); ?></a>
        <?php 
                            $userCurrent =  Yii::app()->user->data();
                            $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'wall-status-form',
								'action' => $userCurrent->createUrl('//newsFeed/postWall'),
							)); ?>
        <div class="post_status" style="display: none;">
          <div class="icon_arrow_popup_status"></div>
          <div class="input-wrap"> <?php echo $form->textArea(new WallStatus(),'status', array('class' => 'status', 'placeholder' => Lang::t('newsfeed', 'Type your status').'...', 'rows' => 6, 'cols' => 43)); ?> </div>
          <div class="input-foot"> <span class="chars"> <?php echo Lang::t('general', 'You have {count} characters remaining', array('{count}'=>'<strong id="chars">500</strong>'))?> </span>
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
                		<ul></ul>
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
                    <input type="hidden" value="<?php echo $limit_newsfeed; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
                    <?php if($show_more): ?>
            		<div class="pagging">
                    	<a style="display: none;" class="showmore" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);" href="javascript:void(0);"><ins></ins></a>
                    </div>
                    <?php endif; ?>
        	</div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="explore left sticky_column explore_page">
    <?php 
			$get = Yii::app()->cache->get('viewProfiles_'.Yii::app()->user->id);
			$arrProfiles = array();
			$total = 0;
			if(!empty($get)){
			    $arrProfiles = json_decode($get);
			    $total = count($arrProfiles);
			}
			//build current area
			$current_area	=	array();

			?>
    <div class="list_explore">
      <div class="online_num left">
        <label> <span class="result_total"><?php echo number_format($total_result); ?></span> <?php echo Lang::t('general', 'members are matching &1your requirements&2', array('&1' => '<a href="javascript:void(0);"><ins>', '&2' => '</ins></a>')); ?> </label>
        <a href="javascript:void(0);"><ins class="icon_location"></ins><?php echo Lang::t('search', 'Find your criteria')?></a> </div>
      <?php $this->widget('frontend.widgets.popup.Findhim', array()); ?>
      <div class="clear"></div>

        <div class="list_user left wrap_scale_box suggest-user-settings">
          <ul>
            <?php
				    if($total_result):
				    $params = CParams::load ();
				    $img_webroot_url	=	$params->params->img_webroot_url;
				    foreach ($search_data as $key => $item) :
				    	$item	=	$item['_source'];
				    	
				        $url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
				        if($item['have_avatar']){
				        	$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
				        }else{
				        	$avatar	=	$item['avatar'];
				        }
				        $location_display	=	'';
				        $state_name   =   !empty($item['state_name'])  ?  $item['state_name']    :   '' ; 
				        $country_name   =   !empty($item['country_name'])   ?   $item['country_name']    :   '';
				        if(!empty($country_name)){
				        	if(!empty($state_name)){
				            	$location_display	=	"$state_name, $country_name";
				            }else{
				            	$location_display	=	$country_name;
				            }
				        }
				        $sexualityLabel	=	ProfileSettingsConst::getSexualityLabel();
				        ?>
				        <li>
				        	<div class="wrap-img">
				            	<a target="_blank" href="<?php echo $url; ?>"><img class="lazy" data-original="<?php echo $avatar; ?>" alt="<?php echo $item['username']; ?>" border="0" align="absmiddle" onerror="$(this).attr('src','/public/images/no-user.jpg');" /></a>
				                <div class="info">
			                    	<a href="<?php echo $url; ?>" target="_blank">
			                        	<p><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $item['birthday_year']; ?></p>
			                        	<?php if(isset($sexualityLabel[$item['sexuality']])): ?>
			                            	<p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$item['sexuality']]; ?></p>
			                            <?php endif; ?>
			                            <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $item['sex_role_name']; ?></p>
			                            <div class="map"><ins></ins> <?php echo $location_display; ?></div>
			                        </a>	             
				                </div>
				            </div>
                            <div class="status_user_onoff">
                                <?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
                                    <p><label class="online"></label><a href="<?php echo $url; ?>" target="_blank"><?php echo $item['username']; ?></a></p>
                                <?php }else{ ?>
                                    <p><label class="offline"></label><a href="<?php echo $url; ?>" target="_blank"><?php echo $item['username']; ?></a></p>
                                <?php } ?>
                                <span>|</span>
                                <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
                                    <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $item['username']; ?>"></a>
                                <?php endif; ?>
                            </div>

			                <div class="icons_status">
			                    <div class="icon_each">
			                    	<?php if(in_array($item['user_id'], $my_friendlist)): ?>
			                            <div class="has_friend"></div>
			                        <?php endif; ?>
			                    </div>
			                </div>
			                <?php 
			                	if(isset($item['is_vip'])):
			                		if($item['is_vip']):
			                ?>
			                			<div class="icon_vip"></div>
			                		<?php endif; ?>
			                 <?php endif; ?>
			                	
				       </li>
            <?php 
				      	endforeach; 
				      	endif;
				      ?>
          </ul>
          <?php if($show_more): ?>
          <div class="pagging"> <a href="javascript:void(0);" onClick="showmore_searchresult();"><ins></ins></a> </div>
          <?php endif; ?>
        </div>

      <!-- members list --> 
    </div>
    <input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="<?php echo $offset ?>" />
    <input type="hidden" name="showmore_type" id="showmore_type" value="quicksearch" />
    <input type="hidden" name="quicksearch_keyword" id="quicksearch_keyword" value="<?php echo $keyword; ?>" />
    <!-- members area --> 
  </div>
  <span id="dvScroll_CurValue" style="display:none;"></span>
  <?php } ?>
</div>
<script type="text/javascript">
    jQuery('document').ready(function(){
    	NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);
		<?php if(Yii::app()->user->getFlash('forgotChangPasss')){?>
	    	Util.popAlertSuccess('<?php echo Lang::t('forgot', 'Your password has been changed!')?>', 400);
			setTimeout(function () {
				window.location = '<?php echo Yii::app()->homeUrl;?>';
			}, 3000);
		<?php }?>
    });
</script>
