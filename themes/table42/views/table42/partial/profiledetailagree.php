<div class="content clearfix">
        <div class="left pic_person">
        	<div class="img_big"><a href="<?php echo $profile->photo->getImageThumb768x1024(true); ?>" class="wrap_img"><img src="<?php echo $profile->photo->getImageThumb203x204(true); ?>" width="203px" height="204px" /></a></div>
            
            <?php if(isset($list_photo[0])): ?>
            <div class="img_small">
            	<ul>
            		<?php foreach($list_photo AS $row): ?>
                	<li>
                		<a href="<?php echo $row->getImageThumb768x1024(true); ?>" class="wrap_img">
                			<img width="56px" height="51px" src="<?php echo $row->getImageThumb203x204(true); ?>" />
                		</a>
                	</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php 
            	if($accept_dating):
            ?>
            <div class="btn_center request-button">
            	<a class="bg_btn" href="javascript:void(0);" title="Đồng Ý Ghép Đôi" onclick="Tablefortwo.agreeDating('<?php echo $profile->id; ?>');"><span class="bg_btn">Đồng Ý Ghép Đôi</span></a>
            </div>
            <?php endif;?>
            
        </div>
        <div class="right infor_user">
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
            
            <h4>Mở rộng</h4>
            <?php if(isset($detail_profile['occupation'])): ?>
            	<p><b>Nghề Nghiệp: </b><?php echo $detail_profile['occupation']; ?></p>
            <?php endif; ?>
            
            <p><b>Tôn Giáo: </b><?php echo $detail_profile['religion']; ?></p>
            <p><b>Tính Cách: </b><?php echo $detail_profile['mannerism']; ?></p>
            <p><b>Hút Thuốc: </b><?php echo $detail_profile['smoke']; ?></p>
            <p><b>Rượu: </b><?php echo $detail_profile['drink']; ?></p>
            <p><b>Vũ Trường: </b><?php echo $detail_profile['club']; ?></p>
            <p><b>Sex An Toàn: </b><?php echo $detail_profile['club']; ?></p>
            <p><b>Mức Độ Công Khai: </b><?php echo $detail_profile['safer_sex']; ?></p>
            <p><b>Tôi Sống Với:</b><?php echo $detail_profile['live_with']; ?></p>
        </div>
 </div>