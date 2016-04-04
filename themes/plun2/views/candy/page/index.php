<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/candy.js?t='.time(), CClientScript::POS_END);//?t='.time(), CClientScript::POS_END
Yii::app()->clientScript->registerScript('Candyhistory', "Candy.history(); var CHARGE_TYPE_PERCENT = ".Charge::CHARGE_TYPE_PERCENT."; var CHARGE_TYPE_AMOUNT = ".Charge::CHARGE_TYPE_AMOUNT.";", CClientScript::POS_END);

$getFree = new GetFree();
$isGetFree = $getFree->find('user_id = :user_id', array(':user_id'=>$this->usercurrent->id));
?>
<div class="container pheader min_max_1024 clearfix hasBanner_160 candy_page">
  <div class="explore left">
	<div class="list_explore">
	  <div class="left candy_static sticky_column">
			<div class="candy_info">
				<div class="title">Candy</div>
				<div class="content">
					  <div class="info">
						<p>Số candy hiện có:</p>
						<p class="num"><?php echo !empty($this->usercurrent->balance->candy) ? number_format($this->usercurrent->balance->candy) : 0;?></p>
                        <?php if(!$isGetFree): ?><a class="btnGetCandyFree" href="<?php echo $this->usercurrent->createUrl('//candy/getFreeCandy') ?>"><ins class="icon_common"></ins>nhận candy miễn phí</a><?php endif; ?>
					  </div>
					  <ul class="list_menu_candy">
                          	<li><a href="javascript:;" class="ajax-load" data-url="<?php echo $this->usercurrent->createUrl('//candy/welcome');?>">Candy là gì?</a></li>
                            <li><a href="javascript:;" class="ajax-load" data-url="<?php echo $this->usercurrent->createUrl('//candy/history');?>">Lịch sử giao dịch</a></li>
                            <li><a href="javascript:;" class="coming-soon">Chuyển candy</a></li>
                            <li><a href="javascript:;" class="coming-soon">Nâng cấp tài khoản <ins class="icon_common"></ins></a></li>
                      </ul>
				</div>
			</div>
			<div class="tang_candy">
				<div class="title">Tặng Candy</div>
				<form data-charge-type="<?php echo $charge->charge_type ?>" data-charge="<?php echo $charge->charge ?>" id="transfer-form" action="<?php echo $this->usercurrent->createUrl('//candy/index'); ?>" method="post">
					<div class="content">
						<ul>
							<li>
								<input id="lstUser" name="receiver" type="text" placeholder="Người nhận" style="width: 100%" />
								<?php 
									$this->widget('backend.extensions.select2.ESelect2',array(
									  'selector'=>"#lstUser",
									  'options'=>array(
										'allowClear'=>true,
										'minimumInputLength' => 2,
										'maximumSelectionSize' => 10,
										'multiple'=>true,                            
										'ajax'=>array(
											'url'=>$this->user->createUrl('my/GetUsersSuggest'),
											'dataType'=>'json',
											'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
											'results'=>'js:function(data,page) { return {results: data}; }',
										),
									  ),
									));
								?>
							</li>
							<li>
								<?php echo CHtml::dropDownList('candy', 'candy', VCandy::model()->listCandyCanTranfer(), array('empty'=>'Số Candy', 'class'=>'chooseCandy'));?>
	<!-- 							<select> -->
	<!-- 								<option>Số Candy</option> -->
	<!-- 							</select> -->
							</li>
							<li class="capchaTangCandy">
								<input id="captcha" name="captcha" />
								<?php
									$this->widget('CCaptcha', array(
										'buttonLabel'=>'',
										'imageOptions' => array(
    	                            		'style'=>'height: 28px;',
    	                            		'class'=>'left',
    	                            		'id'=>'yw0',
    	                            	),
    	                                'buttonOptions' => array(
    	                                	'class'=>'reload',
    	                                	'id'=>'yw1',
    	                                )
									));
								?>
								<?php 
									$captcha = Yii::app()->getController()->createAction("captcha");
									$result = $captcha->getVerifyResult();
									$result = preg_replace('/\s/', '', $result);
									$result = urlencode($result);
									$hash = $captcha->generateValidationHash($result);
								?>
								<input type="hidden" id="hash" value="<?php echo $hash ?>" />
    	                    </li>
							<li>*Chúng tôi sẽ lấy chiết khấu <?php echo $charge->charge;?>% trên tài khoản của bạn.</li>
							<li class="right"><input class="but" name="" type="submit" value="Gửi" /></li>
						</ul>
					</div>
				</form>
			</div>
			<div class="nap_candy">
				<div class="title">Nạp Candy</div>
                <div class="content">
                    <div class="nganluong"><a href="javascript:;" class="coming-soon"></a></div>
                    <div class="thecao"><a href="javascript:;" class="coming-soon"></a></div>
            	</div>
			</div>
	  </div>
	  <div class="left content_candy sticky_column">
	  	<?php if($this->usercurrent->balance && $this->usercurrent->balance->new_transaction): ?>
	  		<?php CController::forward('/candy/history', false); ?>
	  	<?php else: ?>
	  		<?php CController::forward('/candy/welcome', false); ?>
	  	<?php endif; ?>
	  </div>
	</div>
	<?php $this->widget('frontend.widgets.UserPage.Banner', array()); ?>
  </div>
</div>
<div id="popup_UpgradeVip" class="popup_UpgradeVip" style="display:none;">
</div>
<?php 
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'confirmTransfer', 'content'=>''));
?>