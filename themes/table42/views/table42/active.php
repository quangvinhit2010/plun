  <?php 
  $params = CParams::load ();
  $userCurrent =  Yii::app()->user->data();
  ?>
  <!-- InstanceBeginEditable name="EditRegion2" -->
    <div class="table42 clearfix"> 
    	<div class="people">
            <div class="content">
                <div class="bg_person"></div>
                <div class="bg_car"></div>
                <div class="wrap_sologan">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/title_henho.png" align="absmiddle" />
                    <div class="txt_intro">
                        <p><?php echo $currentround->description; ?></p>
                        <p class="time">Thời gian <?php echo date('d/m/Y', $currentround->time_start); ?> - <?php echo date('d/m/Y', $currentround->time_end); ?></p>
                         <?php if(!$signup): ?>	
	                         <?php if(Yii::app()->user->isGuest){ ?>
	                        	<p><a class="bg_btn btn_join" href="javascript:void(0);" title="Tham gia ngay" data-effect-id="popup_login"><span class="bg_btn">tham gia ngay</span></a></p>
	                        <?php }else{ ?>
									
									<?php if($profile){ ?>
								
							
			                        	<?php if($profile->step == Table42Profile::STEP_SIGNUP){ ?>
			                        		<p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_xacnhan', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
			                        	<?php } ?>
			                        	
				                        <?php if($profile->step == Table42Profile::STEP_CONFIRM){ ?>
				                        	<p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_upload', 308, 176);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                        <?php } ?>
		                        	
			                        	<?php if($profile->step == 0){ ?>
				                        	<?php if($sex_role === 1){?>
				                            <p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>
				                            
				                        	<?php if($sex_role === 0){?>
				                            <p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia_sexrole', 308, 239);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>	                            
			
				                           	<?php if($sex_role === 2){?>
				                            <p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_sexrole_error', 308, 90);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>
				                        <?php } ?> 
			                        
			                        <?php }else{ ?>
			                        		<p><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
			                        <?php } ?>
			                        
	                            <?php } ?>
	                            
                        <?php endif;?>
                    </div>
                </div>
                <?php if(Yii::app()->user->isGuest){ ?>
                    <div class="form_login icon_table42">
                        <a href="javascript:void(0);" class="login" title="Login" data-effect-id="popup_login">Đăng nhập</a>
                        <a href="javascript:void(0);" class="register" title="Register" data-effect-id="popup_signup">Đăng ký</a>
                    </div>
                <?php } ?>

                <div class="menu_list">
                    <div class="wrap_menu_42">
                        <ul>
                            <li class="index"><a href="#" title="Trang chủ" class="icon_table42 btnEffectBg"></a></li>
                            <li class="danhsach"><a href="<?php echo Yii::app()->createUrl('/table42/listmember');?>" title="Danh sách" class="icon_table42 btnEffectBg"></a></li>
                            <?php if($signup_ok): ?>
                                <li class="lmkb"><a href="<?php echo Yii::app()->createUrl('/table42/listrequest');?>" title="Lời mời kết bạn" class="icon_table42 btnEffectBg"></a></li>
                                <li class="dykd"><a href="<?php echo Yii::app()->createUrl('/table42/listagree');?>" title="Đồng ý kết đôi" class="icon_table42 btnEffectBg"></a></li>
                            <?php endif; ?>
                            <li class="couple"><a href="<?php echo Yii::app()->createUrl('/table42/listcouple');?>" title="Couple" class="icon_table42 btnEffectBg"></a></li>
                            <li class="result"><a href="#" title="Kết quả" class="icon_table42 btnEffectBg"></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>
   
	<?php $this->renderPartial("//table42/partial/login", array('model' => $model, 'params'=> $params)) ;?>
	<?php $this->renderPartial("//table42/partial/register", array());?>
	
	<?php if(!Yii::app()->user->isGuest): ?>
		<?php $this->renderPartial("//table42/partial/upload", array());?>
		<div id="popup_updatePublic" class="popup_general" title="XÁC NHẬN THÔNG TIN" style="display:none;"></div>
		<?php $this->renderPartial("//table42/partial/upload_photo", array());?>
			<?php if($sex_role === 1):?>
				<?php $this->renderPartial("//table42/partial/signup", array());?>	                            
			<?php endif; ?>
	       <?php if($sex_role === 0):?>
				<?php $this->renderPartial("//table42/partial/signup_choose_sexrole", array());?>
	        <?php endif; ?>	                            
		<?php $this->renderPartial("//table42/partial/signup_message", array());?>		
		<?php $this->renderPartial("//table42/partial/signup_choose_sexrole", array());?>
		<?php $this->renderPartial("//table42/partial/signup_sexrole_message", array());?>
	<?php endif; ?>
	
	<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'PopupAlert')); ?>
	
	<script>
		 $(function() {
			
			 $('.form_login a.login').click(function(){
				objTable42.showEffectPopup({
					width: 308,
					height: 253,
					effectBasic: true
				}, this);
			  });
			  
			  $('.form_login a.register').click(function(){
				objTable42.showEffectPopup({
					width: 561,
					height: 371	,
					effectBasic: true
				}, this);
			  });
			  
			  $('.people .content p a.btn_join').click(function(){
				objTable42.showEffectPopup({
					width: 308,
					height: 253	,
                    popupDefault: true
				}, this);
			  });
			  
			  $(document).on('click','#popup_login .content ul li a.link_regist',function(){
					objTable42.showEffectPopup({//popup register
						width: 561,
						height: 371,
						popupMulti: true
					}, this);
					
					return false;  
			  });
			  
			  $(document).on('click','#popup_signup .content ul li a.link_login',function(){
					objTable42.showEffectPopup({//popup login
						width: 308,
						height: 253,
						popupMulti: true
					}, this);
					
					return false;   
			  });
				<?php if($upload): ?>
				Tablefortwo.openPopup('popup_upload', 308, 176);
				<?php endif; ?>			  
		});
	</script>