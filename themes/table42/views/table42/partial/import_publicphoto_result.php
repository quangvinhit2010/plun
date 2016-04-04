    <div class="content">
    	<h1>Từ Ảnh công cộng</h1>
        <p class="tb_upload">Bạn vui lòng chọn tối đa 3 hình trong ảnh công cộng</p>
        <div class="wrapImgUpload">
            <ul class="clearfix">
            	<?php foreach ($photos AS $row): ?>
                <li>
                    <a href="javascript:void(0);" class="wrap_img"><img pid="<?php echo $row->id; ?>" width="203px" height="204px" src="<?php echo $row->getImageThumbnail(true);?>" /><span class="icon_table42"></span></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <a class="bg_btn" href="javascript:void(0);" title="Chọn từ ảnh Công cộng" onclick="Tablefortwo.choosePhotoFromPublicPhoto();"><span class="bg_btn">Lưu hình ảnh</span></a>
        </div>
    </div>