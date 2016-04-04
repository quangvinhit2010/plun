<?php
/* @var $this SiteController */

$this->pageTitle .= ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<div class="aboutus_new">
    	<div class="menu">
    	    <?php $this->renderPartial('//site/pages/'.Yii::app()->language.'/_about-menu')?>
        </div>
        <div class="video_about">
        	<div class="left">
            	<div class="video_vimeo">
                    <code>
                        <iframe src="<?php echo Yii::app()->createUrl('//site/player', array('type'=>'vimeo', 'vid'=>Util::encryptRandCode('95474535')));?>" width="720" height="407" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </code>
                </div>
            </div>
            <div class="right slogan">
            	<p>“At PLUN.ASIA, we give singles the opportunity to express themselves through various tools.”</p>
                <p><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/signname_plun_asia.jpg" align="right"></p>
            </div>
        </div>
        <div class="content_aboutus_new">
        	<div class="left">
            	<div class="title_aboutus_new"></div>
            </div>
            <div class="right">
                <p><b>Who We Are</b></p>
                <p>Launched in summer of 2010, PLUN.ASIA (formerly known as PLU-N.COM) pioneered the online GLBT (Gay/Lesbian/Bisexual/Transgender) dating industry in Viet Nam. Besides creating romantic opportunities for singles to find someone special we also provide tools for friends to stay connected. Over six years of planning and development, we’ve learned more and more about what people want to offer an array of features from personals, social networking, community and news with a very unique gay touch.</p>
                 
                <p><b>How It Works</b></p>
                <p>At PLUN.ASIA, we give singles the opportunity to express themselves through various tools. Profiles may include up to five (5) photos with preferences regarding the person they’re searching for and if you became that user's friend you'll gain access to his daily updates via the News Feed. And to help ensure the integrity of our community, every profile and photo is screened by our Customer Care team for appropriateness before it’s posted to the site.</p>
                 
                <p><b>What Is PLUN</b></p>
                <p>PLUN stands for PLU Nation and PLU (People Like Us) is a common term in Hong Kong, Singapore and Malaysia indicating the GLBT community. </p>
                <p>PLUN is a British Virgin Islands business and operated in Viet Nam by its strategic partner Dream Weavers Online Services JSC.</p>
                
                
            </div>
        </div>
        
    </div>

    
    
    
<div class="bg_contactus" style="display: none;">
    <div class="aboutus">
	    <div class="header_about">
	        <div class="w550 auto_center video_clip">
	            <code>
	                <iframe width="560" height="315" src="//www.youtube.com/embed/uHe9F3usg0Y" frameborder="0" allowfullscreen></iframe>
	            </code>
	            <!--<iframe width="560" height="315" src="//www.youtube.com/embed/uHe9F3usg0Y" frameborder="0" allowfullscreen></iframe>-->
	        </div> 
	    </div>
	</div>
    <div class="w900 content_about">
	    <!--<div class="w550 auto_center">
	        <p><b>Kelvin To</b></p>
	        <p class="ceo">CEO and Founder of</p>   
	    </div>-->       
	    <div class="w550 auto_center">
	        	<h2>Who We Are</h2>
                <p>Launched in summer of 2010, PLUN.ASIA (formerly known as PLU-N.COM) pioneered the online GLBT (Gay/Lesbian/Bisexual/Transgender) dating industry in Viet Nam. Besides creating romantic opportunities for singles to find someone special we also provide tools for friends to stay connected. Over six years of planning and development, we’ve learned more and more about what people want to offer an array of features from personals, social networking, community and news with a very unique gay touch.</p>
                 
                <h2>How It Works</h2>
                <p>At PLUN.ASIA, we give singles the opportunity to express themselves through various tools. Profiles may include up to five (5) photos with preferences regarding the person they’re searching for and if you became that user's friend you'll gain access to his daily updates via the News Feed. And to help ensure the integrity of our community, every profile and photo is screened by our Customer Care team for appropriateness before it’s posted to the site.</p>
                 
                <h2>What Is PLUN</h2>
                <p>PLUN stands for PLU Nation and PLU (People Like Us) is a common term in Hong Kong, Singapore and Malaysia indicating the GLBT community. </p>
                <p>PLUN is a British Virgin Islands business and operated in Viet Nam by its strategic partner Dream Weavers Online Services JSC.</p>
		</div>
	    
	</div>
</div>    
