	<div id="popup_upload" class="popup_general" title="Upload Photo" style="display:none;">	    
	    <div class="content">
            <h1>Upload Photo</h1>
	        <ul>
	            <li class="center"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.importPublicPhoto();" title="Chọn từ ảnh Công cộng"><span class="bg_btn">Chọn từ ảnh Công cộng</span></a></li>
	            <li class="center"><a class="bg_btn" onclick="$('#uploadImage').trigger( 'click' ); return false;" href="javascript:void(0);" title="Tải ảnh từ máy"><span class="bg_btn">Tải ảnh từ máy</span></a></li>
	        </ul>
	    </div>
	</div>
	<div class="upload_thumbnail" style="display: none;">
		<input type="file" id="uploadImage" multiple="multiple">
	</div>
	<input type="hidden" name="uploadType" id="uploadType" value="1" />