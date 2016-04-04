<?php
class CommunitiesController extends Controller
{	
	public $usercurrent;
	
	public function init(){
		if(!Yii::app()->user->isGuest){
			$this->usercurrent = Yii::app()->user->data();
		}
	}
	
	public function actionIndex()
	{
		$this->render('page/index', array());
	}
	
	public function actionCreate()
	{
		$model = new Community();
		$data = Yii::app()->request->getParam('Community');
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && !empty($this->usercurrent->id)){
			$model->attributes = $data;
			$community_alias = Util::getUsername($model->community_name);
			while ( Community::model()->findByAttributes(array('community_alias'=>$community_alias)) ) {
				$suffix += rand ( 1, 999 );
				$community_alias = $community_alias . $suffix;
			}			
			$model->community_alias = $community_alias;
			$model->created = time();
			$model->creator = $this->usercurrent->id;
			$model->validate();
			if(!$model->hasErrors()){
				$model->save();
				echo CJSON::encode(array('status'=>true));
			}else{
				echo CJSON::encode(array('status'=>false));
			}
		}
		Yii::app()->end();
	}
	
	
	public function actionListItem()
	{
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && !empty($this->usercurrent->id)){
			$cri = new CDbCriteria();
			$cri->addCondition("");
			
			$communities = Community::model()->findAll($cri);
			$this->renderPartial('partial/list-item', array(
					'communities'=>$communities,
			));
		}		
	}
	
	public function actionView()
	{
		$community_alias = Yii::app()->request->getParam('alias');
		$community = Community::model()->findByAttributes(array('community_alias'=>$community_alias));
		$this->render('page/view', array(
			'community'=>$community,
		));		
	}
	
	public function actionPostStatus()
	{
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest && !empty($this->usercurrent->id)){
			
		}		
	}
}