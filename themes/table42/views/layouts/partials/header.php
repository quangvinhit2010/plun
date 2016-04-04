<?php
$arrEx = array('newsFeed', 'my', 'photo', 'messages', 'bookmark','chat', 'alerts', 'friend', 'settings');
$clsExplore = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, $arrEx)) ? ' class="active"' : '';
$clsHotbox = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, array('hotbox'))) ? ' class="active"' : '';
$clsIsu = (!empty(Yii::app()->controller) && in_array(Yii::app()->controller->id, array('isu'))) ? ' class="active"' : '';
$attributes = !empty($attributes) ? $attributes : array();
$params = CParams::load ();
?>
<div <?php echo VHelper::model()->parseAttributesHtml($attributes)?>>
  <div class="menu left">
    <ul>
      <li <?php echo $clsExplore;?>><a href="<?php echo $params->params->base_url . Yii::app()->homeUrl;?>"><?php echo Lang::t('general', 'Explore')?></a></li>
      <li <?php echo $clsHotbox;?>><a href="<?php echo $params->params->base_url . Yii::app()->createUrl('//hotbox');?>"><?php echo Lang::t('general', 'Hot box')?></a></li>
      <li <?php echo $clsIsu;?>><a href="<?php echo $params->params->base_url . Yii::app()->createUrl('//isu');?>"><?php echo Lang::t('general', 'Isu')?></a></li>
    </ul>
  </div>
  <div class="search_setting left">
    <ul>
		<li class="search">
			<?php $this->widget('frontend.widgets.UserPage.Quicksearch', array()); ?>
		</li>
      <li class="setting_list">
        <a title="Setting" class="down_list" href="#"><ins></ins></a>
        <div class="list_setting">
          <ol>            
            <?php if(!Yii::app()->user->isGuest){?>
            	<?php 
            		$userCurrent =  Yii::app()->user->data();
            	?>		
				<li class="user">
					<a href="<?php echo $params->params->base_url . $userCurrent->getUserUrl();?>">
						<ins><img width="16" src="<?php echo $avatar = $userCurrent->getAvatar().'?t='.time();?>" class="nav-username"/></ins>
						<span class="inline-text"><b><?php echo $userCurrent->getDisplayName();?></b></span>
					</a>
				</li>
				<li class="setting">
					<a href="<?php echo $params->params->base_url . $userCurrent->createUrl('//settings/index');?>">
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
				<li class="language_vi"><a <?php echo $params->params->base_url . (Yii::app()->language == VLang::LANG_VI) ? 'class="active"' : ''?> href="<?php echo $params->params->base_url . Yii::app()->createUrl('//site/lang', array('_lang'=>'vi'))?>"><ins></ins> Tiếng Việt</a></li>
				<li class="language_en"><a <?php echo $params->params->base_url . (Yii::app()->language == VLang::LANG_EN) ? 'class="active"' : ''?> href="<?php echo $params->params->base_url . Yii::app()->createUrl('//site/lang', array('_lang'=>'en'))?>"><ins></ins> English</a></li>                                    
            	<li class="logout"><a href="<?php echo Yii::app()->createUrl('//site/logout');?>"><ins></ins> <?php echo Lang::t('general', 'Log Out')?></a></li>
            <?php }else{?>
            	<li class="help">
					<a target="_blank" href="http://support.plun.asia/">
						<ins></ins>
						<span class="inline-text"><?php echo Lang::t('general', 'Help')?></span>
					</a>
				</li>
            	<li class="language_vi"><a <?php echo $params->params->base_url . (Yii::app()->language == VLang::LANG_VI) ? 'class="active"' : ''?> href="<?php echo $params->params->base_url . Yii::app()->createUrl('//site/lang', array('_lang'=>'vi'))?>"><ins></ins> Tiếng Việt</a></li>
				<li class="language_en"><a <?php echo $params->params->base_url . (Yii::app()->language == VLang::LANG_EN) ? 'class="active"' : ''?> href="<?php echo $params->params->base_url . Yii::app()->createUrl('//site/lang', array('_lang'=>'en'))?>"><ins></ins> English</a></li>   
            <?php }?>
          </ol>
        </div>
      </li>
    </ul>
  </div>
  <div class="logo">
    <a href="<?php echo $params->params->base_url . Yii::app()->homeUrl;?>" title="plun.asia"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/logo.png" align="absmiddle" /></a>
  </div>
  
</div>