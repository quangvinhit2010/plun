<?php

class DefaultController extends Controller
{
	public $layout='//layouts/column2';
	
	public function actionIndex($from = NULL, $to = NULL)
	{
		if(empty($_REQUEST)) {
			$from = mktime('0','0','0');
			$to = mktime('23','59','59');
		} else {
			$from = strtotime($from . " 00:00:00");
			$to = strtotime($to . " 23:59:59");
		}
		
		$report = Yii::app()->db->createCommand()
					->select('DATE_FORMAT(FROM_UNIXTIME(createtime), "%d/%m/%Y") as Date, COUNT(*) as Total')
					->from('usr_user')
					->where('createtime >= :from AND createtime <= :to', array(':from'=>$from, ':to'=>$to))
					->order('createtime DESC')
					->group('Date')
					->queryAll();
		
		$total = 0;
		foreach($report as $r) {
			$total += $r['Total'];
		}
		
		$report = new CArrayDataProvider($report, array('keyField' => 'Date', 'pagination' => array('pageSize' => 31,),));
		
		if(empty($_REQUEST)) {
			$this->render('index', array('report'=>$report, 'total'=>$total));
		} else {
			$this->renderPartial('_view', array('report'=>$report, 'total'=>$total));
		}
	}
}