<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/isotope.js', CClientScript::POS_END);
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/imagesloaded.pkgd.min.js', CClientScript::POS_END);*/
Yii::app()->clientScript->registerScript('homepage',
"
var getItemsCommonFirst = setInterval(function(){
    if($('.logo_homepage').length > 0){
        objCommon.hw_home();
        clearInterval(getItemsCommonFirst);
    }
},0);
		
	$('.privacy_popup').click(function(){
        $('#popup_privacy').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar('#popup_privacy .wrap_content_scroll');
    $('.agreenment_popup').click(function(){
        $('#popup_Users_Agreement').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar('#popup_Users_Agreement .wrap_content_scroll');
    $('.community_popup').click(function(){
        $('#popup_community').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar('#popup_community .wrap_content_scroll');
    
$(window).resize(function(){
	objCommon.hw_home();
});",
CClientScript::POS_END);
$count = Member::model()->cache(500)->countByAttributes(array('status'=>1));
?>
<div class="wrapper_body">
    	<div class="home_login left">
        	<div class="logo_homepage"><a title="plun.asia" href="<?php echo Yii::app()->homeUrl;?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/logo_homepage.png" align="absmiddle"/></a></div>
            <div class="form_login_homepage">
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
			)); ?>
            	<ul>
                	<li>
                        <div class="numUser">
                            <h3>
                                <?php echo number_format($count);?>
								<!--                                  
                                <a href="<?php echo Yii::app()->createUrl('//landing/index');?>" class="icon_common">“<?php echo Lang::t('general', 'Explore')?>”</a>
								-->
                            </h3>
                        </div>

                    </li>
                    <li><p class="member"><?php echo Lang::t('general', 'Members worldwide')?></p></li>
                    <li>
                    	<div class="<?php echo ($model->hasErrors('username')) ? 'error' : '';?>">
					        <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'txt')); ?>					        
					        <div class="error_block">
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'username'); ?>                 		        
                            </div>
				        </div>
                    </li>
                    <li>
                    	<div class="<?php echo ($model->hasErrors('password')) ? 'error' : '';?>">
	                    	<?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'txt')); ?>
	                    	<div class="error_block">
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'password'); ?>                 		        
                            </div>
						</div>
                    </li>
                    <li>
                    	<div>                    		
                    		<label for="LoginForm_rememberMe"><?php echo $form->checkBox($model,'rememberMe'); ?><?php echo Lang::t('login', 'Remember me');?></label> | 
                    		<a class="forgot" href="<?php echo Yii::app()->createUrl('//site/forgotpass')?>"><?php echo Lang::t('login', 'Forgot your Password?');?></a>
                    	</div>
					</li>
                    <li>
	                    <?php echo CHtml::submitButton(Lang::t('login', 'Sign in'), array('class'=>'but but_signin')); ?>
	                    <input class="but but_signup" name="" type="button" value="<?php echo Lang::t('login', 'Sign up now');?>" onclick="window.location.href='<?php echo Yii::app()->createUrl('//register');?>'"/>
                    </li>
                </ul>
			<?php $this->endWidget(); ?>
			<?php if(!empty(CParams::load()->params->loginsocial->enable)){?>
                <div class="loginSocialUser">
                    <p>Đăng nhập bằng tài khoản</p>
                    <a href="javascript:void(0);" onclick="window.open('/register/Facebook', 'win001', 'width=500, height=500');" class="icon_common icon_faceLogin"></a>
                    <a href="javascript:void(0);" onclick="window.open('/register/Google', 'win002', 'width=500, height=500');" class="icon_common icon_googleLogin"></a>
                </div>
			<?php }?>
            </div>
            <div class="footer_homepage">
            	<ul class="main_cate clearfix">
                	<li><a href="<?php echo Yii::app()->createUrl('//hotbox');?>"><?php echo Lang::t('general', 'Hot box')?></a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('//isu');?>"><?php echo Lang::t('general', 'Isu')?></a></li>
                    <li><a class="txtBlock" href="<?php echo CParams::load()->params->vtn->url.'/forum/forum.php';?>"><?php echo Lang::t('general', 'Forum')?></a></li>
                    <li class="lang_icon"><a class="icon_common icon_<?php echo Yii::app()->language;?>" href="#"></a></li>
                </ul>
            	<ul class="clearfix line_2">
	            	<li><a target="_blank" href="<?php echo Yii::app()->createUrl('/site/page/view/about');?>" title=""><?php echo Lang::t('about', 'About PLUN.ASIA')?></a></li>
	            	<li>|</li>
					<li><a href="<?php echo Yii::app()->createUrl('/site/page/view/about');?>#popup_privacy" class="privacy_popup" title="<?php echo Lang::t('general', 'Privacy')?>"><?php echo Lang::t('general', 'Privacy')?></a></li>
					<li>|</li>
					<li><a href="<?php echo Yii::app()->createUrl('/site/page/view/about');?>#popup_Users_Agreement" class="agreenment_popup" title="<?php echo Lang::t('general', 'Users Agreement')?>"><?php echo Lang::t('general', 'Users Agreement')?></a></li>
				</ul>
                <ul class="clearfix line_3">
                    <li><a href="<?php echo Yii::app()->createUrl('/site/page/view/about');?>#popup_community" class="community_popup" title="<?php echo Lang::t('general', 'Community Guidelines')?>"><?php echo Lang::t('general', 'Community Guidelines')?></a></li>
                    <li>|</li>
                    <li><a target="_blank" href="<?php echo Yii::app()->createUrl('/site/contact');?>" title="<?php echo Lang::t('general', 'Contact Us')?>"><?php echo Lang::t('general', 'Contact Us')?></a></li>
                </ul>
	            <?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/privacy')?>
				<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/users-agreement')?>
				<?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/community-guidelines')?>
            </div>
        </div>
        <div class="home_hot_pics left">
        	<?php 
        	$cri = new CDbCriteria();
			$cri->addCondition("status=1");
			$cri->order = "`order` ASC";
			$homePosition = HomePosition::model()->findAll($cri);
        	if(!empty($homePosition)){?>
        	<ul id="masonry_box" class="hero-masonry">
        		<?php 
//         		$files = array_reverse($files);
        		foreach ($homePosition as $position){?>
        			<?php 
					if($position->photo){
						$attr = HomePhoto::model()->getAttrByColRow($position->col, $position->row);
						$srcImg = $attr['pathThumb'].DS.$position->photo->file_name;
        			?>
        			<li data-col="<?php echo $position->col;?>" data-row="<?php echo $position->row;?>" class="item is-loading"><a class="wrap-img-loading" data-srcImg="/<?php echo $srcImg;?>" href="<?php echo $position->photo->link;?>"><span></span><img src="" align="absmiddle" /></a></li>
        			<?php }?>
        		<?php }?>
        	</ul>
        	<?php }else{?>
        	<ul id="masonry_box" class="hero-masonry">
            	<li data-col="1" data-row="2" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_ver_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="2" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_hor_b.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_b.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="2" data-row="2" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_4x_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="2" data-row="2" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_4x_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="2" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_ver_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="2" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_hor_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="2" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_hor_a.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
                <li data-col="1" data-row="1" class="item is-loading"><a class="wrap-img-loading" data-srcImg="https://dl.dropboxusercontent.com/u/43486987/html_ver2.0/images/pics_1x_d.jpg" href="javascript:void(0);"><span></span><img src="" align="absmiddle" /></a></li>
            </ul>
            <?php }?>
        </div>
        <div class="clear"></div>
	</div>

