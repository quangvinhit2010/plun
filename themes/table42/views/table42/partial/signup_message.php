	<?php 
		$data	=	Yii::app()->user->data();
		$params = CParams::load ();
	?>
	<div id="popup_xacnhan" class="popup_general" title="XÁC NHẬN THÔNG TIN" style="display:none;">	    
	    <div class="content">
            <h1>XÁC NHẬN THÔNG TIN</h1>
	        <ul>
	            <li>Bạn vui lòng <a href="<?php echo $params->params->base_url . Yii::app()->createUrl('//messages/index', array('alias' => $data['alias_name'])); ?>">xác nhận thông tin</a> được gửi trong Massage của Plun.asia</li>
	        </ul>
	    </div>
	</div>   