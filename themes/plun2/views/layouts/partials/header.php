<?php
$arrEx = array('newsFeed', 'my', 'photo', 'messages', 'bookmark','chat', 'alerts', 'friend', 'settings');
$clsExplore = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, $arrEx)) ? ' class="active"' : '';
$clsHotbox = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, array('hotbox'))) ? ' class="active"' : '';
$clsIsu = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, array('isu'))) ? ' class="active"' : '';
$attributes = !empty($attributes) ? $attributes : array();
?>
<div <?php echo VHelper::model()->parseAttributesHtml($attributes)?>>
  <div class="menu left">
    <ul>
      <li <?php echo $clsExplore;?>><a href="<?php echo Yii::app()->homeUrl;?>"><?php echo Lang::t('general', 'Explore')?></a></li>
      <li <?php echo $clsHotbox;?>><a href="<?php echo Yii::app()->createUrl('//hotbox');?>"><?php echo Lang::t('general', 'Hot box')?></a></li>
      <li <?php echo $clsIsu;?>><a href="<?php echo Yii::app()->createUrl('//isu');?>"><?php echo Lang::t('general', 'Isu')?></a></li>
      <li><a href="<?php echo CParams::load()->params->vtn->url.'/forum/forum.php';?>"><?php echo Lang::t('general', 'Forum')?></a></li>
		<!--        
      <li><a target="_blank" href="http://purpleguy.plun.asia/"><?php echo Lang::t('general', 'Purple Guy')?></a></li>
		-->
    </ul>
  </div>
  <div class="search_setting left">
    <ul>
      <li class="setting_list">
        <a title="<?php echo Lang::t('general', 'Control Panel')?>" class="down_list" href="#"><ins></ins></a>
        <div class="list_setting">
          <ol>            
            <?php if(!Yii::app()->user->isGuest){?>
            	<?php 
            	$userCurrent =  Yii::app()->user->data();
            	?>
<!--
            	<li class="pics">
					<a href="<?php echo $userCurrent->createUrl('//photo/index');?>">
						<ins></ins> 
						<span class="inline-text"><?php echo Lang::t('general', 'Photo')?></span>
					</a>
				</li>
				<li class="mess">
					<a href="<?php echo $userCurrent->createUrl('//messages/index');?>">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Message')?></span>
					</a>
				</li>
				<li class="bookmark">
					<a href="<?php echo $userCurrent->createUrl('//bookmark/index');?>">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Bookmark')?></span>
					</a>
				</li>
				<li class="alert">
					<a href="<?php echo $userCurrent->createUrl('//alerts/index');?>">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Alerts')?></span>
					</a>
				</li>
				<li class="friends">
					<a href="<?php echo $userCurrent->createUrl('//friend/index');?>">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Friends')?></span>
					</a>
				</li>
				<li class="candy"><a href="#"><ins></ins> <?php echo Lang::t('general', 'Candy')?></a></li>
				<li class="vote"><a href="#"><ins></ins> <?php echo Lang::t('general', 'Rate')?></a></li>
-->				
				<li class="user">
					<a href="<?php echo $userCurrent->getUserUrl();?>">
						<ins><img width="16" src="<?php echo $avatar = $userCurrent->getAvatar().'?t='.time();?>" class="nav-username"/></ins>
						<span class="inline-text"><b><?php echo $userCurrent->getDisplayName();?></b></span>
					</a>
				</li>
				<li class="setting">
					<a href="<?php echo $userCurrent->createUrl('//settings/index');?>">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Setting')?></span>
					</a>
				</li>
				<li class="help">
					<a target="_blank" href="http://support.plun.asia/">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Help')?></span>
					</a>
				</li>
				<li class="language_vi"><a <?php echo (Yii::app()->language == VLang::LANG_VI) ? 'class="active"' : ''?> href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'vi'))?>"><ins></ins> Tiếng Việt</a></li>
				<li class="language_en"><a <?php echo (Yii::app()->language == VLang::LANG_EN) ? 'class="active"' : ''?> href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'en'))?>"><ins></ins> English</a></li>                                    
            	<li class="logout"><a href="<?php echo Yii::app()->createUrl('//site/logout');?>"><ins></ins> <?php echo Lang::t('general', 'Log Out')?></a></li>
            <?php }else{?>
            	<li class="help">
					<a target="_blank" href="http://support.plun.asia/">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Help')?></span>
					</a>
				</li>
            	<li class="language_vi"><a <?php echo (Yii::app()->language == VLang::LANG_VI) ? 'class="active"' : ''?> href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'vi'))?>"><ins></ins> Tiếng Việt</a></li>
				<li class="language_en"><a <?php echo (Yii::app()->language == VLang::LANG_EN) ? 'class="active"' : ''?> href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'en'))?>"><ins></ins> English</a></li>   
            <?php }?>
          </ol>
        </div>
      </li>
    </ul>
  </div>

  <div class="logo">
    <a href="<?php echo Yii::app()->homeUrl;?>" title="plun.asia"></a>
  </div>
  
</div>

<div class="search_setting left frm_search_user">
    <ul>
        <li class="search">
            <?php $this->widget('frontend.widgets.UserPage.Quicksearch', array()); ?>
        </li>
        <li>
            <a class="txtBlock" href="<?php echo Yii::app()->createUrl('/site/page/view/about');?>"><?php echo Lang::t('about', 'About PLUN.ASIA')?></a>
        </li>
        <li class="">
            <a href="<?php echo Yii::app()->createUrl('/site/contact');?>"><?php echo Lang::t('general', 'Contact Us')?></a>
        </li>
        <?php if(isset(Yii::app()->params->candy) && !Yii::app()->user->isGuest): ?>
        <li class="candyNumberShow">
			<a href="<?php echo $this->usercurrent->createUrl('//candy/index');?>"><ins class="icon_common"></ins><?php echo !empty($this->usercurrent->balance->candy) ? number_format($this->usercurrent->balance->candy) : 0;?></a>
		</li>
		<?php endif;?>
    </ul>
</div>
<div id="popup_frmLienHe" class="popup_genneral" style="display:none;">

</div>
<script type="text/javascript">
    $('.frm_search_user ul li.showFrmPopup a').on('click',function(){
        objCommon.loading();
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: 'site/popContact',
            success: function(res){
                $('#popup_frmLienHe').html(res);
                setTimeout(function(){
                    objCommon.unloading();
                    $('#popup_frmLienHe').pdialog({
                        open: function(){
                            objCommon.outSiteDialogCommon(this);
                        },
                        dialogClass: 'closeCommon',
                        width: 845
                    });
                },300);
            }
        });

        return false;
    });
</script>
