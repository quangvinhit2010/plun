	<div id="popup_signup" class="popup_general" title="Đăng ký" style="display:none;">	  
	    <div class="content">
	    	<h1>Đăng ký</h1>
	        <ul>
	            <li class="half"><input class="txt" id="RegisterForm_username" name="RegisterForm[username]" type="text" value="" placeholder="Username"/></li>
	            <li class="half"><input class="txt" id="RegisterForm_email" name="RegisterForm[email]" type="text" value="" placeholder="Email"/></li>
	            <li class="half"><input class="txt" id="RegisterForm_password" name="RegisterForm[password]" type="password" value="" placeholder="Mật khẩu"/></li>
	            <li class="half">
	                <div class="select_style w76">
	                    <?php 
	                    	$days	=	BirthdayHelper::model()->getDates();
	                    	echo CHtml::dropDownList('RegisterForm[day]', current($days), $days, array('id' => 'RegisterForm_day', 'class' => 'virtual_form', 'text' => 'register_day')); ?>
	                	<span class="txt_select"><span class="register_day"><?php echo current($days); ?></span></span> <span class="btn_combo_down"></span>
	                </div>
	                <div class="select_style w76">                              
	                	<?php 
	                		$month	=	BirthdayHelper::model()->getMonth();
	                		echo CHtml::dropDownList('RegisterForm[month]', key($month), $month, array('id' => 'RegisterForm_month', 'class' => 'virtual_form', 'text' => 'register_month')); ?>
	                	<span class="txt_select"><span class="register_month"><?php echo current($month); ?></span></span> <span class="btn_combo_down"></span>
	                </div>
	                <div class="select_style w76">                     
	                    <?php 
	                    	$year	=	BirthdayHelper::model()->getYears();
	                    	echo CHtml::dropDownList('RegisterForm[year]', key($year), $year, array('id' => 'RegisterForm_year', 'class' => 'virtual_form', 'text' => 'register_year')); ?>
	                <span class="txt_select"><span class="register_year"><?php echo current($year); ?></span></span> <span class="btn_combo_down"></span>
	                </div>
	                            
	            </li>
	            <li class="half"><input class="txt" id="RegisterForm_confirm_password" name="RegisterForm[confirm_password]" type="password" value="" placeholder="Xác nhận mật khẩu"/></li>
	            <li class="half">
	            	<div class="captcha">
		                <input class="code" id="RegisterForm_verifyCode" name="RegisterForm[verifyCode]" type="text" value="" placeholder="Nhập ký tự"/>
		                <?php $this->widget('CCaptcha', array(
		                    		            'buttonLabel'=>'',
		                						'captchaAction' => 'table42/captcha',
		                                        'imageOptions' => array(
		                                            'style'=>'height: 34px;'
		                                        )
		               )); ?>
	               </div>
	            </li>
	            <li class="half">
	            	<div class="select_style w240">
	              		<?php echo CHtml::dropDownList('RegisterForm[sexrole]', false, array(ProfileSettingsConst::SEXROLE_TOP => 'Top', ProfileSettingsConst::SEXROLE_BOTTOM => 'Bottom'), array('id' => 'RegisterForm_sexrole', 'class' => 'virtual_form', 'text' => 'register_sexrole', 'empty' => 'Vai Trò')); ?>
	               	 	<span class="txt_select"><span class="register_sexrole">Vai Trò</span></span> <span class="btn_combo_down"></span>
	                </div>
	            </li>
	            <li><input class="chk" name="RegisterForm[agree]" type="checkbox" id="RegisterForm_agree" value="1" /><label for="RegisterForm_agree">Để tham gia trang này bạn xác nhận rằng mình tối thiểu 16 tuổi để hiểu rõ các rủi ro khi tham gia và đồng ý với</label> <a href="#" class="reg"> Thỏa thuận sử dụng</a></li>
	            <li class="center">
	            	<a class="bg_btn" href="javascript:void(0);" title="Đăng ký ngay" onclick="Tablefortwo.register();"><span class="bg_btn">Đăng ký ngay</span></a>
	            </li>
	            <li class="center">Đã có tài khoản?<a href="#" class="reg link_login" data-effect-id="popup_login"> Đăng nhập</a></li>
	        </ul>
	    </div>
	</div>