<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/limitCounter.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js');

$looing_foronline	=	ProfileSettingsConst::getLookingForOnlineLabel();
$online_lookingfor_id	=	$usercurrent->profile->online_lookingfor;
$looking_online_status	=	isset($looing_foronline[$online_lookingfor_id])		?	$looing_foronline[$online_lookingfor_id] . ' | '	:	'';
$looking_online_select	=	isset($looing_foronline[$online_lookingfor_id])		?	$looing_foronline[$online_lookingfor_id]	:	Lang::t('newsfeed', 'Anything');


$current_location	=	array();
if($district_id){
	$district =	$district_in_cache->getDistrictInfo($district_id);
	$current_location[]	=	$district['name'];
}
if($city_id){
	$city =	$city_in_cache->getCityInfo($city_id);
	$current_location[]	=	$city['name'];
}
if($state_id){
	$state =	$state_in_cache->getStateInfo($state_id);
	$current_location[]	=	$state['name'];
}
if($country_id){
	$country =	$country_in_cache->getCountryInfo($country_id);
	$current_location[]	=	$country['name'];
}

//general current location
if(!empty($current_location)){
	$current_location	=	implode(', ', $current_location);
}else{
	$current_location	=	'';
}

?>
<div class="col-nav">
	<div class="user">
		<div href="#" title="" class="photo">
			<div class="photo-wrap">
				<img src="<?php echo $this->controller->usercurrent->getAvatar(); ?>?t=<?php echo time();?>" width="150" alt="" border="" class="imgAvatar"/>
					<a href="javascript:void(0);" class="btn-edit"><?php echo Lang::t('general', 'Edit Avatar')?></a>
					<input type="file" id="file" style="display: none;">
					<script  type="text/javascript">
					$('a.btn-edit').click(function() {
					    $('input[id=file]').trigger('click');
					});
					jQuery('document').ready(function(){
				        var input = document.getElementById("file");
				        var formdata = false;
				        if (window.FormData) {
				            formdata = new FormData();
				        }
				        input.addEventListener("change", function (evt) {
				            var i = 0, len = this.files.length, img, reader, file;
				            for ( ; i < len; i++ ) {
				                file = this.files[i];
				                if (!!file.type.match(/image.*/)) {
				                    if ( window.FileReader ) {
				                        reader = new FileReader();
				                        reader.onloadend = function (e) { 
// 				                        	$('.imgAvatar').attr('src', e.target.result)
				                        };
				                        reader.readAsDataURL(file);
				                    }
				                    if (formdata) {
				                        formdata.append("image", file);
				                        jQuery('body').loading();
				                        
				                        jQuery.ajax({
				                            url: "<?php echo $this->controller->usercurrent->createUrl('//my/UploadAvatar', array())?>",
				                            type: "POST",
				                            data: formdata,
				                            processData: false,
				                            contentType: false,
				                            success: function (res) {
				                            	$( ".popup-alert.cropAvatar .frame_content .crop-avatar .frame" ).html(res);
				                            	$( ".popup-alert.cropAvatar" ).pdialog({
						            				title: '<?php echo Lang::t('register', 'Set Avatar')?>',
						            				width: 472,
						            			});
				                            	jQuery('body').unloading();
				                            }
				                        });
				                    }
				                }
				                else
				                {
				                    alert('Not a vaild image!');
				                }   
				            }
			
				        }, false);
			
				    });
					</script>
			</div>
		</div>
		<?php 
		$content =  $this->render('upload-avatar-popup', array(), true);
		$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'cropAvatar', 'content'=>$content));
		?>
		<!-- user photo -->
		<div class="info">
		 <h2 class="name"><a href="<?php echo $this->controller->usercurrent->getUserUrl();?>"><?php echo $this->controller->usercurrent->getDisplayName();?></a></h2>
		 <div class="user_location">
            <p class="current_location">
            	<?php if($profile_location->published){ ?>
            	<a href="javascript:void(0);" onclick="$('.pop-checkin').toggle();">
            		<?php echo $looking_online_status; ?>
            		<?php echo $current_location; ?>
            	</a>
             	<a class="edit" title="Edit" onclick="$('.pop-checkin').toggle();" href="javascript:void(0);"></a>
            	<?php }else{ ?>
            	<a href="javascript:void(0);" onclick="$('.pop-checkin').toggle();">
            		<?php echo Lang::t('newsfeed', 'You haven\'t set your current location and browsing status'); ?>
            	</a>
            	<a class="edit" title="Edit" onclick="$('.pop-checkin').toggle();" href="javascript:void(0);"></a>
            	<?php } ?>
            </p>
			<div class="pop-checkin pop" style="display:none;">
                <span class="arrow"></span>
                <a class="btn-close" href="javascript:void(0);" title="Close">X</a>
                <div class="popcont">
                    <div class="left w150">
                    	<p class="title"><?php echo Lang::t('settings', 'Check in'); ?></p>
                        <div style="display:block;" class="edit_location check-in">
                            <div class="select_style check-in-country">
                            	<?php 
								    $list_country = CHtml::listData($list_country, 'id', 'name');
								    $top_country	=	SysCountry::model()->getCountryTop();
								    $top_country	= CHtml::listData($top_country, 'id', 'name');
								    $list_country_group	=	array(' ' => $top_country,'------------' => $list_country);
								    echo CHtml::dropDownList('country_id',$country_id, $list_country_group, array('name' => 'txt-country', 'id' => 'txt-country', 'onchange' => "getStateCheckIn();",'class' => 'select-type-1 ci-country'));                
                                ?>
                                <span class="txt_select"><span><?php echo $country['name']; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            <?php if(sizeof($list_state)){ ?>
                            <div class="select_style check-in-state">
								<?php
									$list_state = CHtml::listData($list_state, 'id', 'name');
	
                            		if($country_id){
										$top_state	=	LocationState::model()->getTopStateByCountry($country_id);
									    $top_state	=	CHtml::listData($top_state, 'id', 'name');
									    if(sizeof($top_state)){
											$list_state_group	=	array(' ' => $top_state, '------------' => $list_state);
										}else{
											$list_state_group	=	$list_state;
										}
									}else{
										$list_state_group	=	$list_state;
									}																
									echo CHtml::dropDownList('state_id',$state_id, $list_state_group, array('name' => 'txt-state', 'id' => 'txt-state', 'onchange' => "getCityCheckIn();",'class' => 'select-type-1 ci-state', 'empty' => Lang::t('search', '--Any--'))); 
								?>  
                                <span class="txt_select"><span><?php echo (isset($state['name'])) ? $state['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            <?php }else{ ?>
                            	<div class="select_style check-in-state" style="display: none;"></div>
                            <?php } ?>
                            
                            <?php 
                               //list city
                               if(sizeof($list_city)){
								$list_city = CHtml::listData($list_city, 'id', 'name');
							?>
                            <div class="select_style check-in-city">
                            	<?php
                            		echo CHtml::dropDownList('city_id',$city_id, $list_city, array('name' => 'txt-city', 'id' => 'txt-city', 'onchange' => "getDistrictCheckIn();",'class' => 'select-type-1 ci-city', 'empty' => Lang::t('search', '--Any--'))); 
                            	?>
                                <span class="txt_select"><span><?php echo (isset($city['name'])) ? $city['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            <?php }else{ ?> 
                            	<div class="select_style check-in-city" style="display: none;"></div>
                            <?php } ?>
                            
                            
                            <?php 
                            	//list district
                            	if(sizeof($list_district)){ 
								    $list_district = CHtml::listData($list_district, 'id', 'name');
                            ?>
                            <div class="select_style check-in-district">
                            	<?php 
                            		echo CHtml::dropDownList('district_id',$district_id, $list_district, array('name' => 'txt-district', 'id' => 'txt-district', 'class' => 'select-type-1 ci-district', 'empty' => Lang::t('search', '--Any--'))); 
                            	?>
                                <span class="txt_select"><span><?php echo (isset($district['name'])) ? $district['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
                            </div> 
                            <?php }else{?>      
                            	<div class="select_style check-in-district" style="display: none;"></div>
                            <?php } ?>     
                            <!-- 
         					<div class="select_style w160">
								<select class="select-type-1 fh-venue virtual_form" id="findhim-venue" name="findhim-venue" text="venues_findhim_text">
									<option value=""><?php echo Lang::t('search', 'All venues'); ?></option>
								</select>  
								<span class="txt_select"><span class="venues_findhim_text"><?php echo Lang::t('search', 'All venues'); ?></span></span> <span class="btn_combo_down"></span>
							</div>      
							 -->                                   
                        </div>
                    </div>
                    <div class="right w150">
                    	<p class="title"><?php echo Lang::t('settings', 'Looking for')?></p>
                        <div class="user_looking">
                            <div class="select_style check-in-lookingfor">
                             	<?php 
                                	echo CHtml::dropDownList('online_lookingfor', $usercurrent->profile->online_lookingfor, ProfileSettingsConst::getLookingForOnlineLabel(), array('empty' => Lang::t('general', 'All'))); 
                                ?>
                                <span class="txt_select"><span><?php echo $looking_online_select; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                          </div>
                    </div>
                    <div class="block-btn">
                        <a class="btn btn-violet" href="javascript:void(0);" title="" onclick="save_my_checkin();"><?php echo Lang::t('general', 'Save')?></a>
                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="$('.pop-checkin').toggle();"><?php echo Lang::t('general', 'Cancel')?></a> 
                    </div>
                </div>
            </div>                
        </div>     		
			<div class="buttons">
				<button class="btn btn-violet btn-navpost"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Post Status')?></span></button>
				<div class="pop-status">
					<div class="border">
						<div class="wrap">
							<span class="arrow"></span>
							<a href="#" class="btn-close"></a>
							<?php $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'wall-status-form',
								'action' => $this->controller->usercurrent->createUrl('//newsFeed/postWall'),
							)); ?>
								<div class="input-wrap">
									<?php echo $form->textArea(new WallStatus(),'status', array('class' => 'status', 'placeholder' => Lang::t('newsfeed', 'Type your status').'...', 'rows' => 6, 'cols' => 43)); ?>
								</div>
								<div class="input-foot">
									<span class="chars">
										<?php echo Lang::t('general', 'You have {count} characters remaining', array('{count}'=>'<strong id="chars">500</strong>'))?>
									</span>
									<div class="btn-wrap">
									<?php 
									$needle = Yii::app()->urlManager->parseUrl(Yii::app()->request);
									$haystack = array('newsFeed/feed', 'my/view', '');
									?>
										<button class="btn" data="<?php echo (in_array($needle, $haystack)) ? '' : $this->controller->usercurrent->getUserFeedUrl(true)?>"><?php echo Lang::t('newsfeed', 'Post')?></button>
									</div>
									<div class="btn-wrap-cancel">
                                          <button class="btn" type="reset"><?php echo Lang::t('newsfeed', 'Cancel')?></button>
                                     </div>
								</div>
								<script  type="text/javascript">
								    $(document).ready(function() {
								        $(".status").limit({
								            limit: 500,
								            id_result: "chars"
								            });
								         });
								</script>
							<?php $this->endWidget(); ?>
						</div>
					</div>
				</div>
				<!-- Status post -->
			</div>
			<?php 
			$controller = Yii::app()->controller;
			$cr2 = new CDbCriteria();
		    $cr2->addCondition("((from_user_id = :from AND message_read = 0) OR (to_user_id = :to AND message_read = 2)) AND status = 1 AND answered = 0");
		    $cr2->params = array(':from'=>$this->controller->usercurrent->id, ':to'=>$this->controller->usercurrent->id);
		    $totalMSG = Message::model()->count($cr2);
		    $style = 'style="display: none;"';
		    if(!empty($totalMSG) && $totalMSG > 0){
				$style = '';
			}
			?>
			<div class="menu">
				<ul>	
				    <li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-message" title=""></i><span class="text">'.Lang::t('general', 'Message').'</span><span class="count" '.$style.'>'.$totalMSG.'</span>', 
						$this->controller->usercurrent->createUrl('//messages/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'messages') ? 'nav-msg active' : 'nav-msg'
						));
						?>
					</li>
					<li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-alert" title=""></i><span class="text">'.Lang::t('general', 'Alerts').'</span><span class="count" style="display: none;">0</span>', 
						$this->controller->usercurrent->createUrl('//alerts/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'alerts') ? 'nav-alert active' : 'nav-alert'
						));
						?>
					</li>
					<li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-friend" title=""></i><span class="text">'.Lang::t('general', 'Friends').'</span><span class="count" style="display: none;">0</span>', 
						$this->controller->usercurrent->createUrl('//friend/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'friend') ? 'nav-friend active' : 'nav-friend'
						));
						?>
					</li>				
					<li>
						<span class="line"></span>
						<?php 
						
						$photo_alert = SysPhotoRequest::getPhotoRequestUnreadCount($this->controller->usercurrent->id);
						$photo_alert = ($photo_alert > 0) ?  '<span id="photo-alert" class="count">'.$photo_alert.'</span>' :'<span id="photo-alert" class="count" style="display: none;">0</span>';
						echo CHtml::link('<i class="imed imed-photo" title=""></i><span class="text">'.Lang::t('general', 'Photo').'</span>'.$photo_alert, 
						$this->controller->usercurrent->createUrl('//photo/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'photo') ? 'nav-photo active' : 'nav-photo'
						));
						?>
					</li>
					<li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-candy" title=""></i><span class="text">'.Lang::t('general', 'Candy').'</span>', 
						'javascript:void(0);', 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'friend') ? 'coming-soon' : 'coming-soon'
						));
						?>
					</li>
					<li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-bookmark" title=""></i><span class="text">'.Lang::t('general', 'Bookmark').'</span>', 
						$this->controller->usercurrent->createUrl('//bookmark/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'bookmark') ? 'nav-bookmark active' : 'nav-bookmark'
						));
						?>
					</li>				
					<li>
						<span class="line"></span>
						<?php 
						echo CHtml::link('<i class="imed imed-setting" title=""></i><span class="text">'.Lang::t('general', 'Setting').'</span>', 
						$this->controller->usercurrent->createUrl('//settings/index'), 
						array(
							'class' => (!empty($controller->id) && strtolower($controller->id) == 'settings') ? 'active' : ''
						));
						?>
					</li>
				</ul>
			</div>
			<!-- user menu -->
		</div>
	</div>
	<!-- user -->
</div>

