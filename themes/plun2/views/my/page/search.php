    <div class="col-feed">
        <?php CController::forward('/NewsFeed/index', false); ?>
        <!-- news feed -->
    </div>
    <div class="col-right">
		<div class="members feed-hidden">
		    <h3>
		    	<a href="javascript:void(0);" class="btn-top btn-hide">
		    		<i class="imed imed-arrow-left"></i>
					<span class="text"><?php echo Lang::t('general', 'Hide'); ?></span>
				</a>
		        <a class="btn-top btn-open-feed" href="javascript:void(0);"><span class="text"><?php echo Lang::t('general', 'Show'); ?></span><i class="imed imed-arrow-right"></i></a>
		        <p><span class="result_total">0</span> <?php echo Lang::t('general', 'member_online_now', array('{html_tag_begin}' => '<a class="area" href="javascript:void(0);" title="">', '{html_tag_end}' => '</a>')); ?> <a href="javascript:void(0);" class="reference" title=""></a>
		        	
		        </p>    
		        <?php $this->widget('frontend.widgets.popup.Findhim', array()); ?>
		    </h3>
		    <div class="list clearfix quick-search-user">
		            <ul>
		               
		            </ul>
		            <div class="more-wrap-col2 show-more-searchresult" style="display: none;">
						<a class="showmore" onclick="showmore_searchresult();" href="javascript:void(0);"><?php echo Lang::t('general', 'Show More'); ?></a>
					</div>
		    </div>
		    <!-- members list -->
		</div>
		<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="0" />
		<input type="hidden" name="showmore_type" id="showmore_type" value="quicksearch" />
		<input type="hidden" name="quicksearch_keyword" id="quicksearch_keyword" value="<?php echo $q; ?>" />

        <!-- members area -->
    </div>

