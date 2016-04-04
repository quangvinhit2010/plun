<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/about/about.js');
Yii::app()->clientScript->registerScript('aboutInit', "About.init();", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('aboutSlideImages', "About.slideImages();", CClientScript::POS_END);
?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.bxslider.js"></script>
<div class="popSlide">
    <div class="slider">
    </div>
</div>
<div class="aboutus_new">
    	<div class="menu">
        	<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/_about-menu')?>
        </div>
        <div class="about_gallery">
        	<ul>
            	<li>
                	<div class="pics">
                    	<a class="photos" href="#"><img src="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/VietPride14.jpg')?>" align="absmiddle" width="154" height="114"></a>                        
                    </div>
                	<div class="info">
                    	<p class="title"><a class="photos" href="#">VietPride14 (July 20, 2014)</a></p>
                    	
                    </div>
                    <div class="view_download"><a class="link_view photos" href="javascript:void(0);"><?php echo Lang::t('about', 'View');?></a> <a class="link_download" href="<?php echo Yii::app()->createUrl('site/download', array('path'=>Util::encrypt('/public/about/gallery/download/gCircuitSongKran8.zip')))?>"><?php echo Lang::t('about', 'Download');?></a></div>
                    <div class="slider-container slider" style="display: none;">
                          <ul class="bxsliders">   
                            <?php
                            $files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about'. DS . 'gallery' . DS . 'VietPride14' );
                            if(!empty($files)){
                                foreach ($files as $file){
                                    $pathinfo = pathinfo ( $file);
                                ?>                 
                                <li><div class="wrap-img"><img data-caption="Sự kiện được tổ chức tại Cung Văn Hoá Lao Động vào ngày 20/7/2014 vừa qua thu hút được hàng ngàn người tham dự. PLUN.ASIA là môt trong những nhà tài trợ chính của sự kiện này." src="/public/about/gallery/VietPride14/<?php echo $pathinfo['basename'];?>"></div></li>
                                <?php
                                }
                            }
                            ?>
                          </ul>
                    </div>
                </li>
            	<li>
                	<div class="pics">
                    	<a class="photos" href="#"><img src="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PLUNLaunchParty.jpg')?>" align="absmiddle" width="154" height="114"></a>                        
                    </div>
                	<div class="info">
                    	<p class="title"><a class="photos" href="#">PLUN.ASIA Launch Party (Oct 15, 2010)</a></p>
                    	
                    </div>
                    <div class="view_download"><a class="link_view photos" href="javascript:void(0);"><?php echo Lang::t('about', 'View');?></a> <a class="link_download" href="<?php echo Yii::app()->createUrl('site/download', array('path'=>Util::encrypt('/public/about/gallery/download/gCircuitSongKran8.zip')))?>"><?php echo Lang::t('about', 'Download');?></a></div>
                    <div class="slider-container slider" style="display: none;">
                          <ul class="bxsliders">   
                            <?php
                            $files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about'. DS . 'gallery' . DS . 'launch_party' );
                            if(!empty($files)){
                                foreach ($files as $file){
                                    $pathinfo = pathinfo ( $file);
                                ?>                 
                                <li><div class="wrap-img"><img data-caption="Hình ảnh sự kiện PLUN chính thức ra mắt phiên bản Beta vào ngày Ngày 15 tháng 10." src="/public/about/gallery/launch_party/<?php echo $pathinfo['basename'];?>"></div></li>
                                <?php
                                }
                            }
                            ?>
                          </ul>
                    </div>
                </li>
                <?php 
                $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('95946625')));
                ?>
                <li>
                	<div class="pics">
                    	<a class="view_video" href="javascript:void(0);"><img src="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/YeuNhauXaCach.jpg')?>" align="absmiddle" width="154" height="114"></a>
                    	<div class="play_video"><a href="javascript:void(0);"></a></div>
                    </div>
                	<div class="info">
                    	<p class="title"><a class="view_video" href="javascript:void(0);">Yêu trong xa cách</a></p>
                    	
                    </div>
                    <div class="view_download"><a class="link_view view_video" data-src="<?php echo $link;?>" href="#"><?php echo Lang::t('about', 'View');?></a> <a class="link_download" href="<?php echo Yii::app()->createUrl('site/download', array('path'=>Util::encrypt('/public/about/gallery/download/YeuTrongXaCach.mp4')))?>"><?php echo Lang::t('about', 'Download');?></a></div>
                    <div class="slider-container slider" style="display: none;">
                        <iframe data-caption="Một clip dài gần 4 phút kể về chuyện tình lãng mạn của hai teen boy đồng tính ở Việt Nam mới đây đã khiến nhiều cư dân mạng đồng loạt bị “đốn tim”. Với thời lượng gần 4 phút, clip có tên Yêu trong xa cách (Even We’re Apart) ghi lại những thước hình rất đẹp về chuyện tình của hai cậu bạn, dẫu cho họ nằm ở hai đất nước cách xa nhau nửa vòng trái đất." src="" width="720" height="407" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                </li>
                <?php 
                $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('95723111')));
                ?>
                <li>
                	<div class="pics">
                    	<a class="view_video" href="javascript:void(0);"><img src="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PurpleStories.jpg')?>" align="absmiddle" width="154" height="114"></a>
                    	<div class="play_video"><a href="javascript:void(0);"></a></div>
                    </div>
                	<div class="info">
                    	<p class="title"><a class="view_video" href="javascript:void(0);">Purple Stories</a></p>
                    
                    </div>
                    <div class="view_download"><a class="link_view view_video" data-src="<?php echo $link;?>" href="#"><?php echo Lang::t('about', 'View');?></a> <a class="link_download" href="<?php echo Yii::app()->createUrl('site/download', array('path'=>Util::encrypt('/public/about/gallery/download/PurpleStories.mp4')))?>"><?php echo Lang::t('about', 'Download');?></a></div>
                    <div class="slider-container slider" style="display: none;">
                        <iframe data-caption="Purple stories số 1: 'Bắt sóng cảm xúc', sẽ cùng anh Alex và đôi bạn Nguyên và Trân... ngắt cánh hoa trả lời 'nghi vấn': He's gay? Not gay? Gay? Not gay?." src="" width="720" height="407" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                </li>
                <li>
                	<div class="pics">
                    	<a class="photos" href="#"><img src="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/SongKran.jpg')?>" align="absmiddle" width="154" height="114"></a>
                    </div>
                	<div class="info">
                    	<p class="title"><a class="photos" href="#">gCircuit Song Kran 8 Supersized</a></p>
                    
                    </div>
                    <div class="view_download"><a class="link_view photos" href="javascript:void(0);"><?php echo Lang::t('about', 'View');?></a> <a class="link_download" href="<?php echo Yii::app()->createUrl('site/download', array('path'=>Util::encrypt('/public/about/gallery/download/gCircuitSongKran8.zip')))?>"><?php echo Lang::t('about', 'Download');?></a></div>
                    <div class="slider-container slider" style="display: none;">
                          <ul class="bxsliders">   
                            <?php
                            $files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about' . DS . 'gallery' . DS . 'gCircuitSongKran8' );
                            $pFolder = pathinfo ( Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about' . DS . 'gallery' . DS . 'gCircuitSongKran8'. DS);
                            //E:\Projects\__DWM\plun.asia/public/about
                            if(!empty($files)){
                                foreach ($files as $file){
                                    $pathinfo = pathinfo ( $file);
                                    $url = str_replace($pFolder, '', $pathinfo['dirname']);
                                    $cur_file=str_replace('\\','/',$url);
                                ?>                              
                                <li><div class="wrap-img"><img data-caption="Hình ảnh sự kiện gCircuit Song Kran 8 Supersized được tổ chức tại Thái Lan với sự hội tụ của dàn HOTBOY đến từ khắp nơi trên thế giới. PLUN.ASIA giữ vai trò là nhà tài trợ chính tại hạng mục "Trang kết bạn chính thức"." src="/public/about/gallery/gCircuitSongKran8/<?php echo $cur_file.DS.$pathinfo['basename'];?>"></div></li>
                                <?php
                                }
                            }
                            ?>
                          </ul>
                    </div>
                </li>
            </ul>
    	</div>
  </div>