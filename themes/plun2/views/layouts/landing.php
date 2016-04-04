<?php $this->beginContent('//layouts/layout'); ?>
<div class="wrapper_body">
	<div class="wrapper_main_menu left">
	  <div  class="main_menu">
			<ul>
				<li class="main_menu_avatar">
					<a href="javascript:void(0);" title="">
						<img width="35px" height="35px" src="/public/images/no-user.jpg" align="absmiddle" class="nav-username"/>
					</a>
				</li>
				<li class="main_menu_location">
					<a href="javascript:void(0);"><ins></ins></a>
				</li>
				<li class="main_menu_message">
					<a href="javascript:void(0);" class="nav-msg"><ins></ins>
						<label class="count" style="display: inline;">23</label>
					</a>
				</li>
				<li class="main_menu_alert">
					<a href="javascript:void(0);" class="nav-alert"><ins></ins>
						<label class="count" style="display: inline;">68</label>
					</a>
				</li>
				<li class="main_menu_friend">
					<a href="javascript:void(0);" class="nav-friend"><ins></ins>
						<label class="count" style="display: inline;">35</label>
					</a>
				</li>
				<li class="main_menu_photo">
					<a href="javascript:void(0);" class="nav-photo"><ins></ins>
						<label class="count" style="display: inline;">56</label>
					</a>
				</li>
				<li class="main_menu_candy"><a href="javascript:void(0);" class="coming-soon"><ins></ins></a></li>
				<li class="main_menu_bookmark"><a href="javascript:void(0);"><ins></ins></a></li>
				<li class="main_menu_setting"><a href="javascript:void(0);"><ins></ins></a></li>
			</ul>
		</div>
	</div>
	<div class="header scroll_overlow">
	  <div class="menu left">
	    <ul>
	      <li><a href="<?php echo Yii::app()->homeUrl;?>"><?php echo Lang::t('general', 'Explore')?></a></li>
	      <li><a href="<?php echo Yii::app()->createUrl('//hotbox');?>"><?php echo Lang::t('general', 'Hot box')?></a></li>
	      <li><a href="<?php echo Yii::app()->createUrl('//isu');?>"><?php echo Lang::t('general', 'Isu')?></a></li>
	      <li><a href="<?php echo CParams::load()->params->vtn->url.'/forum/forum.php';?>"><?php echo Lang::t('general', 'Forum')?></a></li>
	    </ul>
	  </div>
	  <div class="search_setting left">
	    <ul>
	      <li class="setting_list">
	        <a href="#" class="down_list" title="Setting"><ins></ins></a>
	        <div class="list_setting">
				<ol>            
					<li class="help">
						<a href="http://support.plun.asia/" target="_blank">
							<ins></ins>
							<span class="inline-text">Help</span>
						</a>
					</li>
	            	<li class="language_vi"><a href="/site/lang?_lang=vi"><ins></ins> Tiếng Việt</a></li>
					<li class="language_en"><a href="/site/lang?_lang=en" class="active"><ins></ins> English</a></li>   
				</ol>
	        </div>
	      </li>
	    </ul>
	  </div>	
	  <div class="logo">
	    <a title="plun.asia" href="/"></a>
	  </div>	  
	</div>
	<div class="wrapper_container left menuExists">
		<?php echo $content;?>
		<div class="clear"></div>
		<?php $this->beginContent('//layouts/partials/footer'); ?><?php $this->endContent(); ?>
	</div>
</div>
<?php $this->endContent(); ?>

