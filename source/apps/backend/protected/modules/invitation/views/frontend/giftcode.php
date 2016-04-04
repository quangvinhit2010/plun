<fieldset>
	<legend>Danh sách Giftcode bạn được nhận từ việc <a href="<?php echo Yii::app()->createUrl('/invitation/frontend');?>">invite friend</a></legend>
	<div id="fb_friends">
		<?php if(!empty($InviteBonus)):?>
		<ul class="friends">
			<?php foreach ($InviteBonus as $bonus):?>
			<li>
				<?php echo $bonus->gift_code;?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php endif;?>
	</div>
</fieldset>
