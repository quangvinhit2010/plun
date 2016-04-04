<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/alerts/common.js', CClientScript::POS_BEGIN);
?>
<div class="alert_page" style="display: block;">
	<p class="title left"><span>Alert</span></p>
	<?php 
        $flgData = false;
        $today = date('d-m-Y', time());
        $yesterday = date('d-m-Y', strtotime("-1 day"));
        if(!empty($dates)):
            foreach ($dates as $date){
                 
                $from = strtotime($date['ndate']);
                $to = strtotime(date('d-m-Y', strtotime("+1 day", $from)));
                $criteria=new CDbCriteria();
                $criteria->addCondition("(ownerid=:uid) AND timestamp BETWEEN :from AND :to");
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
                    <div class="date left"><?php echo ucfirst(Lang::t('alerts', 'Sent')). ' '.ucfirst($lbl);?></div>
                    <div class="list_alert left">
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
