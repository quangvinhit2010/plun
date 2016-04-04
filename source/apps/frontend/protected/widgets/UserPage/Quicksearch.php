<?php

class Quicksearch extends CWidget {

    public function run() {
        $q	=	Yii::app()->request->getParam('q', false);
        if (!Yii::app()->user->isGuest) {
            $this->render('quicksearch', array(
                'q' => $q,
            	'userCurrent'	=>	Yii::app()->user->data()
            ));
        }
    }

}