    <div class="content clearfix">
    	<div class="top_img_ketdoi">
        	<div class="wrap_img_ketdoi clearfix">
            	<div class="wrap_img left"><img src="<?php echo $my_profile->photo->getImageThumb203x204(true); ?>" width="203px" height="204px" /></div>
                <div class="wrap_img left"><img src="<?php echo $profile->photo->getImageThumb203x204(true); ?>" width="203px" height="204px" /></div>
                <div class="icon_table42 icon_heart"></div>
            </div>
            
            <div class="btn_center request-button">
	            	<?php if($request->is_dating && $my_profile->id == $request->user_make_dating){ ?>	
	                	<a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.cancelDating(<?php echo $profile->id; ?>);" title="Hủy Kết Đội"><span class="bg_btn">Hủy Kết Đôi</span></a>
	            	<?php }else{ ?>
	            		<?php if(!$request->is_dating):?>
	                	<a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.makeDating(<?php echo $profile->id; ?>);" title="Chọn cặp kết đôi"><span class="bg_btn">Chọn cặp kết đôi</span></a>
	            		<?php endif; ?>
	            	<?php } ?>
            </div>
        </div>
        <div class="infro_ketdoi">
            <div class="wrap_scroll_content clearfix">
                <div class="infor_user">
        	<h2><?php echo $my_profile->username; ?></h2>
            <p><b>Từ: </b><?php echo $my_detail_profile['location']; ?></p>
            <h4>THÔNG TIN CƠ BẢN</h4>
            <?php if(isset($my_detail_profile['ethnicity'])): ?>
           	<p><b>Sắc Tộc: </b><?php echo $my_detail_profile['ethnicity']; ?>  </p>
            <?php endif; ?>
            
            <p><b>Tuổi:</b><?php echo $my_detail_profile['birthday_year']; ?></p>
            
            <?php if(isset($my_detail_profile['sexuality'])): ?>
            	<p><b>Giới tính: </b><?php echo $my_detail_profile['sexuality']; ?>  </p>
            <?php endif; ?>
            
            <?php if(isset($my_detail_profile['role'])): ?>
            <p><b>Vai Trò:</b><?php echo $my_detail_profile['role']; ?> </p>
            <?php endif; ?>
            
            <?php if(isset($my_detail_profile['relationship'])): ?>
            	<p><b>Tình trạng Quan hệ: </b><?php echo $my_detail_profile['relationship']; ?></p>
            <?php endif; ?>
            
            <?php if(isset($my_detail_profile['looking_for'])): ?>
            	<p><b>Tìm Kiếm: </b><?php echo $my_detail_profile['looking_for']; ?></p>
            <?php endif; ?>
            
            <p><b>Ngôn Ngữ Sử Dụng: </b><?php echo $my_detail_profile['languages']; ?></p>
            
            <h4>NHỮNG GÌ BẠN THẤY</h4>
            
            <p><b>Chiều Cao:</b><?php echo $my_detail_profile['height']; ?></p>
            <p><b>Cân Nặng:</b><?php echo $my_detail_profile['weight']; ?> </p>
            
            <p><b>Hình Thể: </b><?php echo $my_detail_profile['build']; ?></p>
            <p><b>Lông Cơ Thể: </b><?php echo $my_detail_profile['body_hair']; ?></p>
            <p><b>Hình Xăm:</b><?php echo $my_detail_profile['tattoo']; ?></p>
            <p><b>Khuyên: </b><?php echo $my_detail_profile['piercing']; ?></p>
        </div>
                <div class="infor_user">
        	<h2><?php echo $profile->username; ?></h2>
            <p><b>Từ: </b><?php echo $detail_profile['location']; ?></p>
           <h4>THÔNG TIN CƠ BẢN</h4>
            <?php if(isset($detail_profile['ethnicity'])): ?>
           	<p><b>Sắc Tộc: </b><?php echo $detail_profile['ethnicity']; ?>  </p>
            <?php endif; ?>
            
            <p><b>Tuổi:</b><?php echo $detail_profile['birthday_year']; ?></p>
            
            <?php if(isset($detail_profile['sexuality'])): ?>
            	<p><b>Giới tính: </b><?php echo $detail_profile['sexuality']; ?>  </p>
            <?php endif; ?>
            
            <?php if(isset($detail_profile['role'])): ?>
            <p><b>Vai Trò:</b><?php echo $detail_profile['role']; ?> </p>
            <?php endif; ?>
            
            <?php if(isset($detail_profile['relationship'])): ?>
            	<p><b>Tình trạng Quan hệ: </b><?php echo $detail_profile['relationship']; ?></p>
            <?php endif; ?>
            
            <?php if(isset($detail_profile['looking_for'])): ?>
            	<p><b>Tìm Kiếm: </b><?php echo $detail_profile['looking_for']; ?></p>
            <?php endif; ?>
            
            <p><b>Ngôn Ngữ Sử Dụng: </b><?php echo $detail_profile['languages']; ?></p>
            
            <h4>NHỮNG GÌ BẠN THẤY</h4>
            
            <p><b>Chiều Cao:</b><?php echo $detail_profile['height']; ?></p>
            <p><b>Cân Nặng:</b><?php echo $detail_profile['weight']; ?> </p>
            
            <p><b>Hình Thể: </b><?php echo $detail_profile['build']; ?></p>
            <p><b>Lông Cơ Thể: </b><?php echo $detail_profile['body_hair']; ?></p>
            <p><b>Hình Xăm:</b><?php echo $detail_profile['tattoo']; ?></p>
            <p><b>Khuyên: </b><?php echo $detail_profile['piercing']; ?></p>
        </div>
            </div>
        </div>
    </div>