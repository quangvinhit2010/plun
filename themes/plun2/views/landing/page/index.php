<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/landing.js');
Yii::app()->clientScript->registerScript('Landing',"
        $(document).ready(function(){
            objCommon.lazyLoadCommon('.feed .content ul li .avatar_feed a img.lazy');
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
    <div class="wrap-feed left">
        <div class="shadow_top"></div>
        <div class="feed sticky_column">
            <div class="title">
                            <h3 class="left"><?php echo Lang::t('newsfeed', 'News Feed'); ?></h3>
                            <a class="post_link right" href="javascript:void(0);"><?php echo Lang::t('newsfeed', 'Post Status'); ?></a>
                            <div id="wall-status-form">                            
                            	<div class="post_status" style="display: none;">                            
	                                <div class="icon_arrow_popup_status"></div>
	                            	<div class="input-wrap">                            		
	                            		<textarea class="status" placeholder="<?php echo Lang::t('newsfeed', 'Type your status');?>..." rows="6" cols="43" name="WallStatus[status]" id="WallStatus_status"></textarea>
	                                </div>
	                                <div class="input-foot">
										<span class="chars">
											<?php echo Lang::t('general', 'You have {count} characters remaining', array('{count}'=>'<strong id="chars">500</strong>'))?>
										</span>
										<div class="btn-wrap-cancel">
	                                        <button type="reset" class="btn"><?php echo Lang::t('newsfeed', 'Cancel')?></button>
	                                     </div>
	                                    <div class="btn-wrap">
											<button data="" class="btn"><?php echo Lang::t('newsfeed', 'Post')?></button>
										</div>									
									</div>
	                            </div>
                            </div>
            </div>        	
        	<div class="content">
					<ul><?php echo $this->renderPartial("//landing/partial/feed", array());?></ul>
                	<div style="display: none;" class="feedLasted" data-url="" data-time=""></div>    
            	    <!-- news feed list -->
                    <input type="hidden" value="0" name="newsfeed_offset" id="newsfeed_offset" />
                    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
                    <?php if($show_more): ?>
            		<div class="pagging">
                    	<a style="display: none;" class="showmore" href="javascript:void(0);"><ins></ins></a>
                    </div>
                    <?php endif; ?>
        	</div>
        	<div class="clear"></div>
        </div>
    </div>
    <div class="explore left explore_page">
        <div class="clearfix sticky_column wrap_myexplore">
            <?php 
			$cs = Yii::app()->clientScript;
			$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/search.js', CClientScript::POS_BEGIN);
			$get = Yii::app()->cache->get('viewProfiles_'.Yii::app()->user->id);
			$arrProfiles = array();
			$total = 0;
			if(!empty($get)){
			    $arrProfiles = json_decode($get);
			    $total = count($arrProfiles);
			}
			//build current area
			$current_area	=	array();
			if($current_district_name){
				array_push($current_area, $current_district_name);
			}
			if($current_city_name){
				array_push($current_area, $current_city_name);
			}
			if($current_state_name){
				array_push($current_area, $current_state_name);
			}
			if($current_country_name){
				array_push($current_area, $current_country_name);
			}
			?>
			<div class="list_explore">
			    <div class="shadow_top"></div>
				<div class="online_num left">
						<label>
							<span class="result_total"><?php echo number_format($total_result); ?></span> 
							<?php echo Lang::t('general', 'members in &1 &2 &3', array('&1' => '<a href="javascript:void(0);"><ins>', '&2' => implode(', ', $current_area), '&3' => '</ins></a>')); ?>		
						</label>
				</div> 
			    
			    <div class="clear"></div>
			
				    <div class="list_user left wrap_scale_box suggest-user-settings">
			        <ul>
				 	<!-- search -->      
				 
				 	<?php
				    if($total_result):
				    $params = CParams::load ();
				    $img_webroot_url	=	$params->params->img_webroot_url;
				    foreach ($data as $key => $item) :
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
				            	<a href="javascript:void(0);"><img class="lazy" data-original="<?php echo $avatar; ?>" alt="<?php echo $item['username']; ?>" border="0" align="absmiddle" onerror="$(this).attr('src','/public/images/no-user.jpg');" /></a>
				                <div class="info">
			                    	<a href="javascript:void(0);">
			                        	<p><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $item['birthday_year']; ?></p>
			                        	<?php if(isset($sexualityLabel[$item['sexuality']])): ?>
			                            	<p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$item['sexuality']]; ?></p>
			                            <?php endif; ?>
			                            <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $item['sex_role_name']; ?></p>
			                            <div class="map"><ins></ins> <?php echo $location_display; ?></div>
			                        </a>	             
				                </div>
				            </div>    
				            <?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
				            	<p><label class="online"></label><a href="javascript:void(0);"><?php echo $item['username']; ?></a></p>
				            <?php }else{ ?>
				            	<p><label class="offline"></label><a href="javascript:void(0);"><?php echo $item['username']; ?></a></p>
				            <?php } ?>
			                <div class="icons_status">
			                    <div class="icon_each">
			                    	<?php if(!empty(CParams::load()->params->anonymousChat)): ?>
			                        <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $item['username']; ?>"></a>
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
				      
				 	  <!-- end search -->        
				      </ul>
				            <?php if($show_more): ?>
				            <div class="pagging">
			                	<a href="javascript:void(0);"><ins></ins></a>
			                </div>
				            <?php endif; ?>
				    </div>
			
			    <!-- members list -->
			</div>
			<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="<?php echo $offset ?>" />
			<input type="hidden" name="showmore_type" id="showmore_type" value="usersetting" />
            <?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
        </div>
        <!-- members area -->
    </div>
</div>
<div id="popupRegisLogin" class="popup_genneral">
    <div class="title"><?php echo Lang::t('alerts', 'Alerts');?></div>
    <div class="content">
        <p><?php echo Lang::t('login', 'Please sign-in to use this feature!')?></p>
        <div class="center">
            <a href="<?php echo Yii::app()->createUrl('//site/login')?>" class="btnGray"><?php echo Lang::t('login', 'Sign in')?></a>
            <a href="<?php echo Yii::app()->createUrl('//register')?>" class="btnTim"><?php echo Lang::t('register', 'Sign Up');?></a>
        </div>
    </div>
</div>
