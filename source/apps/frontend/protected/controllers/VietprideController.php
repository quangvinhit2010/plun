<?php
class VietprideController extends Controller
{	
	public function actionIndex()
	{
		$this->layout='//layouts/user-page';
		$model_gift	=	CmsLoginGifts::model();
		$model=new LoginForm();
		$login_success	=	false;
		
		
		if(isset($_POST['LoginForm']) && Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['LoginForm'];
			$model->validate();
			if(!$model->hasErrors() && $model->login()){
				//check gift
				
				 $this->redirect(Yii::app()->createUrl('//vietpride'));
			}
		}else{
			if(Yii::app()->user->id){
				if($model_gift->checkGift(Yii::app()->user->id)){
					$model_gift				=	new CmsLoginGifts();
					$model_gift->user_id	=	Yii::app()->user->id;
					$model_gift->date_login	=	time();
					$model_gift->ip	=	$_SERVER['REMOTE_ADDR'];
						
					$model_gift->save();	
					$login_success	=	1;
				}else{
					$login_success	=	0;
				}
			}
		}		
	    $this->render('viet-pride', array(
	    		'login_success'	=>	$login_success,
	    		'model'=>$model
	    ));
	}
	public function actionList(){
		$limit = Yii::app()->params->search_result['limit_display'];
		$offset = Yii::app()->request->getParam('offset', 0);
		
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
				
		$model_gift	=	CmsLoginGifts::model();
		
		$result	=	$model_gift->getWinners($offset, $limit);
				
	    $this->render('viet-pride-list', array(
	    		'total_result'	=>	$result['total'],
	    		'my_friendlist'	=>	$my_friendlist,
	    		'show_more'	=>	$result['show_more'],
	    		'offset' => $offset + $limit,
	    		'data'	=>	$result['data']
	    ));		
	}
	public function actionShowmore(){
		$limit = Yii::app()->params->search_result['limit_display'];
		$offset = Yii::app()->request->getParam('offset', 0);
		
		$my_friendlist	=	Friendship::model()->getAllFriendID(Yii::app()->user->id);
		
		$model_gift	=	CmsLoginGifts::model();
		
		$result	=	$model_gift->getWinners($offset, $limit);
		
        $html = $this->renderPartial('viet-pride-list-showmore', array(
        	'my_friendlist'	=>	$my_friendlist,
        	'total_result'	=>	$result['total'],
           'data'	=>	$result['data']), true);

        $array_result_json = array(
            'html' => $html,
           	'show_more'	=>	$result['show_more'],
            'offset' => $offset + $limit,
        );
        echo json_encode($array_result_json);
        exit;	
	}
}