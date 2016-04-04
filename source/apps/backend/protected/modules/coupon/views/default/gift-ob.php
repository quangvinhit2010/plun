<?php $this->pageTitle=Yii::app()->name; ?>
<div class="margin popcode">
	<div class="maintab clearfix">
		<div class="maintab-wrap clearfix">
			<div class="tab-top">
				<h2 class="poptitle-codeob">Gift Code Open Beta</h2>
				<a href="javascript:sprPopClose();" title="Tắt" class="btn-close">Close</a>
			</div>
			<!-- top tab -->
			<div class="tab-cont">
				<div class="qckreg qckcode clearfix">
					<?php if(Yii::app()->user->isGuest): ?>
							<span class="code">Bạn vui lòng đăng nhập để nhận Giftcode</span>	
					<?php else:?>
						<h3>Đây là Giftcode Open Beta của bạn</h3>
						<?php if(!empty($code)) :?>
							<span class="code"><?php echo $code->code;?></span>
						<?php elseif($userPlayInGameOB==false): ?>
							<span class="code">Giftcode này dành cho người chơi trong khoảng thời gian 28-12-2012 đến 10-01-2013.</span>	
						<?php else: ?>
							<span class="code">Đã hết Giftcode cho event này.</span>	
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="note">
					<p class="code">Thời hạn 11/01/2013 - 30/03/2013</p>
				</div>
			</div>
			<!-- tab top -->
		</div>
		<!-- main table -->
	</div>
	<!-- wrap -->
</div>