<?php

class Notify extends CWidget {
    public $view;
    public function run() {
        if (!Yii::app()->user->isGuest && !empty($this->view)) {
            $data = array();
            switch ($this->view){
                case 'account-activation';
		            $user = Yii::app()->user->data();
                    $data['user'] = $user;
                    break;
                case 'from_plun';
                	
                    break;
            }
            $this->render($this->view, $data);
        }
    }

}