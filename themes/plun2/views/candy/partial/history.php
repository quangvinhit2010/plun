<?php
	$newTransaction = 0;
	if($this->usercurrent->balance) {
		$newTransaction = $this->usercurrent->balance->new_transaction;
		$this->usercurrent->balance->new_transaction = 0;
		$this->usercurrent->balance->save();
	}
?>
<div class="content left">                
  <div class="left title">
	<h3 class="left">lịch sử giao dịch</h3>
  </div>
  <div class="thoidiem">
  		<form id="search-form" action="<?php echo $this->usercurrent->createUrl('//candy/history') ?>" method="get">
			<div class="khoang left">
				Lọc theo:
				<select name="actionType">
					<option value="">Tất cả</option>
					<option value="<?php echo Balance::ACTION_TYPE_USE ?>">Sử dụng Candy</option>                                    
					<option value="<?php echo Balance::ACTION_TYPE_RECEIVE ?>">Nạp/Nhận Candy</option>                                    
				</select>
			</div>
			<div class="cuthe left">
				<p>Từ <input name="start" type="text" value="1-<?php echo date('m-Y') ?>" class="date"/> đến <input name="end" type="text" value="<?php echo date('d-m-Y') ?>" class="date"/></p>
			</div>
			<div class="right"><div id="search-loading" class="loadingInside" style="visibility: hidden;"></div><input id="search-button" name="" type="button" value="Tìm kiếm" class="but"/></div>
		</form>
  </div>
  <div class="list_giaodich" style="width: 100%;">
	<ul>
		<?php if(empty($candyHistorys)): ?>
		<li>Không có giao dịch trong khoảng thời gian này</li>
		<?php else: ?>
			<?php foreach ($candyHistorys as $key => $candyHistory): ?>
			<li<?php if($key < $newTransaction) echo ' class="new"' ?>>
				<div class="left">
					<?php
						$metaData = json_decode($candyHistory->meta_data);
						if($metaData) {
							$img = $metaData->avatar;
							$userUrl = CHtml::link($metaData->username, Yii::app()->createUrl('', CMap::mergeArray(array('u' => $metaData->username), array())), array('target'=>'_blank'));
						}
						$candy = "<a href='javascript:;'>{$candyHistory->candy_amount}</a>";
						switch ($candyHistory->object_type) {
							case Balance::TYPE_TRANSFER:
								if($candyHistory->action_type == Balance::ACTION_TYPE_USE)
									$text = "Tặng $candy candies cho $userUrl. chiết khấu 1%.";
								else
									$text = "Nhận được $candy candies từ $userUrl.";
								break;
							case Balance::TYPE_PRIVATE_PHOTO:
								if($candyHistory->action_type == Balance::ACTION_TYPE_USE) {
									$text = "Trả $candy candies để xem <a class='color-box' href='".Yii::app()->createUrl('photo/Detail', array('id'=>$candyHistory->object_id))."' target='_blank'>ảnh riêng tư</a> của $userUrl.";
								} else {
									$img = Yii::app()->theme->baseUrl."/resources/html/css/images/icon_sao.jpg";
									$text = "Bạn nhận được $candy candy từ <a class='color-box' href='{$metaData->privatePhotoUrl}' target='_blank'>ảnh riêng tư</a>.";
								}
								break;
							case Balance::TYPE_GET_FREE:
								$img = Yii::app()->theme->baseUrl."/resources/html/css/images/icon_sao.jpg";
								$text = "Nhận được $candy candies miễn phí từ hệ thống";
						}
					?>
					<img width="25" height="25" src="<?php echo $img ?>" align="absmiddle" />
					<?php echo trim($text) ?></div>
				<div class="time right" style="line-height: 25px;"><?php echo date('H:i d-m-Y', $candyHistory->created) ?></div>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	<?php if($total > $compareWithTotal): ?>
	<div class="pagging" style="text-align: center;">
		<div id="paging-loading" class="loadingInside" style="position: absolute; margin-top: 9px; margin-left: -8px; visibility: hidden;"></div>
		<a href="<?php echo $this->usercurrent->createUrl('//candy/history', $params) ?>" onclick="" href="javascript:void(0);"><ins></ins></a>
	</div>
	<?php endif; ?>
  </div>
</div>