    <div class="table42 clearfix page_kq"> 
    	<div class="people">
            <div class="content">
                <div class="bg_person"></div>
                <div class="bg_car"></div>
                <div class="wrap_sologan">
                	<h1 class="title_page"><?php echo $result->title; ?></h1>
                    <span class="sologan_intro"></span>
                    <div class="txt_intro">
                        <div class="align_center couple_kq"><?php echo $result->description; ?></div>
                        <div class="wrap_list">
                        	<ul class="clearfix">	
                        		<?php foreach ($result->photo AS $row): ?>
                            	<li><?php echo $row->getImageThumb203x204(false, array('height' => '64px', 'width' => '64px')); ?></li>
								<?php endforeach; ?>
                            </ul>	
                        </div>
                        <p class="align_center view_detail_kq"><a href="#">Xem chi tiết</a></p>
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
                        <li class="index"><a href="/" title="Trang chủ" class="icon_table42 btnEffectBg"></a></li>
                        <li class="danhsach"><a href="<?php echo Yii::app()->createUrl('/table42/listmember');?>" title="Danh sách" class="icon_table42 btnEffectBg"></a></li>

                        <?php if($signup_ok): ?>
                            <li class="lmkb active"><a href="<?php echo Yii::app()->createUrl('/table42/listrequest');?>" title="Lời mời kết bạn" class="icon_table42 btnEffectBg"></a></li>
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

    <script type="text/javascript">
		$(document).ready(function(){
			$('.view_detail_kq a').click(function(){
				objTable42.showEffectPopup({
					width: 735,
					minHeight: 647,
					popupDefault: true,
					scrollContent: '.infro_ketdoi'
				}, 'popup_ketqua');
				$('#popup_ketqua .wrap_slideshow .bxslider').bxSlider({
					slideWidth: 150,
					minSlides: 4,
					maxSlides: 4,
					pager: false
				});		
			});
			$('.list_slide_pic ul li .wrap_img').colorbox({
					rel: 'wrap_img',
					scrolling: false,
					fixed: true,
					innerHeight: true,
					scalePhotos: true,
					maxWidth: '100%',
					maxHeight: '95%'
				});
		});
</script>
    <!-- InstanceBeginEditable name="EditRegion5" -->
