<?php
class BookmarkController extends MemberController {

	
	
	public function actionIndex($page = null){
		
		$page = (isset($page)) ? $page : 1;
		$criteria = new CDbCriteria;
		$criteria->addCondition('user_id = :user_id');
		$criteria->params = array(':user_id' => $this->user->id);
// 		$criteria->order = 't.created DESC';
		$criteria->with = array(
				'user' => array(
					'alias'		=> 'u',
					'order'=>'u.username ASC',
				),
		);
		$total = Bookmark::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = Yii::app()->params->page_limit['bookmark_limit'];
		$pages->applyLimit($criteria);
		
		$next_page = ($total > $pages->pageSize * $page) ? $page + 1 : 'end' ; 
		
		
		$bookmarks = Bookmark::model()->findAll($criteria);
		
		$city_in_cache = new CityonCache();
		$district_in_cache = new DistrictonCache();
		$country_in_cache   =   new CountryonCache();
		
		if(Yii::app()->request->isAjaxRequest){
			if(count($bookmarks) > 0 ){
				$this->renderPartial('partial/index_ajax',array(
						'bookmarks' => $bookmarks,
						//'pages' => $pages,
						'total_bookmarks' => $total,
						'next_page' => $next_page,
						'country_info' => $country_in_cache->getListCountry(),
						'city_info' => $city_in_cache->getListCity(),
				));
			} else {
				echo 'end';
				Yii::app()->end();
			}
		} else {
			$this->render('page/index', array(
					'bookmarks' => $bookmarks,
					'pages' => $pages,
					'total_bookmarks' => $total,
					'next_page' => $next_page,
					'country_info' => $country_in_cache->getListCountry(),
					'city_info' => $city_in_cache->getListCity(),
			));
		}
		
	}
	
	
	
	public function actionDelete($id){
		if(isset($id)){
			$model =	Bookmark::model()->find('target_id = :target_id AND user_id = :user_id', array('target_id' => $id, ':user_id' => Yii::app()->user->id));
			if(isset($model)) {
				$model->delete();
				echo json_encode(array('status' => 'true', 'bookmark_count' => Bookmark::model()->count('user_id = :user_id', array(':user_id' => Yii::app()->user->id))));
				Yii::app()->end();
			}
		}
	}
	
	public function actionAdd($id){
		if(isset($id) && Yii::app()->request->isAjaxRequest && isset(Yii::app()->user->id)){
			$return = '';
			if(Member::model()->findByPk($id)){
				$add = new Bookmark();
				$add->user_id = Yii::app()->user->id;
				$add->target_id = $id;
				$add->created = time();
				$add->save();
				$return =  json_encode(array('status' => 'true'));
			} else {
				$return =  json_encode(array('status' => 'false'));
			}
			echo $return;
			Yii::app()->end();
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Bookmark::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function checkProfile($id){
		
	}
	
	protected function beforeRender($view)
	{
		parent::beforeRender($view);
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/bookmark.js', CClientScript::POS_END);
		return true;
		return true;
	
	}
}