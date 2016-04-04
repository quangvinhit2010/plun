<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/alerts.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('alert',
		"$(document).ready(function(){
			objCommon.hw_common();
		});
		$(window).resize(function(){
			objCommon.hw_common();
		});",
		CClientScript::POS_END);
?>
<div class="container pheader min_max_1024 clearfix hasBanner_300 alert_page">
	<div class="explore left">
		<div class="list_explore alert_detail" data-url="<?php echo $this->user->createUrl('//alerts/show');?>">
            <div class="shadow_top"></div>
		<div class="title"><?php echo Lang::t('alerts', 'Alerts');?></div>
		<div class="content">
			<?php 
	        $flgData = false;
	        $today = date('d-m-Y', time());
	        $yesterday = date('d-m-Y', strtotime("-1 day"));
	        if(!empty($dates)):
	    	    foreach ($dates as $date){
	    	        
	                $from = strtotime($date['ndate']);
	                $to = strtotime(date('d-m-Y', strtotime("+1 day", $from)));
	                $criteria=new CDbCriteria();
	                $criteria->addCondition("(status = 1) AND (ownerid=:uid || owner_type='system') AND timestamp BETWEEN :from AND :to");
	        	    $criteria->params = array(':uid'=>$user->id, ':from'=>$from, ':to'=>$to);
	                $criteria->order = "timestamp DESC";
	                $datas = XNotifications::model()->findAll($criteria);
	                if(!empty($datas)):
	                    $flgData = true;
	                    $lbl = '';
	                    if($date['ndate']==$today){
	                        $lbl = Lang::t('alerts', 'Today');
	                    }elseif($date['ndate']==$yesterday){
	                        $lbl = Lang::t('alerts', 'Yesterday');
	                    }else{
	                        $timestamp = strtotime($date['ndate']);
	                        $lbl = Lang::t('time', 'DATE_FORMAT_ELAPSED_OP1', array('{day}'=>date('d', $timestamp), '{month}'=>Lang::t('time', strtoupper(date('F', $timestamp)))));
	                    }
	                ?>
	                    <div class="list_date">
	                    	<h3><?php echo ucfirst($lbl);?></h3>
	                        <ul>                	
	                	        <?php $this->renderPartial('partial/_view-alert', array(
	                    	            'datas'=>$datas,
	                    	    ));?>
	                        </ul>
	                    </div>
	                <?php 
	                endif;
	            }
	        endif;
	        ?>
		</div>
	</div>
	<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_300)); ?>		
	</div>
</div>

<div class="popup_general popup_alert" title="Alert" style="display:none;"></div> 