<div id="popup_ketqua" class="popup_general" title="XÁC NHẬN THÔNG TIN" style="display:none;">	    
    <div class="content">
    	<div class="list_slide_pic">
        	<div class="wrap_slideshow clearfix">
	        	<ul class="bxslider">
	        	    <?php foreach ($result->photo AS $row): ?>
	               		<li><a href="<?php echo $row->getImageThumb768x1024(true); ?>" class="wrap_img"><?php echo $row->getImageThumb203x204(false, array('height' => '140px', 'width' => '140px')); ?></a></li>
					<?php endforeach; ?>
	            </ul>
            </div>
        </div>
        <p class="align_center"><span class="btn_gray">Lượt bình chọn: <?php echo $request->vote_total; ?></span></p>
        <div class="infro_ketdoi">
            <div class="wrap_scroll_content clearfix">
                <div class="infor_user">
        	<h2><?php echo $friend_profile->username; ?></h2>
            <p><b>Từ: </b><?php echo $friend_profile_full['location']; ?></p>
            <h4>THÔNG TIN CƠ BẢN</h4>
            <?php if(isset($friend_profile_full['ethnicity'])): ?>
           	<p><b>Sắc Tộc: </b><?php echo $friend_profile_full['ethnicity']; ?>  </p>
            <?php endif; ?>
            
            <p><b>Tuổi:</b><?php echo $friend_profile_full['birthday_year']; ?></p>
            
            <?php if(isset($friend_profile_full['sexuality'])): ?>
            	<p><b>Giới tính: </b><?php echo $friend_profile_full['sexuality']; ?>  </p>
            <?php endif; ?>
            
            <?php if(isset($friend_profile_full['role'])): ?>
            <p><b>Vai Trò:</b><?php echo $friend_profile_full['role']; ?> </p>
            <?php endif; ?>
            
            <?php if(isset($friend_profile_full['relationship'])): ?>
            	<p><b>Tình trạng Quan hệ: </b><?php echo $friend_profile_full['relationship']; ?></p>
            <?php endif; ?>
            
            <?php if(isset($friend_profile_full['looking_for'])): ?>
            	<p><b>Tìm Kiếm: </b><?php echo $friend_profile_full['looking_for']; ?></p>
            <?php endif; ?>
            
            <p><b>Ngôn Ngữ Sử Dụng: </b><?php echo $friend_profile_full['languages']; ?></p>
            
            <h4>NHỮNG GÌ BẠN THẤY</h4>
            
            <p><b>Chiều Cao:</b><?php echo $friend_profile_full['height']; ?></p>
            <p><b>Cân Nặng:</b><?php echo $friend_profile_full['weight']; ?> </p>
            
            <p><b>Hình Thể: </b><?php echo $friend_profile_full['build']; ?></p>
            <p><b>Lông Cơ Thể: </b><?php echo $friend_profile_full['body_hair']; ?></p>
            <p><b>Hình Xăm:</b><?php echo $friend_profile_full['tattoo']; ?></p>
            <p><b>Khuyên: </b><?php echo $friend_profile_full['piercing']; ?></p>
        </div>
                <div class="infor_user">
        	<h2><?php echo $inviter_profile->username; ?></h2>
            <p><b>Từ: </b><?php echo $inviter_profile_full['location']; ?></p>
            <h4>THÔNG TIN CƠ BẢN</h4>
            <?php if(isset($inviter_profile_full['ethnicity'])): ?>
           	<p><b>Sắc Tộc: </b><?php echo $inviter_profile_full['ethnicity']; ?>  </p>
            <?php endif; ?>
            
            <p><b>Tuổi:</b><?php echo $inviter_profile_full['birthday_year']; ?></p>
            
            <?php if(isset($inviter_profile_full['sexuality'])): ?>
            	<p><b>Giới tính: </b><?php echo $inviter_profile_full['sexuality']; ?>  </p>
            <?php endif; ?>
            
            <?php if(isset($inviter_profile_full['role'])): ?>
            <p><b>Vai Trò:</b><?php echo $inviter_profile_full['role']; ?> </p>
            <?php endif; ?>
            
            <?php if(isset($inviter_profile_full['relationship'])): ?>
            	<p><b>Tình trạng Quan hệ: </b><?php echo $inviter_profile_full['relationship']; ?></p>
            <?php endif; ?>
            
            <?php if(isset($inviter_profile_full['looking_for'])): ?>
            	<p><b>Tìm Kiếm: </b><?php echo $inviter_profile_full['looking_for']; ?></p>
            <?php endif; ?>
            
            <p><b>Ngôn Ngữ Sử Dụng: </b><?php echo $inviter_profile_full['languages']; ?></p>
            
            <h4>NHỮNG GÌ BẠN THẤY</h4>
            
            <p><b>Chiều Cao:</b><?php echo $inviter_profile_full['height']; ?></p>
            <p><b>Cân Nặng:</b><?php echo $inviter_profile_full['weight']; ?> </p>
            
            <p><b>Hình Thể: </b><?php echo $inviter_profile_full['build']; ?></p>
            <p><b>Lông Cơ Thể: </b><?php echo $inviter_profile_full['body_hair']; ?></p>
            <p><b>Hình Xăm:</b><?php echo $inviter_profile_full['tattoo']; ?></p>
            <p><b>Khuyên: </b><?php echo $inviter_profile_full['piercing']; ?></p>
        </div>
            </div>
        </div>        
    </div>
</div>
<!-- InstanceEndEditable -->