    <?php 
		$userCurrent =  Yii::app()->user->data();
	?>
    <div class="table42 clearfix"> 
    	<div class="list_request_page content">
        	<div class="wrap_user_req clearfix">
        		<h1>Danh sách Đồng Ý Kết Đôi</h1>
                <div class="wrap_img_person listagree">
	                
                </div>
                <a href="javascript:void(0);" class="icon_table42 iconNextBack icon_back_list" onclick="Tablefortwo.getListagreePre();"></a>
                <a href="javascript:void(0);" class="icon_table42 iconNextBack icon_next_list" onclick="Tablefortwo.getListagreeNext();"></a>
                <input type="hidden" id="numPersonShow" name="numPersonShow" value="0" />
            </div>
            <div class="menu_list">
                <div class="wrap_menu_42">
                    <ul>
                        <li class="index"><a href="/" title="Trang chủ" class="icon_table42 btnEffectBg"></a></li>
                        <li class="danhsach"><a href="<?php echo Yii::app()->createUrl('/table42/listmember');?>" title="Danh sách" class="icon_table42 btnEffectBg"></a></li>

                        <?php if($signup_ok): ?>
                            <li class="lmkb "><a href="<?php echo Yii::app()->createUrl('/table42/listrequest');?>" title="Lời mời kết bạn" class="icon_table42 btnEffectBg"></a></li>
                            <li class="dykd active"><a href="<?php echo Yii::app()->createUrl('/table42/listagree');?>" title="Đồng ý kết đôi" class="icon_table42 btnEffectBg"></a></li>
                        <?php endif; ?>

                        <li class="couple"><a href="<?php echo Yii::app()->createUrl('/table42/listcouple');?>" title="Couple" class="icon_table42 btnEffectBg"></a></li>
                        <li class="result"><a href="<?php echo Yii::app()->createUrl('/table42/result');?>" title="Kết quả" class="icon_table42 btnEffectBg"></a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" name="paging" id="paging" value="1" />

	<div id="popup_chose_ketdoi" class="popup_general" title="" style="display:none;">
	</div>