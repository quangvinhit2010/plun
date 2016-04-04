<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/rate.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('RateInit', "Rate.Init();", CClientScript::POS_END);
$url = $this->controller->usercurrent->createUrl('//rate/list', array('var'=>Util::encryptRandCode('list-rating')));
?>
<div class="left danhhieu loadingItem">
    <div class="title"> <ins></ins> Danh hiệu </div>
    <div class="content">
        <ul>
            <li class="catim"><a title="Cà tím" href="javascript:;"></a></li>
            <li class="hinhthat"><a title="Hình thật" href="javascript:;"></a></li>
            <li class="hinhao"><a title="Hình ảo" href="javascript:;"></a></li>
            <li class="congchua"><a title="Công chúa" href="javascript:;"></a></li>
            <li class="dethuong"><a title="Dể thương" href="javascript:;"></a></li>
            <li><a href="javascript:;" class="showlist_danhhieu" data-url="<?php echo $url;?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/rating-next.png"/></a></li>
			<!--<li class="hangnho"><a title="Hàng nhỏ" href="javascript:;"></a></li>
			<li class="hangnong"><a title="Hàng nóng" href="javascript:;"></a></li>
			<li class="deptrai"><a title="Đẹp trai" href="javascript:;"></a></li>
			<li class="langlo"><a title="Lẵng lơ" href="javascript:;"></a></li>
			<li class="tinh1dem"><a title="Tình 1 đêm" href="javascript:;"></a></li>-->
		</ul>
	</div>
</div>
