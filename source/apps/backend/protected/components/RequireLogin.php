<?php
class RequireLogin extends CBehavior
{
	public function attach($owner)
	{
		$owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
	}

	public function handleBeginRequest($event)
	{
		$app = Yii::app();
		$user = $app->user;

		$request = trim($app->urlManager->parseUrl($app->request), '/');
		$login = trim($user->loginUrl[0], '/');

		$allowed = CMap::mergeArray(
			array($login),
			Yii::app()->params['notRequireLogin']
		);
		
		if ($user->isGuest && !in_array($request, $allowed)){
			$user->loginRequired();
		}else{
			$userdata = $user->data();
			if(!empty($userdata->id) && $userdata->superuser <= 0){
				$user->logout();
				// 			throw new CHttpException(403, 'You are not authorized to perform this action. !');
				$app->request->redirect('/');
			}	
		}
			
		$request = substr($request, 0, strlen($login));
		if (!$user->isGuest && $request == $login)
		{
			$url = $app->createUrl('//site/index');
			$app->request->redirect($url);
		}
	}
}