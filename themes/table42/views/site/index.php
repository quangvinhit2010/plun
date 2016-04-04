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
	                        	<p class="join_now_botton"><a class="bg_btn btn_join" href="javascript:void(0);" title="Tham gia ngay" data-effect-id="popup_login"><span class="bg_btn">tham gia ngay</span></a></p>
	                        <?php }else{ ?>
									
									
									<?php if($profile){ ?>
										
										<?php if($resigup):?>	
											<p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
										<?php endif; ?>
							
			                        	<?php if($profile->step == Table42Profile::STEP_SIGNUP){ ?>
			                        		<p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_upload', 308, 176);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
			                        	<?php } ?>
		                        	
			                        	<?php if($profile->step == 0){ ?>
				                        	<?php if($sex_role === 1){?>
				                            <p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>
				                            
				                        	<?php if($sex_role === 0){?>
				                            <p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia_sexrole', 308, 239);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>	                            
			
				                           	<?php if($sex_role === 2){?>
				                            <p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_sexrole_error', 308, 90);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
				                            <?php } ?>
				                        <?php } ?> 
			                        
			                        <?php }else{ ?>
			                        		<p class="join_now_botton"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup('popup_thamgia', 308, 209);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a></p>
			                        <?php } ?>
			                        
	                            <?php } ?>
	                            
                        <?php endif;?>
                         <div class="clearfix">
                        	<a id="btn_popupHDTG" href="#" class="left">Hướng dẫn tham gia</a>
                            <a id="btn_popupTLGT" href="#" class="right">Thể lệ và giải thưởng</a>
                         </div>
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
                            <li class="index active"><a href="/" title="Trang chủ" class="icon_table42 btnEffectBg"></a></li>
                            <li class="danhsach"><a href="<?php echo Yii::app()->createUrl('/table42/listmember');?>" title="Danh sách" class="icon_table42 btnEffectBg"></a></li>
                            <?php if($signup_ok): ?>
                                <li class="lmkb"><a href="<?php echo Yii::app()->createUrl('/table42/listrequest');?>" title="Lời mời kết bạn" class="icon_table42 btnEffectBg"></a></li>
                                <li class="dykd"><a href="<?php echo Yii::app()->createUrl('/table42/listagree');?>" title="Đồng ý kết đôi" class="icon_table42 btnEffectBg"></a></li>
                            <?php endif; ?>
                            <li class="couple"><a href="<?php echo Yii::app()->createUrl('/table42/listcouple');?>" title="Couple" class="icon_table42 btnEffectBg"></a></li>
                            <li class="result"><a href="<?php echo Yii::app()->createUrl('/table42/result');?>" title="Kết quả" class="icon_table42 btnEffectBg"></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>
   <div id="popupTLGT" class="popup_general" title="Đăng nhập" style="display:none;">	    
	    <div class="content">
	    	<h1>Thể lệ và giải thưởng</h1>
	        <div class="wrapScroll">
	        	<p><strong>Giải thưởng:</strong></p>
	
					<p>Cặp kết đ&ocirc;i tham gia c&oacute; tỷ lệ b&igrave;nh chọn cao nhất sẽ được tham dự một bữa tối l&atilde;ng mạn tại một nh&agrave; h&agrave;ng cao cấp, được t&agrave;i trợ ho&agrave;n to&agrave;n bởi <strong>PLUN.ASIA.</strong></p>
					
					<p>Cặp kết đ&ocirc;i tham gia sẽ được xuất hiện tr&ecirc;n ấn phẩm Attitude th&aacute;ng đ&oacute; v&agrave; chia sẻ cảm nhận của m&igrave;nh về cuộc hẹn cũng như về đối phương tr&ecirc;n ấn phẩm <strong>Attitude</strong>, ấn phẩm quốc tế, định k&igrave; h&agrave;ng th&aacute;ng, về phong c&aacute;ch sống d&agrave;nh cho LGBT Việt.</p>
					
					<p><strong>Điều khoản đăng k&iacute; </strong></p>
					
					<ul>
						<li>Thời hạn đăng k&iacute; tham gia Table For Two v&agrave;o tuần đầu ti&ecirc;n của c&aacute;c th&aacute;ng, từ ng&agrave;y 1 đến ng&agrave;y 10.</li>
						<li>Ban tổ chức sẽ duyệt hồ sơ của người tham gia trong v&ograve;ng 2 ng&agrave;y (ng&agrave;y 11 v&agrave; ng&agrave;y 12)</li>
						<li>C&aacute;c bạn tham gia c&oacute; quyền tự kết đ&ocirc;i với nhau trong v&ograve;ng 3 ng&agrave;y tiếp theo (từ ng&agrave;y 13 đến ng&agrave;y 15)</li>
						<li>Sau đ&oacute; l&agrave; khoản thời gian b&igrave;nh chọn cho c&aacute;c cặp đ&ocirc;i y&ecirc;u th&iacute;ch cho đến hết th&aacute;ng. ( 15 ng&agrave;y b&igrave;nh chọn )</li>
					</ul>
					
					<p><strong>Điều khoản sử dụng:</strong></p>
					
					<ul>
						<li><strong>Table For Two</strong> l&agrave; chuy&ecirc;n mục định k&igrave; h&agrave;ng th&aacute;ng nhằm mang đến sự kh&aacute;c lạ, mới mẻ v&agrave; hấp dẫn cho những buổi hẹn h&ograve; của cặp đ&ocirc;i được b&igrave;nh chọn nhiều nhất mỗi th&aacute;ng.</li>
						<li>Bất k&igrave; th&ocirc;ng tin c&aacute; nh&acirc;n hoặc h&igrave;nh ảnh n&agrave;o gửi chậm trễ, qu&aacute; ng&agrave;y 10 h&agrave;ng th&aacute;ng sẽ được chuyển cho <strong>Table For Two</strong> của th&aacute;ng tiếp theo.</li>
						<li>Th&ocirc;ng tin v&agrave; h&igrave;nh ảnh phải hợp lệ, thuộc quyền sở hữu của ch&iacute;nh người tham gia chương tr&igrave;nh. Lưu &yacute;: 3 tấm h&igrave;nh đ&atilde; gửi cho chương tr&igrave;nh sẽ được sử dụng xuy&ecirc;n suốt v&agrave; kh&ocirc;ng được thay đổi.</li>
						<li>Mọi h&igrave;nh thức b&igrave;nh chọn Cặp Đ&ocirc;i đều được thực hiện một c&aacute;ch c&ocirc;ng khai.</li>
						<li>Quyết định của Ban Tổ Chức <strong>PLUN.ASIA</strong> l&agrave; quyết định cuối c&ugrave;ng.</li>
						<li>Khi bạn đ&atilde; được chọn nhưng đơn phương hủy cuộc hẹn, hoặc từ chối l&ecirc;n b&aacute;o sau khi đ&atilde; tham gia cuộc hẹn, bạn sẽ &nbsp;phải thanh to&aacute;n chi ph&iacute; cuộc hẹn cũng như những thiệt hại cho chương tr&igrave;nh theo mức bồi thường m&agrave; <strong>PLUN.ASIA</strong> đưa ra.</li>
					</ul>
					
					<p><strong>Điều lệ v&agrave; t&iacute;nh bảo mật:</strong></p>
					
					<p><strong>PLUN.ASIA </strong>c&oacute; quyền sử dụng th&ocirc;ng tin v&agrave; h&igrave;nh ảnh c&aacute; nh&acirc;n m&agrave; người tham gia đ&atilde; cung cấp cho c&aacute;c hoạt động của <strong>PLUN</strong>, cũng như <strong>Table For Two</strong> m&agrave; kh&ocirc;ng phải xin ph&eacute;p người tham gia.</p>
					
					        </div>
	    </div>
	</div>
	<div id="popupHDTG" class="popup_general" title="Đăng nhập" style="display:none;">	    
			    <div class="content">
			    	<h1>Hướng dẫn tham gia</h1>
			        <div class="wrapScroll">
			        	<p>Để&nbsp;qu&aacute; tr&igrave;nh đăng k&yacute; tham gia <strong>Table For Two</strong> dễ d&agrave;ng hơn vui l&ograve;ng đọc qua Hướng Dẫn tham gia:</p>
			
			<ol>
				<li><strong>Tham gia Table For Two cần t&agrave;i khoản PLUN kh&ocirc;ng ?</strong></li>
			</ol>
			
			<ul>
				<li>Tất nhi&ecirc;n sẽ cần một t&agrave;i khoản PLUN để tham gia Table For Two. Nếu bạn chưa c&oacute; t&agrave;i khoản vui l&ograve;ng đăng k&yacute; tại trang website Plun</li>
			</ul>
			
			<ol>
				<li><strong>Tại sao đăng k&yacute; tham gia Table For Two phải c&oacute; điện thoại hoặc url facebook ?</strong></li>
			</ol>
			
			<ul>
				<li>Số điện thoại hoặc url sẽ được ch&uacute;ng t&ocirc;i bảo mật nhằm t&iacute;nh ri&ecirc;ng tư trong qu&aacute; tr&igrave;nh bạn tham gia Table For Two. Chỉ khi cặp kết đội l&agrave; người chiến thắng PLUN sẽ li&ecirc;n hệ bạn để nhận thưởng.</li>
			</ul>
			
			<ol>
				<li><strong>Khi tham gia Table For Two cặp đội thắng cuộc c&oacute; được l&ecirc;n ấn phẩm Attitude kh&ocirc;ng ?</strong></li>
			</ol>
			
			<ul>
				<li>Theo giải thưởng th&igrave; những cặp đ&ocirc;i chiến thắng sẽ được l&ecirc;n b&aacute;o ấn phẩm Attitude theo từng số.</li>
			</ul>
			
			<p><em><strong>Vui l&ograve;ng đọc qua c&aacute;c bước hướng dẫn đăng k&yacute; tham gia.</strong></em></p>
			
			<p><strong>Bước 1:</strong> click v&agrave;o tham gia v&agrave; l&agrave;m theo hướng dẫn:</p>
			
			<p><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Table42_1.jpg" /></p>
			
			<p><strong>Bước 2:</strong> Nhập số điện thoại hoặc url Facebook ( sẽ được bảo mật, chỉ khi bạn l&agrave; người chiến thắng th&igrave; plun sử dụng để li&ecirc;n hệ với bạn )</p>
			
			<p><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Table42_3.jpg" /></p>
			
			<p><strong>Bước 3:</strong> Y&ecirc;u cầu khi tham gia Table For Two phải 3 tấm h&igrave;nh. Nếu hồ sơ tr&ecirc;n PLUN của bạn đ&atilde; c&oacute; h&igrave;nh PLUN cho ph&eacute;p tải ảnh từ h&igrave;nh c&ocirc;ng cộng, ngược lại bạn c&oacute; thể chọn tải ảnh từ m&aacute;y t&iacute;nh.</p>
			
			<p><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Table42_2.jpg" /></p>
			
			<p><strong>Bước 4:</strong> V&agrave;o Danh S&aacute;ch để t&igrave;m kiếm kết đội sau khi profile của bạn đ&atilde; được duyệt để tham gia.</p>
			
			<p><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Table42_4.jpg" /></p>
			
			<p style="margin-left: 40px;">V&igrave; đ&acirc;y l&agrave; game show hẹn h&ograve; để chọn ra 1 couple (cặp đ&ocirc;i) n&ecirc;n c&aacute;c bạn cần phải chọn phần vai tr&ograve; của m&igrave;nh Top/Bottom (lưu &yacute; l&agrave; chỉ c&oacute; thể chọn Top/Bottom)</p>
			
			<p><strong>Bước 5:</strong> Khi bạn mời kết đ&ocirc;i --&gt; Đồng &yacute; Kết Đ&ocirc;i --&gt; chấp nhận Kết Đ&ocirc;i để bắt đầu tham gia</p>
			
			<p><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/Table42_5.jpg" /></p>
			
			<p><strong>C&aacute;ch thức b&igrave;nh chọn</strong></p>
			
			<p>Sau khi tham gia c&aacute;c bạn sẽ c&oacute; thời gian để v&agrave;o danh s&aacute;ch Table For Two chọn đối tượng m&igrave;nh muốn gh&eacute;p đ&ocirc;i. Nếu đối tượng đồng &yacute; th&igrave; 2 bạn sẽ trở th&agrave;nh 1 couple (cặp đ&ocirc;i)</p>
			        </div>
			    </div>
	</div>
	<?php $this->renderPartial("//table42/partial/login", array('model' => $model, 'params'=> $params)) ;?>
	<?php $this->renderPartial("//table42/partial/register", array());?>
	
	<?php if(!Yii::app()->user->isGuest): ?>
		<?php $this->renderPartial("//table42/partial/upload", array());?>
		<div id="popup_updatePublic" class="popup_general" title="XÁC NHẬN THÔNG TIN" style="display:none;"></div>
		<?php $this->renderPartial("//table42/partial/upload_photo", array());?>
			<?php $this->renderPartial("//table42/partial/signup", array());?>
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
				objCommon.sprScroll('#popupHDTG .wrapScroll');
				$('#btn_popupHDTG').click(function(){
					objTable42.showEffectPopup({
						width: 625,
						height: 715,
						popupDefault: true
					},'popupHDTG');
					return false;
				});
				objCommon.sprScroll('#popupTLGT .wrapScroll');
				$('#btn_popupTLGT').click(function(){
					objTable42.showEffectPopup({
						width: 625,
						height: 715,
						popupDefault: true
					},'popupTLGT');
					return false;
				});
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