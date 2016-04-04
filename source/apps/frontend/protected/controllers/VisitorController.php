<?php

class VisitorController extends MemberController
{
	
	/*
	public function filters(){
		return array(
				array(
						'CHttpCacheFilter + index',
						'cacheControl' => " max-age=100, must-revalidate",
						'lastModified'=>date('Y-m-d H:i:s'),
				),
				array(
						'COutputCache',
						'duration'=>100,
						'varyByParam'=>array('id'),
				),
		);
	}
	*/
	
	public function actionIndex()
	{
		$this->render('page/index', array(
	    ));
	}
	
	public function actionListItem()
	{
		$usercurrentView = VstUser::model()->getUser($this->usercurrent);
		$limit = CParams::load()->params->visitor->limit_show_free;
		$nextMore = CParams::load()->params->visitor->more_will_charge_limit;
		
		$c = new EMongoCriteria;
		$c->viewed_id('==', $this->usercurrent->id);
		$totalAll = VstUserViewUser::model()->count($c);
// 		$c->timestamp('>=', mktime('0','0','0'));
// 		$c->timestamp('<=', mktime('23','59','59'));
		$c->limit($limit + $nextMore);
		$c->sort('timestamp', EMongoCriteria::SORT_ASC);
		$vstUserViewUser = VstUserViewUser::model()->findAll($c);
		
		
		$this->renderPartial('partial/list-item', array(
			'totalAll'=>$totalAll,					
			'nextMore'=>$nextMore,					
			'limit'=>$limit,					
			'vstUserViewUser'=>$vstUserViewUser,					
			'usercurrentView'=>$usercurrentView,					
		));
	}
	
	public function actionMore()
	{
		$offset = Yii::app()->request->getParam('offset', 1);
		$limit = CParams::load()->params->visitor->limit_show_free;
		$usercurrentView = VstUser::model()->getUser($this->usercurrent);
		
		$c = new EMongoCriteria;
		$c->viewed_id('==', $this->usercurrent->id);
		$totalAll = VstUserViewUser::model()->count($c);
		$c->limit($limit)->offset($offset);
		$c->sort('timestamp', EMongoCriteria::SORT_ASC);
		$vstUserViewUser = VstUserViewUser::model()->findAll($c);	
		if(!empty($vstUserViewUser) && !$usercurrentView->isLimitRightToViewVisitor()){
			$html = $this->renderPartial('partial/item', array(
					'limit'=>$limit,
					'vstUserViewUser'=>$vstUserViewUser), true);		
			echo CJSON::encode(array('status'=>true, 'offset'=>$offset + $limit, 'url'=>$this->usercurrent->createUrl('//visitor/more', array('offset' => $offset + $limit)), 'html' => $html));			
		}else{
			echo CJSON::encode(array('status'=>false));
		}	
	}
	
}