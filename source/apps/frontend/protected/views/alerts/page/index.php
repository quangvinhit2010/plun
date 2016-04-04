<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/alerts/common.js', CClientScript::POS_BEGIN);
?>
<!-- left column -->
<div class="col-right"> 
    <div class="alert_detail left" data-url="<?php echo $this->user->createUrl('//alerts/show');?>">
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
                $criteria->addCondition("(status = 1) AND (ownerid=:uid) AND timestamp BETWEEN :from AND :to");
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
                        $lbl = Lang::t('time', 'DATE_FORMAT_ELAPSED_OP1', array('{day}'=>date('d', $timestamp), '{month}'=>Lang::t('time', strtoupper(date('F', $timestamp)))));;
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
    <div class="right" style="margin-top:0px;"> <a href="#"><img src="<?php echo Yii::app()->theme->baseUrl . '/resources/html/'?>images/banner_120x600.jpg" align="absmiddle"></a> </div>
</div>
<!-- right column -->

<?php 
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'alertDetail', 'content'=>'')); 
?>
