<div id="blog_slide">
	<div class="content_blog">
		<span class="icon_common btn_slide flag_<?php echo Yii::app()->language;?>"></span>
        <div class="wrap_blog">
        	<?php if(!empty(CParams::load()->params->from_plun->birthday)): ?>
        	<div class="birth_user_alert loadingItem">
            	<p><i class="icon_common"></i><?php echo Lang::t('notify', 'Birthday today')?>:</p>
            </div>
            <div id="more_birthday" class="birth_user_alert_more u_list_add" style="display: none; height: 485px;">
            	
            </div>
            <script type="text/javascript">
			    jQuery('document').ready(function(){
			    	setTimeout(function(){
			    		objCommon.loadingInside($('.birth_user_alert p'));
			    		$.ajax({
						      type: "POST",
						      url: '/search/getBirthdayList?limit=100&offset=0&date=<?php echo date('m-d')?>',
						      data: {type: 'getbirthday'},
						      dataType: 'json',
						      success: function( response ) {
							      	if(response.result == true){
								      	var html = '', htmlMore = '<div class="scrollPopup"><ul class="clearfix">';
										$(response.data).each(function(index, val){
											if(index == 0){
												html += '<p><i class="icon_common"></i><?php echo Lang::t('notify', 'Birthday today')?>: <a href="/u/'+val.username+'" target="_blank">'+val.username+'</a>';
												if($(response.data).length > 1){
									        		html += ' và <a href="javascript:void(0);" class="bd_more">' +$(response.data).length+ ' <?php echo Lang::t('notify', 'others')?></a>';
									        	}
												html += '</p>';
												$('.birth_user_alert').html(html);
											}else{
												htmlMore += '<li><a class="left" title="'+val.username+'" href="/u/'+val.username+'" target="_blank"><img src="'+val.avatar+'" width="50" height="50" /></a>';
												htmlMore += '<div class="clearfix list_r_u"> <div class="right c_item_box"> <div class="_tempH"></div> <div class="c_item">'; 
// 												htmlMore += '<a href="#" class="btn_add_friend icon_common" title="Add Friend"></a>';
// 												htmlMore += '<a href="#" class="btn_friend icon_common" title="Friend"></a>';
												htmlMore += '<a data-id="'+val.username+'" class="icon_common icon_msg_user quick-chat" href="javascript:void(0);"></a>'; 
												htmlMore += '</div> </div>'; 
												htmlMore += '<div class="n_u"> <div class="c_item_box"> <div class="_tempH"></div> <div class="c_item"><a title="'+val.username+'" href="/u/'+val.username+'" target="_blank">'+val.username+'</a></div> </div> </div> </div></li>';
											}
							        	});
                                        htmlMore += '</ul></div>';
										$('.birth_user_alert_more').html(htmlMore);
										objCommon.sprScroll('#more_birthday .scrollPopup');
										objCommon.unloadingInside();
							      	}else{
							      		$('.birth_user_alert').hide();
							      	}
						      }
						});
			    	},500);
			    	$(document.body).on('click', '.birth_user_alert .bd_more', function(event) {
				    	$('.birth_user_alert_more').pdialog({
							title: '<?php echo Lang::t('notify', 'Birthday today')?>',
							open: function(event, ui) {
                                objCommon.outSiteDialogCommon(this);
							},
							width: 480
						});
						return false;
			    	});
			    });
			</script>
            <?php endif;?>
            <div class="tabs">
            	<ul class="clearfix">
                    <?php if(!empty(CParams::load()->params->from_plun->news)): ?>
                        <li class="active"><a href="javascript:void(0);" rel="#tabBlog"><?php echo Lang::t('general', 'News');?></a></li>
                    <?php endif;?>
                    <?php if(!empty(CParams::load()->params->from_plun->feedback)): ?>
                		<li><a href="javascript:void(0);" rel="#feedback_frm"><?php echo Lang::t('general', 'Feedback');?></a></li>
                	<?php endif;?>
            	</ul>
            </div>
            <?php if(!empty(CParams::load()->params->from_plun->news)): ?>
            <div id="tabBlog" class="list_news_blog tabsCommon">
            	<ul class="clearfix">
                	<li>
                    	<a href="http://blog.plun.asia/plun-mang-xa-hoi-danh-cho-cong-dong-lgbt/" target="_blank">
                        	<span class="wrap_img"><img src="/public/images/avatar65x65.jpg" /></span>
                            <span class="wrap_right">
                                <b>PLUN mạng xã hội dành cho cộng đồng LGBT</b>
                                <span>Plun là mạng xã hội đi tiên phong trong việc tiếp cận xu hướng sử dụng công nghệ mới của giới trẻ thuộc cộng đồng LGBT</span>
                                <span style="display: none;">Chúng tôi sắp ra mắt tính năng Private Photo (ảnh riêng tư) và Candy (tiền ảo). Đây là tính năng hấp dẫn giúp bạn có thể đăng tải hình ảnh riêng tư của mình lên mạng và chia sẻ với những người thật sự muốn xem. Và người được xem hình sẽ được 1 lượng Candy phù hợp.</span>
                            </span>
                        </a>
                    </li>                    
                </ul>
            </div>            
            <?php endif;?>
            
            <?php if(!empty(CParams::load()->params->from_plun->feedback)): ?>
			<div id="feedback_frm" class="feedback-panel open tabsCommon">
			    <h3><?php echo Lang::t('newsfeed', 'PLUN.ASIA Website Assessment Table');?></h3>
			    <div id="form-wrap">
			        <form method="post" action="<?php echo Yii::app()->createUrl('//event/feedback')?>">
			        	<select name="level">
			        		<option value="good">Good</option>
			        		<option value="well">Well</option>
			        		<option value="bad">Bad</option>
			        	</select>
			            <p><?php echo Lang::t('newsfeed', 'Which PlUN.ASIA\'s features need to be improved ?');?></p>
			            <textarea id="message" name="message"></textarea><br>
			            <input type="submit" class="button" value="<?php echo Lang::t('newsfeed', 'Submit');?>">
			        </form>
			    </div>
			</div>		
			<script type="text/javascript">
			    jQuery('document').ready(function(){
                    if($('#blog_slide .content_blog .wrap_blog .list_news_blog ul li').length > 5){
                        $('#blog_slide .content_blog .wrap_blog .list_news_blog').addClass('scroll_box');
                        objCommon.sprScroll('#blog_slide .content_blog .wrap_blog .list_news_blog');
                    }
			    	$(document.body).on('click', '#feedback_frm .button', function(event) {
			    		objCommon.loading();
			    		var form = $("#feedback_frm form");
			    		$.ajax({
						      type: "POST",
						      url: form.attr( 'action' ),
						      data: form.serialize(),
						      success: function( response ) {
						    	  var boxShow = $("#blog_slide");
						    	  var _this = $('#blog_slide .btn_slide')
						    	  if(_this.hasClass('active_open')){
						              boxShow.animate({
						                  right: -375
						              },300);
						              _this.removeClass('active_open');
						          }else{
						              boxShow.animate({
						                  right: 0
						              },300);
						              _this.addClass('active_open');
						          }
						    	  $('#message').val('');
						    	  objCommon.unloading();
						      }
						});	        		
						return false;
			    	});    	        	
			    });
			</script>	
			<?php endif;?>
        </div>
    </div>
</div>

