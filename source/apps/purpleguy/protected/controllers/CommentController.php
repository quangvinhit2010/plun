<?php

class CommentController extends Controller
{
	public function actionIndex()
	{
		$this->render('//comment/page/index');
	}
	
	public function actionPost()
	{
	    if(Yii::app()->user->isGuest)
	        throw new CHttpException(403, 'Not Login');
	    
		$model = new PurpleguyComment();

        if (isset($_POST['PurpleguyComment'])) {
            $data = $_POST['PurpleguyComment'];
            $obj = PurpleguyProfile::model()->findByAttributes(array('user_id'=>$data['item']));
            if ($obj) {
                $params = array('item' => null);
                $model->attributes = $data;
                $model->created_date = time();
                $model->created_by = $this->usercurrent->id;
                $model->approved = 1;
                $model->type_id = PurpleguyComment::COMMENT_PROFILE;
                $model->item_id = $obj->user_id;

                if ($model->save()) {                    
                    $params['item'] = $model;
                    
                    $purpleGuyRound = PurpleguyRound::model()->getCurrentRound();
                    $userVote = PurpleguyUserVote::model()->findByAttributes(array('user_id'=>$obj->user_id, 'round_id'=>$purpleGuyRound->id));
                    if(!empty($userVote)){
                        $userVote = $userVote;
                    }else{
                        $userVote = new PurpleguyUserVote();
                        $userVote->user_id = $obj->user_id;
                        $userVote->round_id = $purpleGuyRound->id;
                    }
                    $userVote->total_comment += 1;
                    $userVote->validate();
                    $userVote->save();
                    $this->renderPartial("//comment/partial/comment-item", $params);
                }
            }
            Yii::app()->end();
        }
        Yii::app()->end();
	}
	
	public function actionCommentsPrevious() {
	    $page = Yii::app()->request->getParam('page', 1);
	    $params['object_id'] = Yii::app()->request->getParam('object_id');
	    $params['data'] = PurpleguyComment::model()->getComments($params['object_id'], PurpleguyComment::COMMENT_PROFILE, $page);
	    $this->renderPartial("//comment/partial/previous-comment", $params);
	    Yii::app()->end();
	}
}