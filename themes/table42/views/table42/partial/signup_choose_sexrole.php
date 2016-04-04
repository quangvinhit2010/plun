	<div id="popup_thamgia_sexrole" class="popup_general" title="Đăng Ký tham gia" style="display:none;">	    
	    <div class="content">
	    	<h1>Đăng Ký tham gia</h1>
	        <ul>
	            <li><input class="txt" name="sigup_input" id="sigup_input" type="text" value="" placeholder="Nhập số phone hoặc Facebook"/></li>
	            				<li>
	            	<div class="select_style w240">
	              		<?php echo CHtml::dropDownList('SignupForm[sexrole]', false, array(ProfileSettingsConst::SEXROLE_TOP => 'Top', ProfileSettingsConst::SEXROLE_BOTTOM => 'Bottom'), array('id' => 'SignupForm_sexrole', 'class' => 'virtual_form', 'empty' => 'Vai Trò','text' => 'register_sexrole')); ?>
	               	 	<span class="txt_select"><span class="register_sexrole">Vai Trò</span></span> <span class="btn_combo_down"></span>
	                </div>
	            </li>
	            <li>
	            	<input class="chk" name="SignupForm_rememberMe" type="checkbox" id="SignupForm_rememberMe"/>
	            	<label for="SignupForm_rememberMe">Để tham gia chương trình bạn cần đồng ý với</label> 
					<a href="#" class="reg">điều khoản sử dụng</a>
				</li>

	            <li class="center"><a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.SignUp();" title="tham gia"><span class="bg_btn">tham gia</span></a></li>                
	        </ul>
	    </div>
	</div>