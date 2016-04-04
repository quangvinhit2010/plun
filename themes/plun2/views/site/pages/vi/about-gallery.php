<div id="anchor-gallary" class="parallax-anchor"></div>
<div class="say_hello clearfix items_effect">
    <div class="left txtCenter">
        <div class="line_bg left"></div>
        <div class="line_bg right"></div>
        <span>Gallery</span>
    </div>
</div>
<div class="list_gallery_about parentCenter">
    <ul class="clearfix">
        <li class="wrap_item_img">
            <div class="gallerySlideShow">
                <ul>
                    <li>
                        <a class="groupGallery wrap_img" href="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/VietPride14.jpg')?>">
                            <img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/VietPride14.jpg')?>" />
                            <span class="mask"><i></i></span>
                        </a>
                    </li>
                    <?php
                    $files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about'. DS . 'gallery' . DS . 'VietPride14' );
                    if(!empty($files)){
                        foreach ($files as $file){
                            $pathinfo = pathinfo ( $file);
                            ?>
                            <li><a class="groupGallery" href="/public/about/gallery/VietPride14/<?php echo $pathinfo['basename'];?>"></a></li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </li>
        <li class="wrap_item_img">
            <div class="gallerySlideShow">
                <ul>
                    <li>
                        <a class="groupGallery wrap_img" href="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PLUNLaunchParty.jpg')?>">
                            <img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PLUNLaunchParty.jpg')?>" />
                            <span class="mask"><i></i></span>
                        </a>
                    </li>
                    <?php
                    $files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about'. DS . 'gallery' . DS . 'launch_party' );
                    if(!empty($files)){
                        foreach ($files as $file){
                            $pathinfo = pathinfo ( $file);
                            ?>
                            <li><a class="groupGallery" href="/public/about/gallery/launch_party/<?php echo $pathinfo['basename'];?>"></a></li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </li>
        <li class="video_gallery wrap_item_img">
            <?php  $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('95946625'))); ?>
            <a href="<?php echo $link;?>" class="wrap_img">
                <i></i><img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/YeuNhauXaCach.jpg')?>" />
                <span class="mask"><i></i></span>
            </a>
        </li>
        <li class="video_gallery wrap_item_img">
            <?php  $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('95723111'))); ?>
            <a href="<?php echo $link;?>" class="wrap_img">
                <i></i><img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PurpleStories.jpg')?>" />
                <span class="mask"><i></i></span>
            </a>
        </li>
        <li class="wrap_item_img">
            <div class="gallerySlideShow">
                <ul>
                    <li>
                        <a class="groupGallery wrap_img" href="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/SongKran.jpg')?>">
                            <img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/SongKran.jpg')?>" />
                            <span class="mask"><i></i></span>
                        </a>
                    </li>
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
                            <li><a class="groupGallery" href="/public/about/gallery/gCircuitSongKran8/<?php echo $cur_file.DS.$pathinfo['basename'];?>"></a></li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </li>
        <li class="wrap_item_img">
                <div class="gallerySlideShow">
                    <ul>
                        <li>
                            <a class="groupGallery wrap_img" href="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PurpleGuy.jpg')?>">
                                <img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/PurpleGuy.jpg')?>" />
                                <span class="mask"><i></i></span>
                            </a>
                        </li>
                        <?php
							$files = CFileHelper::findFiles(Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about' . DS . 'gallery' . DS . 'PurpleGuy' );
							$pFolder = pathinfo ( Yii::getPathOfAlias ( 'pathroot' ) . DS . 'public' . DS . 'about' . DS . 'gallery' . DS . 'PurpleGuy'. DS);
							//E:\Projects\__DWM\plun.asia/public/about
							if(!empty($files)){
								foreach ($files as $file){
									$pathinfo = pathinfo ( $file);
									$url = str_replace($pFolder, '', $pathinfo['dirname']);
									$cur_file=str_replace('\\','/',$url);
								?>                 
							<li><a class="groupGallery" href="/public/about/gallery/PurpleGuy/<?php echo $cur_file.DS.$pathinfo['basename'];?>"></a></li>
							<?php
							}
						}
						?>
                    </ul>
                </div>
            </li>
		<li class="video_gallery wrap_item_img">
            <?php  $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('114857402'))); ?>
            <a href="<?php echo $link;?>" class="wrap_img">
                <i></i><img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/Video3.jpg')?>" />
                <span class="mask"><i></i></span>
            </a>
        </li>            
		<li class="video_gallery wrap_item_img">
            <?php  $link = Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('114856676'))); ?>
            <a href="<?php echo $link;?>" class="wrap_img">
                <i></i><img data-original="<?php echo Yii::app()->createUrl('/public/about/gallery/thumbnail/Video4.jpg')?>" />
                <span class="mask"><i></i></span>
            </a>
        </li>            
    </ul>
</div>