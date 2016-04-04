<?php $purpleguyContactModel = new PurpleguyContact(); ?>
<div class="popup_hotro popup_general" style="display: none;">
	<h3>Hỗ trợ</h3>
    <a href="#" class="but_close"></a>    
    <div class="content content_hotro mCustomScrollbar _mCS_1"><div class="mCustomScrollBox mCS-light" id="mCSB_1" style="position:relative; height:100%; overflow:hidden; max-width:100%;"><div class="mCSB_container mCS_no_scrollbar" style="position: relative; top: 0px;">
    	<?php 
			$form = $this->beginWidget('CActiveForm', array(
				'id'=>'frm-contact',
    			'action'=>Yii::app()->createUrl('//vote/contact'),
				'htmlOptions'=>array('autocomplete'=>"off",),
				'enableClientValidation'=>true
			));
    	?>
    	<p>Những câu hỏi và các vấn đề cần được hổ trợ sẽ được gửi về <a href="#">pg@plun.asia</a></p>
        <ul>
        	<li>
        		<?php echo $form->textField($purpleguyContactModel, 'name', array('placeholder'=> 'Họ Tên')); ?>
        		<?php echo $form->error($purpleguyContactModel, 'name'); ?>
        	</li>
            <li>
            	<?php echo $form->textField($purpleguyContactModel, 'email', array('placeholder'=> 'Email')); ?>
            	<?php echo $form->error($purpleguyContactModel, 'email'); ?>
            </li>
            <li>
            	<?php echo $form->textField($purpleguyContactModel, 'phone_number', array('placeholder'=> 'Số Điện Thoại')); ?>
            	<?php echo $form->error($purpleguyContactModel, 'phone_number'); ?>
            </li>
            <li>
            	<?php echo $form->textArea($purpleguyContactModel, 'body', array('placeholder'=> 'Nội dung câu hỏi')); ?>
            	<?php echo $form->error($purpleguyContactModel, 'body'); ?>
            </li>
            <li><input class="but active" name="" type="submit" value="Submit"><input class="but" name="" type="reset" value="Reset"></li>
        </ul>
        <?php $this->endWidget(); ?>
    </div><div class="mCSB_scrollTools" style="position: absolute; display: none;"><div class="mCSB_draggerContainer"><div class="mCSB_dragger" style="position: absolute; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="position:relative;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
</div>
<script>
	$('#frm-contact').submit(function(e){
		$('body').loading();
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(response){
			$('body').unloading();
			if(response == '1') {
				$('.popup_hotro').find('.content').html('<div style="line-height: 20px; margin-bottom: 10px;">Bạn đã gửi hỗ trợ thành công.</div>');
			} else {
				$.each(response, function(index, val){
					$('#PurpleguyContact_'+index).next().html(val).show();
				});
			}
		}, 'json');
		e.preventDefault();
	});
</script>