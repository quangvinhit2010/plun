    <div class="content clearfix">
    	<div class="top_img_ketdoi">
        	<div class="wrap_img_ketdoi clearfix">
            	<div class="wrap_img left"><img src="<?php echo $friend_profile->photo->getImageThumb203x204(true); ?>" width="203px" height="204px" /></div>
                <div class="wrap_img left"><img src="<?php echo $inviter_profile->photo->getImageThumb203x204(true); ?>" width="203px" height="204px" /></div>
                <div class="icon_table42 icon_heart"></div>
            </div>
            <div class="btn_center">
            	<span class="btn_gray total_vote_couple"><?php echo $request->vote_total; ?></span>
                <a class="bg_btn request-button" href="javascript:void(0);" title="Bình chọn" onclick="Tablefortwo.voteCouple(<?php echo $request->id; ?>);"><span class="bg_btn">Bình chọn</span></a>
            </div>
        </div>
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