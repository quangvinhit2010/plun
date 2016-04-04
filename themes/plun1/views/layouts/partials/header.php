<?php 
$username = $avatar = $url = $urlMy = Yii::app()->homeUrl;
$urlPhoto = $urlMsg = $urlFriend = $urlAlert = $urlSetting = 'javascript:void(0);';
if(!Yii::app()->user->isGuest){
    $userCurrent =  Yii::app()->user->data();
	$url = Yii::app()->homeUrl;
	$urlMy = $userCurrent->getUserUrl();
	$username = $userCurrent->getDisplayName();
	$avatar = $userCurrent->getAvatar().'?t='.time();
	$urlPhoto = $userCurrent->createUrl('//photo/index');
	$urlMsg = $userCurrent->createUrl('//messages/index');
	$urlFriend = $userCurrent->createUrl('//friend/index');
	$urlAlert = $userCurrent->createUrl('//alerts/index');
	$urlSetting = $userCurrent->createUrl('//settings/index');
	$urlBookmark = $userCurrent->createUrl('//bookmark/index');
}
$controller = Yii::app()->controller;
$arrEx = array('newsFeed', 'my', 'photo', 'messages', 'bookmark','chat', 'alerts', 'friend', 'settings');
$clsExplore = (!empty($controller) && in_array($controller->id, $arrEx)) ? ' class="current"' : '';
$clsHotbox = (!empty($controller) && in_array($controller->id, array('hotbox'))) ? ' class="current"' : '';
$clsIsu = (!empty($controller) && in_array($controller->id, array('isu'))) ? ' class="current"' : '';
$clsBookmark = (!empty($controller) && in_array($controller->id, array('bookmark'))) ? ' class="current"' : '';
Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
?>
<div class="header">
	<div class="logo">
		<a href="<?php echo $url;?>" title=""></a>
	</div>
	<!-- logo -->
	<div class="navigation">
		<ul>
			<li<?php echo $clsExplore;?>><a href="<?php echo $url;?>" title=""><?php echo Lang::t('general', 'Explore')?></a> <span class="line"></span></li>
			<li<?php echo $clsHotbox;?>><a href="<?php echo Yii::app()->createUrl('//hotbox');?>" title=""><?php echo Lang::t('general', 'Hot box')?></a> <span class="line"></span></li>
			<!-- 
			 <li><a href="#" title="" class="coming-soon"><?php echo Lang::t('general', 'Hot box')?></a> <span class="line"></span>
			 -->
			<li<?php echo $clsIsu;?>><a href="<?php echo Yii::app()->createUrl('//isu');?>" title=""><?php echo Lang::t('general', 'Isu')?></a> <span class="line"></span></li>
			<!--
			<li><a href="#" title="" class="coming-soon"><?php echo Lang::t('general', 'Talk That Talk')?></a> <span class="line"></span>
			</li>
			
			 -->
			<li>
				<a target="_blank" href="http://purpleguy.plun.asia/" title=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/PurpleGuy_banner.jpg" align="absmiddle"></a>
				<span class="line"></span>
			</li>
		</ul>
	</div>
	<!-- page navigation -->
	<div class="navigation-sec">
		<?php $this->widget('frontend.widgets.UserPage.Quicksearch', array()); ?>
		<?php 
		if(!empty(Yii::app()->language)){
            $arrLang = VLang::model()->getLangs();
            $language = Yii::app()->language;
// 		    $_lang = Yii::App()->locale->getLocaleDisplayName($language);
		    if(!empty($arrLang[$language])){
                $langCls = $arrLang[$language]['class'];
                $langTitle = $arrLang[$language]['title'];
            }
		?>
		<div class="navblock nav-country">
			<span class="line"></span> 
			<a href="#" class="btn-country" data-toggle="dropdown"><i class="imed <?php echo $langCls;?>"></i><span class="text"><?php echo $langTitle;?></span><i class="imed imed-arrow-wd"></i></a>
			<div class="spr-submenu">
				<ul>
				    <?php foreach ($arrLang as $key=>$item){?>
					<li><a href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>$key))?>"><i class="imed <?php echo $item['class'];?>"></i><span class="text"><?php echo $item['title'];?></span></a></li>
					<?php }?>
				</ul>
			</div>
			<!-- submenu -->
		</div>
		
		<script type="text/javascript">
			$(function(){
				$(".btn-country").click(function () {
					var parent = $(this).parent();
					$('.spr-submenu').css({width: parent.width() - 7});
				});	
			});
		</script>
		<?php 
        }    
		?>
		<?php if(!Yii::app()->user->isGuest){?>
		<!-- sub nav -->
		<div class="navblock nav-username">
			<span class="line"></span>
			<a href="<?php echo $urlMy;?>"><img border="" alt="" src="<?php echo $avatar; ?>"><span class="username"><?php echo $username;?></span></a>
		</div>
		<!-- sub nav -->
		<div class="navblock nav-setting">
				<span class="line"></span>
				<a data-target="#" data-toggle="dropdown" href="#"><i class="i18 i18-setting"></i></a>
				<div class="setting-bot">
					<span class="arrow"><i></i></span>
					<div class="setting-bot-board">
						<ul>
							<li>
								<a href="<?php echo $urlPhoto;?>">
									<i class="i18 i18-photo"></i> 
									<span class="inline-text"><?php echo Lang::t('general', 'Photo')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo $urlMsg;?>">
									<i class="i18 i18-message"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Message')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo $urlBookmark;?>">
									<i class="i18 i18-bookmark"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Bookmark')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo $urlAlert;?>">
									<i class="i18 i18-alert"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Alerts')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo $urlFriend;?>">
									<i class="i18 i18-friend"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Friends')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo $urlSetting;?>">
									<i class="i18 i18-account"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Setting')?></span>
								</a>
							</li>
							<li>
								<a target="_blank" href="http://support.plun.asia/">
									<i class="i18 i18-help"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Help')?></span>
								</a>
							</li>
							<li>
								<a href="<?php echo Yii::app()->createUrl('//site/logout');?>">
									<i class="i18 i18-logout"></i>
									<span class="inline-text"><?php echo Lang::t('general', 'Log Out')?></span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<?php }?>
		<!-- sub nav -->
	</div>
	<!-- second navigation -->
</div>