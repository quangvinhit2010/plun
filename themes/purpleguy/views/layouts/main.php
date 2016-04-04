<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->beginContent('//layouts/partials/head'); ?><?php $this->endContent(); ?>
</head>
<body<?php echo VHelper::model()->parseAttributesHtml($this->option_html['body']['attributes'])?>>
    <div <?php echo VHelper::model()->parseAttributesHtml($this->option_html['container']['attributes'])?>>
        <div class="wrapper">
            <?php $this->beginContent('//layouts/partials/header'); ?><?php $this->endContent(); ?>
            <div class="col-nav">
            	<div class="menu">
                    <p><a href="/"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/images/PurpleGuy_logo.jpg" align="absmiddle"></a></p>
                    <h3>vòng loại tháng</h3>
                    <ul>
                        <li class="thamgia"><a href="#">tham gia</a></li>
                        <li class="thele"><a href="#">thể lệ & giải thưởng</a></li>
                        <li class="hotro"><a class="active" href="#">hỗ trợ</a></li>
                        <li>
                            <div class="select_style">
                            		<?php
                            			$order_by = Yii::app()->request->getQuery('order_by', 'default');
                            			$order = array(
                            				'created' => 'Mới Nhất',
											'total_view' => 'Xem Nhiều Nhất',
											'total_vote' => 'Vote Nhiều Nhất',
											'default' => 'Sắp xếp theo'
                            			);
                            		?>
                                    <div class="dropdown-box">
                                        <span class="txt_select"><span class="country_register_text"><?php echo $order[$order_by] ?></span></span> 
                                        <span class="btn_combo_down">&nbsp;</span>
                                        <ul>
                                            <li data-value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'created',)) ?>"><span>Mới Nhất</span></li>
                                            <li data-value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'total_view',)) ?>"><span>Xem Nhiều Nhất</span></li>
                                            <li data-value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'total_vote',)) ?>"><span>Vote Nhiều Nhất</span></li>
                                        </ul>
                                    </div>
                                    <!-- <select class="sort_item">
                                    	<option style="display: none;">Sắp xếp theo</option>
	                                    <option value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'created',)) ?>">Mới Nhất</option>
	                                    <option value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'total_view',)) ?>">Xem Nhiều Nhất</option>
	                                    <option value="<?php echo Yii::app()->createUrl('/vote/index', array('order_by'=>'total_vote',)) ?>">Vote Nhiều Nhất</option>
                                    </select>		                                
                                    <span class="txt_select"><span class="country_register_text"><?php echo $order[$order_by] ?></span></span> 
                                    <span class="btn_combo_down"></span> -->
                                </div>
                        </li>
                    </ul>
            	</div>
            </div>
            <div <?php echo VHelper::model()->parseAttributesHtml($this->option_html['main']['attributes'])?>>
                <?php echo $content;?>
            </div>
        </div>
    </div>
</body>
</html>
