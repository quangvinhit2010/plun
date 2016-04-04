<?php $this->pageTitle=Yii::app()->name; ?>

<h2>Users & Systems</h2>
<div class="modules">
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//user/user/admin'); ?>" title="Users"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png"> Users</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//user/user/admin'); ?>">Admin</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//user/user/index'); ?>">List</a></li>
		</ul>
	</div>
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//TranslatePhpMessage/translate'); ?>" title="Systems"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Systems</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//TranslatePhpMessage/translate'); ?>">Translate</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//global/default/config'); ?>">Config</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//global/default/clearSesion'); ?>">ClearSession</a></li>
		</ul>
	</div>
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//notification/send/sendToAll'); ?>" title="Systems"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Send Notification</a></h3>
	</div>
</div>

<h2>Website</h2>
<div class="modules">
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//systems/userTop/admin'); ?>" title="User Top"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">User Top</a></h3>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//background/home/slide'); ?>" title="Home Slide"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Home Slide</a></h3>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//global/default/clearcache'); ?>" title="Clear Cache"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Clear Cache</a></h3>
	</div>
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//ReportingTool/default'); ?>" title="Reporting Tool"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Reporting Tool</a></h3>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//hobby/hobbies/admin'); ?>" title="Hobbies"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Hobbies</a></h3>
	</div>	
</div>
<div class="modules">
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//hotbox/hotbox/admin'); ?>" title="Hot Box"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Hot Box</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//hotbox/hotbox/create'); ?>">Create</a></li>
		</ul>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//systems/notes/admin'); ?>" title="ISU"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">ISU</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//systems/notes/create'); ?>">Create</a></li>
		</ul>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//systems/photo/admin'); ?>" title="Photos"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Photos</a></h3>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//venues/venues/admin'); ?>" title="venues"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Venues</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//venues/category/admin'); ?>">Category</a></li>
		</ul>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//background/default/config'); ?>" title="Background"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Background</a></h3>
	</div>	
</div>
<div class="modules">	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//banner/sysBanner/admin'); ?>" title="Banner"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Banner</a></h3>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//systems/V2Feedback'); ?>" title="V2 Feedback"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">V2 Feedback</a></h3>
	</div>	
</div>

<h2>Events</h2>
<div class="modules">	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//purpleguy/purpleguyEvent'); ?>" title="Purple Guy"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Purple Guy</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//purpleguy/purpleguyPhoto/admin'); ?>">Photos</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//purpleguy/purpleguyComment/admin'); ?>">Comments</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//purpleguy/purpleguyContact/admin'); ?>">Contact Form</a></li>
		</ul>
	</div>
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//table42/Table42Round/admin'); ?>" title="Purple Guy"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png">Table For Two</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42Round/admin'); ?>">Rounds</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42Profile/admin'); ?>">Profiles</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42Profile/approved'); ?>">Profiles (Approved)</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42Profile/notapproved'); ?>">Profiles (Not Approved)</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42DatingRequest/admin'); ?>">Couples</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42DatingRequest/win'); ?>">Win Couples</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42DatingRequest/topvote'); ?>">Top Vote Couples</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//table42/Table42Result/admin'); ?>">Results</a></li>
		</ul>
	</div>	
	<div class="module">
		<h3><a href="<?php echo Yii::app()->createUrl('//coupon/events/admin'); ?>" title="Coupons"><img alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/default.png"> Coupons</a></h3>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('//coupon/events/admin'); ?>">View Events</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('//coupon/giftcode/import'); ?>">Import GiftCode</a></li>
		</ul>
	</div>
	
		
</div>

