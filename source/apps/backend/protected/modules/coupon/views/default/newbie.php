<div class="gcpage">
	<div class="wrap">
	<?php //if($is_fan == true){ ?>
		<script>
		$(document).ready(function(){
			sprPopMain('.popcode');
		});
		function GiftcodePopClose(){
			//$.colorbox.close();
			$.colorbox({
				onClosed:Redirect(),
			});
		}
		function Redirect(){
			var url = window.location.protocol + '//' + window.location.hostname
			sprPopClose();
			window.location.assign(url);
		}
		</script>
			<div class="popcode2">
				<div class="margin popcode">
				<div class="maintab clearfix">
					<div class="maintab-wrap clearfix">
						<div class="tab-top">
							<h2 class="poptitle-codett">Gift Code Tân Thủ</h2>
							<a href="javascript:GiftcodePopClose();" title="Tắt" class="btn-close">Close</a>
						</div>
						<!-- top tab -->
						<div class="tab-cont">
							<?php if(isset($code)) {?>
							<div class="qckreg qckcode clearfix">
								<h3>Đây là Giftcode Tân thủ của bạn</h3>
								<span class="code"><?php echo $code->code; ?></span>
							</div>
							<div class="note">
								<p class="code">Thời hạn 11/01/2013 - 11/05/2013</p>
							</div>
							<?php } else { ?>
							<div class="qckreg qckcode clearfix">
								<h3>Sự kiện này đã hết hoặc không còn giftcode trong hệ thống</h3>
							</div>
							<?php } ?>
							
						</div>
						<!-- tab top -->
					</div>
					<!-- main table -->
				</div>
				<!-- wrap -->
			</div>
			</div>
	<?php //} else { ?>
	<!-- 
			<div class="step1">
			<h2>Bước 1</h2>
				<div class="gcfb">
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fdp.like.vn&amp;width=200&amp;height=120&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color=%23c08857&amp;header=true&amp;appId=110340419066263" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:120px;" allowTransparency="true"></iframe>
				</div>
			</div>
			<div class="step2">
				<h2>Bước 2</h2>
					<a href="<?php //echo $loginUrl; ?>">Login with Facebook</a>
			</div>
	-->				
	<?php 	//} ?>
	</div>
	<div class="gclist">
		<ul>
			<li><a href="/article/34/qua-tang-tro-luc-tan-thu-dong-phong">Hướng dẫn nhận giftcode Tân thủ</a></li>
			<li><a href="/article/36/huong-dan-su-dung-giftcode-trong-game-dong-phong">Hướng dẫn sử dụng giftcode trong game</a></li>
			<li><a href="/article/37/huong-dan-tao-tai-khoan-facebook">Hướng dẫn tạo tài khoản facebook</a></li>
		</ul>
	</div>
</div>
