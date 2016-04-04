<?php
/**
 * @author vinhnguyen
 * @desc Rate Controller
 */
class RateController extends MemberController
{
	public function actionIndex()
	{
	    $this->renderPartial('partial/index', array());
	}
}