<div class="popup_language choose_lang" style="display: none;">
	<ul>
		<li><label><?php echo Lang::t('general', 'Choose your language');?></label></li>
		<li>
			<a class="en" href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'en'))?>"><ins></ins> English </a> 
			<a class="vn" href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>'vi'))?>"><ins></ins> Tiếng Việt</a>
		</li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){

        objCommon.tooltipPlun({
            posiBottom: true,
            el: '.loginSocialUser a.icon_faceLogin',
            titleTip: "<?php echo Lang::t('login', 'Don\'t worry, we won\'t post anything on your timeline.')?>"
        });

        objCommon.tooltipPlun({
            posiBottom: true,
            el: '.loginSocialUser a.icon_googleLogin',
            titleTip: "<?php echo Lang::t('login', 'Don\'t worry, we won\'t post anything on your timeline.')?>"
        });

		objCommon.hw_home();
		// Choose language
// 		if(!$.cookie("chooseLang")){			
// 			popupLanguage();
// 		}
		
// 		$(".choose_lang ul li a").click(function(){
// 			var date = new Date();
// 			var minutes = 15;
// 			date.setTime(date.getTime() + (minutes * 60 * 1000));
// 			$.cookie("chooseLang", true, { expires: date, path: '/' });			
// 		});
		
		$(document.body).on('click', '.lang_icon a', function(event) {
			popupLanguage();			
		});
		/* click outsite */			
		$(document).click( function(e){
			var overlay = $('.ui-widget-overlay')[0];
			var target = e.target;
			if(overlay == target){
				$( ".popup_language" ).dialog("close");
// 				$(".choose_lang ul li:eq(1)").hide();
			}
		});

		function popupLanguage(){
			$( ".popup_language" ).exists(function(){
				$( ".popup_language" ).dialog({
					open: function(event, ui) {
						$("body").css({ overflow: 'hidden' });
						objCommon.no_title(this); // config trong file jquery-ui.js
						$(".ui-dialog-titlebar-close").hide();// tat nut close
					},
					close: function(event, ui) {
						$("body").css({ overflow: 'inherit' });
					},
					resizable: false,
					position: 'middle',
					draggable: false,
					autoOpen: false,
					width:'auto',
					center: true,
					modal: true			
				});
			});			
			$( ".popup_language" ).dialog("open");
		}
		
	});
	$(window).resize(function(){
		objCommon.hw_home();
	});
</script>