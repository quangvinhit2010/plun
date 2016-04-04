<?php if(isset($hotbox->images)) { ?>
	<div class="popup_hotbox" style="display: none;">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/supersized.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/supersized.shutter.css" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/jquery.easing.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/supersized.3.2.7.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/js/supersized.shutter.min.js"></script>
		<script type="text/javascript">
			var listImg = [			// Slideshow Images
	           					   	 	<?php foreach ($hotbox->images as $origin_key => $origin) {?>
	                                    {image : '<?php echo $origin->getImageOrigin(array(), true);?>?t=<?php echo time();?>', title : '<?php echo $hotbox->title?>', thumb : ''},
	       							<?php } ?>
	                        ];
	
		</script>
		<div class="gallery_hotbox left">
			<a class="gallery_close" href="javascript:void(0);"><i></i></a>
			<a id="prevslide" class="load-item"></a>
			<a id="nextslide" class="load-item"></a>
                    
            <div id="thumb-tray" class="load-item">
	            <div id="thumb-back"></div>
	            <div id="thumb-forward"></div>
            </div>
		</div>
		
		<div class="w400 detail_hotbox_400 right">
			<div class="wrap_detail">
				<h2><?php echo $hotbox->title;?></h2>
				<!--  
				<div class="date_location">
					<b>Location:</b> Hanoi, Vietnam I <b>Date:</b> 20/9/2013
				</div>
				-->
				<div class="content">
					<p><?php echo $hotbox->body;?></p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
